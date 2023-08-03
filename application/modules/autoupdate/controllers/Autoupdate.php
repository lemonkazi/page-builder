<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autoupdate extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();

		$model_list = [
			'settings/Core_settings_model' => 'MCores'
		];
		$this->load->model($model_list);

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];

		/** Hook point */
		$this->hooks->call_hook('autoupdate_construct');

		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Check autoupdate exist or not
	 *
	 * @return 	void
	 */
	public function index()
	{
		if (http_response($this->config->item('autoupdate_uri')))
		{
			$updates = json_decode(file_get_contents($this->config->item('autoupdate_uri')), TRUE);
			$config = file_get_contents('./config.ini');
			foreach ($updates as $version => $require)
			{
				if (my_version_compare(trim($version), trim($config)) > 0)
				{
					$php_zip = extension_loaded('zip');
					$php_curl = extension_loaded('curl');
					if ( ! $php_zip || ! $php_curl)
					{
						$temp['alert_type'] = 'error';
						$temp['header'] = $this->lang->line('autoupdate_index_php_module_error_heading');
						$temp['content'] = $this->lang->line('autoupdate_index_php_module_error_content');
						if ( ! $php_zip)
						{
							$temp['content'] .= $this->lang->line('autoupdate_index_php_zip_error_content');
						}
						if ( ! $php_curl)
						{
							$temp['content'] .= $this->lang->line('autoupdate_index_php_curl_error_content');
						}
						$this->load->view('error', array('data'=>$temp));
						break;
					}
					if (my_version_compare(trim($config), trim($require)) < 0)
					{
						$temp['alert_type'] = 'error';
						$temp['header'] = $this->lang->line('autoupdate_index_current_version_error_heading');
						$temp['content'] = sprintf($this->lang->line('autoupdate_index_current_version_error_content'), $require, $require);

						$this->load->view('error', array('data'=>$temp));
						break;
					}

					$c_user = getmyuid();
					$temp_file = tempnam(sys_get_temp_dir(), 'TMP');
					$p_user = fileowner($temp_file);
					@unlink($temp_file);

					$license = $this->MCores->get_by_name('license_key');
					if (count($license) > 0)
					{
						$url = $this->config->item('license_api') . 'verify_key/' . $license['value'];
						/** curl */
						$ch = @curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, FALSE);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$output = @curl_exec($ch);
						@curl_close($ch);
					}
					else
					{
						$output = '';
					}

					if ($c_user != $p_user)
					{
						$temp['alert_type'] = 'error';
						$temp['header'] = $this->lang->line('autoupdate_index_error_heading');
						$temp['content'] = $this->lang->line('autoupdate_index_error_content');

						$this->load->view('error', array('data'=>$temp));
					}
					else if ($output != 'valid')
					{
						$temp['alert_type'] = 'error';
						$temp['header'] = $this->lang->line('autoupdate_index_invalid_error_heading');
						$temp['content'] = $this->lang->line('autoupdate_index_invalid_error_content');

						$this->load->view('error', array('data'=>$temp));
					}
					else
					{
						$temp['alert_type'] = 'info';
						$temp['header'] = $this->lang->line('autoupdate_index_alert_heading');
						$temp['content'] = sprintf($this->lang->line('autoupdate_index_alert_content'), 'autoupdate/update');

						$this->load->view('alert', array('data'=>$temp));
					}
					break;
				}
				else
				{
					redirect('sites', 'refresh');
				}
			}
		}
		else
		{
			redirect('sites', 'refresh');
		}
	}

	/**
	 * Auto Update
	 *
	 * @return 	void
	 */
	public function update()
	{
		/** VERY RISKY SHOT */
		/** We need to run this script once started even if user close their browser */
		@ignore_user_abort(TRUE);
		/** Ignore php time limit which is generally 30 sec. */
		@set_time_limit(0);

		$return = TRUE;
		$return_json = [];

		$license = $this->MCores->get_by_name('license_key');
		$url = $this->config->item('license_api') . 'get_json/' . $license['value'];
		/** curl */
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = @curl_exec($ch);
		@curl_close($ch);
		if ($output == '')
		{
			$return_json['code'] = 0;
			$return_json['content'] = sprintf($this->lang->line('autoupdate_update_error_json_uri'), site_url('sites'));
			die(json_encode($return_json));
		}

		$updates = json_decode(file_get_contents($output), TRUE);
		$config = file_get_contents('./config.ini');
		$rev_updates = array_reverse($updates);

		foreach ($rev_updates as $version => $section)
		{
			if (my_version_compare(trim($version), trim($config)) > 0)
			{
				/** Run codeupdate before controler method */
				if (isset($section['codeupdate']['before']))
				{
					$method = $section['codeupdate']['before'];
					$mObj = $this->load->module('codeupdate');
					$output = $mObj->codeupdate->$method();
				}

				/** Enable write permission (777) to the folders */
				if (isset($section['permission_directories']))
				{
					foreach ($section['permission_directories'] as $key => $value)
					{
						@chmod(FCPATH . $key, 0777);
					}
				}

				/** Add files one by one */
				if (isset($section['add_files']))
				{
					foreach ($section['add_files'] as $key => $value)
					{
						$write = FCPATH . $value;
						/** Create folder in Local Server if not exist */
						if ( ! file_exists(dirname($write)))
						{
							@mkdir(dirname($write), 0777, true);
						}
						/** Read the file from Remote Server */
						$read = file_get_contents($key);
						/** Write the file in Local Server */
						if ( ! file_put_contents($write, $read))
						{
							$return = FALSE;
						}
					}
				}

				/** Run codeupdate after_add_files controler method */
				if (isset($section['codeupdate']['after_add_files']))
				{
					$method = $section['codeupdate']['after_add_files'];
					$mObj = $this->load->module('codeupdate');
					$ObjBuf = $mObj->codeupdate->$method();
				}

				/** Delete files one by one */
				if (isset($section['delete_files']))
				{
					foreach ($section['delete_files'] as $key => $value)
					{
						@chmod(FCPATH . $key, 0777);
						@unlink(FCPATH . $key);
					}
				}

				/** Run codeupdate after_delete_files controler method */
				if (isset($section['codeupdate']['after_delete_files']))
				{
					$method = $section['codeupdate']['after_delete_files'];
					$mObj = $this->load->module('codeupdate');
					$ObjBuf = $mObj->codeupdate->$method();
				}

				/** Delete directories recursively */
				if (isset($section['delete_directories']))
				{
					foreach ($section['delete_directories'] as $key => $value)
					{
						$this->path = FCPATH . $key;

						if (file_exists($this->path))
						{
							recursive_delete($this->path);
						}
					}
				}

				/** Run codeupdate after_delete_directories controler method */
				if (isset($section['codeupdate']['after_delete_directories']))
				{
					$method = $section['codeupdate']['after_delete_directories'];
					$mObj = $this->load->module('codeupdate');
					$ObjBuf = $mObj->codeupdate->$method();
				}

				/** Download zip and extract as per json file value */
				if (isset($section['zip_file']))
				{
					foreach ($section['zip_file'] as $key => $value)
					{
						/** Download zip file chunk by chunk */
						$write = FCPATH . $value;
						file_put_contents($write, fopen($key, 'rb'));

						$tmp_dir = FCPATH . 'tmp/update_' . $version;
						@mkdir($tmp_dir);

						/** Get overwrite blocks value */
						$block = $this->MCores->get_by_name('overwrite_blocks');

						/** Extract zip file chunk by chunk exclude if overwrite set no */
						if ($block['value'] == 'no')
						{
							zipextract_chunked($write, $tmp_dir, 'elements/');
						}
						else
						{
							zipextract_chunked($write, $tmp_dir);
						}

						/** Copy/Replace files and delete file/folder */
						recursive_copy($tmp_dir, FCPATH);
						recursive_delete($tmp_dir);
						@unlink($write);
					}
				}

				/** Run codeupdate after_zip_file controler method */
				if (isset($section['codeupdate']['after_zip_file']))
				{
					$method = $section['codeupdate']['after_zip_file'];
					$mObj = $this->load->module('codeupdate');
					$ObjBuf = $mObj->codeupdate->$method();
				}

				/** Revert back the folder permission to 755 */
				if (isset($section['permission_directories']))
				{
					foreach ($section['permission_directories'] as $key => $value)
					{
						@chmod(FCPATH . $key, 0755);
					}
				}

				if ($return)
				{

					$return = array();

					/** Code for migrating database goes here */
					$option = array(
						'migration_enabled' => TRUE,
						'migration_path' => APPPATH . 'migrations/'
					);
					$this->load->library('migration', $option);

					if ($this->migration->latest() === FALSE)
					{
						$return_json['content'] .= '<br/><div class="alert alert-warning"><strong>' . $this->lang->line('migrate_failure') . '</strong></div>';
					}

					/** Get package name */
					$package = file_get_contents('./package.ini');

					/** Run codeupdate after_migration controler method and pass the appropriate package name and version */
					if (isset($section['codeupdate']['after_migration']))
					{
						$method = $section['codeupdate']['after_migration'];
						$mObj = $this->load->module('codeupdate');
						$ObjBuf = $mObj->codeupdate->$method($version, $package, TRUE);
					}

					/** Update the version in Local Server */
					file_put_contents(FCPATH . 'config.ini', $version);
					$return['header'] = $this->lang->line('autoupdate_update_header');
					$return['content'] = sprintf($this->lang->line('autoupdate_update_success_content'), site_url('sites'));

					/** Set the message pushed from JSON file */
					$content = isset($section['message']['content']) ? $section['message']['content'] : '';
					if ($content != '')
					{
						$return['content'] .= '<br/>' . $content;
					}
				}
				else
				{
					$return['header'] = $this->lang->line('autoupdate_update_header');
					$return['content'] = sprintf($this->lang->line('autoupdate_update_error_content'), site_url('sites'));
				}
			}
			else
			{
				/** Nothing to update */
				$return['header'] = $this->lang->line('autoupdate_update_header');
				$return['content'] = sprintf($this->lang->line('autoupdate_update_no_update_content'), site_url('sites'));
			}
		}
		$this->session->set_userdata($return);
		redirect('autoupdate/confirmation', 'refresh');
		//die(json_encode($return_json));
	}


	public function confirmation()
	{
		$this->data['header'] = $this->session->userdata('header');
		$this->data['content'] = $this->session->userdata('content');
		$this->load->view('confirm', array('data'=>$this->data));
	}

}