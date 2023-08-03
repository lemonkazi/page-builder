<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, check if user has right to access this class, loads the hook class and add a hook point
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();

		$model_list = [
			'asset/Assets_model' => 'MAssets',
			'sites/Sites_model' => 'MSites',
			'user/Users_model' => 'MUsers',
			'package/Packages_model' => 'MPackages',
			'settings/Whitelabel_model' => 'MWhitelabel',
			'settings/Payment_settings_model' => 'MPayments'
		];
		$this->load->model($model_list);

		if ( ! $this->session->has_userdata('user_id'))
		{
			redirect('auth', 'refresh');
		}

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];
		$this->data['whitelabel_general'] = $this->MWhitelabel->load();

		/** Hook point */
		$this->hooks->call_hook('asset_construct');
	}

	/**
	 * Blank index
	 */
	public function index()
	{

	}

	/**
	 * Image library landing page
	 *
	 * @return  void
	 */
	public function images()
	{
		/** Hook point */
		$this->hooks->call_hook('asset_images_pre');

		$this->load->helper('thumb');

		/** Load user images */
		$user_id = $this->session->userdata('user_id');
		$user_images = $this->MAssets->get_images($user_id);
		if ($user_images)
		{
			$this->data['userImages'] = $user_images;
		}
		else
		{
			$this->data['userImages'] = [];
		}

		/** Load admin images */
		$admin_images = $this->MAssets->admin_images();
		if ($admin_images)
		{
			$this->data['adminImages'] = $admin_images;
		}
		else
		{
			$this->data['adminImages'] = [];
		}

		$this->data['userID'] = $user_id;
		$this->data['page'] = "asset";
		$gateway = $this->MPayments->get_by_name('payment_gateway');
        $this->data['packages'] = $this->MPackages->get_all($gateway[0]->value, true);

		/** Hook point */
		$this->hooks->call_hook('asset_images_post');

		$this->load->view('asset/images', $this->data);
	}

	/**
	 * Takes an incoming form with file upload
	 */
	public function imageUpload()
	{
		/** Hook point */
		$this->hooks->call_hook('asset_imageUpload_pre');

		$user_id = $this->session->userdata('user_id');
		// If the upload path does not exist, create it
		if ( ! file_exists('./' . $this->config->item('images_uploadDir') . '/' . $user_id))
		{
			mkdir('./' . $this->config->item('images_uploadDir') . '/' . $user_id, 0777, TRUE);
		}

		$config['upload_path'] = './' . $this->config->item('images_uploadDir') . '/' . $user_id . '/';
		$config['allowed_types'] = $this->config->item('upload_allowed_types');
		$config['max_size']	= $this->config->item('upload_max_size');
		$config['max_width']  = $this->config->item('upload_max_width');
		$config['max_height']  = $this->config->item('upload_max_height');
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('userFile'))
		{
			$this->session->set_flashdata('error', $this->upload->display_errors());
		}
		else
		{
			$this->session->set_flashdata('success', 1);
		}

		/** Hook point */
		$this->hooks->call_hook('asset_imageUpload_post');

		redirect('asset/images', 'refresh');
	}

	/**
	 * Takes an incoming form with image via Ajax
	 *
	 * @param  integer 	$site_id
	 * @return json 	$return
	 */
	public function imageUploadAjax()
	{
		$this->load->library('Slim');
		$this->load->helper('thumb');

		/** Check if its a normal user and user's allowed disk space exceed */
		if ($this->session->userdata('user_type') != 'Admin')
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
			$size_bytes = get_dir_size('./images/uploads/'. $this->session->userdata('user_id'));
			$size_mb = ($size_bytes/1000)/1000;
			if ($size_mb >= $package['disk_space'])
			{
				Slim::outputJSON(array(
					'status' => SlimStatus::FAILURE,
					'message' => $this->lang->line('asset_slim_disk_space_error')
				));

				return;
			}
		}

		// Uncomment if you want to allow posts from other domains
		// header('Access-Control-Allow-Origin: *');

		/** Get posted data, if something is wrong, exit */
		try
		{
			$images = Slim::getImages();
		}
		catch (Exception $e)
		{
		    // Possible solutions
		    // ----------
		    // Make sure you're running PHP version 5.6 or higher
			Slim::outputJSON(array(
				'status' => SlimStatus::FAILURE,
				'message' => $this->lang->line('asset_slim_unkown_error')
			));
			return;
		}

		/** No image found under the supplied input name */
		if ($images === false)
		{
		    // Possible solutions
		    // ----------
		    // Make sure the name of the file input is "slim[]" or you have passed your custom
		    // name to the getImages method above like this -> Slim::getImages("myFieldName")
			Slim::outputJSON(array(
				'status' => SlimStatus::FAILURE,
				'message' => $this->lang->line('asset_slim_no_data_error')
			));
			return;
		}

		/** Should always be one image (when posting async), so we'll use the first on in the array (if available) */
		$image = array_shift($images);
		/** Something was posted but no images were found */
		if ( ! isset($image))
		{
		    // Possible solutions
		    // ----------
		    // Make sure you're running PHP version 5.6 or higher
			Slim::outputJSON(array(
				'status' => SlimStatus::FAILURE,
				'message' => $this->lang->line('asset_slim_no_images_error')
			));
			return;
		}

		/** If image found but no output or input data present */
		if ( ! isset($image['output']['data']) && ! isset($image['input']['data']))
		{
		    // Possible solutions
		    // ----------
		    // If you've set the data-post attribute make sure it contains the "output" value -> data-post="actions,output"
		    // If you want to use the input data and have set the data-post attribute to include "input", replace the 'output' String above with 'input'
			Slim::outputJSON(array(
				'status' => SlimStatus::FAILURE,
				'message' => $this->lang->line('asset_slim_no_imagedata_error')
			));
			return;
		}

		/** if we've received output data save as file */
		if (isset($image['output']['data']))
		{
		    // get the name of the file
			$name = $image['output']['name'];
			$name = str_replace(" ", "-", $name);
		    // get the crop data for the output image
			$data = $image['output']['data'];
		    // If you want to store the file in another directory pass the directory name as the third parameter.
		    // $file = Slim::saveFile($data, $name, 'my-directory/');
		    // If you want to prevent Slim from adding a unique id to the file name add false as the fourth parameter.
		    // $file = Slim::saveFile($data, $name, 'tmp/', false);
			$user_id = $this->session->userdata('user_id');
			if ( ! file_exists('./' . $this->config->item('images_uploadDir') . '/' . $user_id))
			{
				mkdir('./' . $this->config->item('images_uploadDir') . '/' . $user_id, 0777, TRUE);
			}
			if ($image['meta']->fresh == 1)
			{
				$output = Slim::saveFile($data, $name, './'.$this->config->item('images_uploadDir') . '/' . $user_id .'/');
				$thumb = thumb("./" . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $output['name'], 250, 140, TRUE);
				$full = $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $output['name'];
			}
			else
			{
				$output = Slim::saveFile($data, $name, './'.$this->config->item('images_uploadDir') . '/' . $user_id .'/', FALSE);
				$thumb = thumb("./" . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $name, 250, 140, TRUE);
				$full = $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $name;
			}
		}

		/** if we've received input data (do the same as above but for input data) */
		if (isset($image['input']['data']))
		{
		    // get the name of the file
			$name = $image['input']['name'];
		    // get the crop data for the output image
			$data = $image['input']['data'];
		    // If you want to store the file in another directory pass the directory name as the third parameter.
		    // $file = Slim::saveFile($data, $name, 'my-directory/');
		    // If you want to prevent Slim from adding a unique id to the file name add false as the fourth parameter.
		    // $file = Slim::saveFile($data, $name, 'tmp/', false);
			$user_id = $this->session->userdata('user_id');
			if ( ! file_exists('./' . $this->config->item('images_uploadDir') . '/' . $user_id))
			{
				mkdir('./' . $this->config->item('images_uploadDir') . '/' . $user_id, 0777, TRUE);
			}
			$input = Slim::saveFile($data, $name, './'.$this->config->item('images_uploadDir') . '/' . $user_id .'/');
		    //overwrite the thumbnail
			$thumb = thumb( "./" . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $name, 250, 140, true );
			$full = $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $name;
		}

		/** Build response to client */
		$response = array(
			'status' => SlimStatus::SUCCESS
		);
		if (isset($output) && isset($input))
		{
			$response['output'] = array(
				'file' => $output['name'],
				'path' => $output['path']
			);
			$response['input'] = array(
				'file' => $input['name'],
				'path' => $input['path']
			);
		}
		else
		{
			$response['file'] = isset($output) ? $output['name'] : $input['name'];
			$response['path'] = isset($output) ? $output['path'] : $input['path'];
		}

		if ( isset($thumb) ) {
			$response['thumb'] = $thumb;
		}
		if (isset($full))
		{
			$response['full'] = $full;
		}
		if ( isset($size_mb) ) {
			$response['usedspace'] = round($size_mb, 2);
		} else {
			$size_bytes = get_dir_size('./images/uploads/'. $this->session->userdata('user_id'));
			$size_mb = round(($size_bytes/1000)/1000, 2);
			$response['usedspace'] = $size_mb;
		}


		/** Return results as JSON String */
		Slim::outputJSON($response);
	}


	/**
	 * Removes a single user image
	 *
	 * @return void
	 */
	public function delImage()
	{
		/** Hook point */
		$this->hooks->call_hook('asset_delImage_pre');

		/** Delete the image */
		if (isset($_POST['image']) && $_POST['image'] != '')
		{
			$user_id = $this->session->userdata('user_id');
			/** Disect the URL */
			$temp = explode("/", $_POST['image']);
			$fileName = array_pop( $temp );
			$userDirID = array_pop( $temp );
			/** Make sure this is the user's images */
			if ($user_id == $userDirID)
			{
				/** All good, remove! */
				unlink('./' . $this->config->item('images_uploadDir') . '/' . $user_id . '/' . $fileName);
			}
		}

		/** Delete the thumbnail */
		if (isset($_POST['thumb']) && $_POST['thumb'] != '')
		{
			/** Disect the URL */
			$temp = explode("/", $_POST['thumb']);
			$fileName = array_pop( $temp );

			unlink('./tmp/thumbs/' . $fileName);
		}

		/** Hook point */
		$this->hooks->call_hook('asset_delImage_post');
	}

	/**
	 * Resizes the provided image
	 *
	 * @return void
	 */
	public function resizeImage()
	{
		$return = [];

		/** resize the image */
		$file_temp = explode('?', pathinfo($_POST['image'], PATHINFO_BASENAME));
		$filename = $file_temp[0];

		$config['source_image'] = './' . $this->config->item('images_uploadDir') . '/' . $this->session->userdata('user_id') . '/' . $filename;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $_POST['width'];
		$config['height'] = $_POST['height'];

		if ($this->load->library('image_lib', $config))
		{
			$this->image_lib->resize();

			/** new thumbnail */
			$this->load->helper('thumb');
			$thumb = thumb( "./" . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $filename, 250, 140, true );

			$return['responseCode'] = 1;
			$return['responseMessage'] = $this->lang->line('asset_resize_success');
			$return['image'] = base_url() . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $filename;
			$return['thumbnail'] = $thumb;
		}
		else
		{
			$return['responseCode'] = 0;
			$return['responseMessage'] = $this->lang->line('asset_resize_error');
		}

		die(json_encode($return));
	}

	/**
	 * Unsplash search with keyword
	 *
	 * @param  string  	$search
	 * @param  boolean 	$page
	 * @return JSON
	 */
	public function unsplash_search($search, $page = false)
	{
		\Crew\Unsplash\HttpClient::init([
			'applicationId'	=> $this->config->item('unsplash_access_key'),
			'secret'		=> $this->config->item('unsplash_secret_key'),
			'callbackUrl'	=> base_url(),
			'utmSource' 	=> 'GroovePages'
		]);

		if ( ! $page)
		{
			$page = 1;
		}
		$per_page = 30;
		$orientation = 'landscape';

		$photos = Crew\Unsplash\Search::photos($search, $page, $per_page, $orientation);
		$return = ['results' => []];
		for ($i=0; $i < $per_page; $i++)
		{
			$return['results'][] = $photos[$i];
		}
		if ($return['results'][0] === null && $page < 2) $return['message'] = $this->lang->line('modal_imagelibrary_message_noimagesearchresults');
		else if ($return['results'][0] === null) $return['message'] = $this->lang->line('modal_imagelibrary_message_nofurtherimagesearchresults');
		die(json_encode($return));
	}

	/**
	 * Unsplash photo by id
	 *
	 * @param  string 	$id
	 * @return JSON
	 */
	public function unsplash_photo($id)
	{

		/** Check if its a normal user and user's allowed disk space exceed */
		if ($this->session->userdata('user_type') != 'Admin')
		{
			$package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
			$size_bytes = get_dir_size('./images/uploads/'. $this->session->userdata('user_id'));
			$size_mb = ($size_bytes/1000)/1000;
			if ($size_mb >= $package['disk_space'])
			{
				echo json_encode(['error' => $this->lang->line('asset_slim_disk_space_error')]);
				return;
			}
		}

		\Crew\Unsplash\HttpClient::init([
			'applicationId'	=> $this->config->item('unsplash_access_key'),
			'secret'		=> $this->config->item('unsplash_secret_key'),
			'callbackUrl'	=> base_url(),
			'utmSource' 	=> 'GroovePages'
		]);

		$photo = Crew\Unsplash\Photo::find($id);
		$photo->download();

		$url_of_image = $photo->urls['full'];
		$filename = basename($url_of_image);
		$filename = explode('?', $filename);

		$user_id = $this->session->userdata('user_id');
		// If the upload path does not exist, create it
		if ( ! file_exists('./' . $this->config->item('images_uploadDir') . '/' . $user_id))
		{
			mkdir('./' . $this->config->item('images_uploadDir') . '/' . $user_id, 0777, TRUE);
		}

		$save_location = 'images/uploads/' . $user_id . '/' . $filename[0] . '.jpg';
		file_put_contents($save_location, file_get_contents($url_of_image));
		$this->load->helper('thumb');
		$thumb = thumb($save_location, 250, 140, TRUE);
		$return = [
			'src' =>  $save_location . '?' . str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT),
			'thumb' => $thumb,
			'dataService' => site_url('asset/imageUploadAjax/'),
			'maxFileSize' => $this->config->item('upload_max_size')/1000,
			'fileType' => $this->lang->line('asset_slim_filetype_error'),
			'tooSmall' => $this->lang->line('asset_slim_toosmall_error'),
			'unknownResponse' => $this->lang->line('asset_slim_unkown_error'),
			'uploadSuccess' => $this->lang->line('asset_slim_upload_success'),
			'cancelLabel' => $this->lang->line('asset_slim_button_cancel'),
			'confirmLabel' => $this->lang->line('asset_slim_button_confirm'),
			'newLabel' => $this->lang->line('myimages_newimage_label'),

		];
		echo json_encode($return);
	}
		
	/**
	 * Controller desctruct method for custom hook point
	 *
	 * @return void
	 */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('asset_destruct');
	}

}