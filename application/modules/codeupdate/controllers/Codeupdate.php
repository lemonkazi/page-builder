<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Codeupdate extends MY_Controller {

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
	}

	/**
	 * Check autoupdate exist or not
	 *
	 * @param 	string 		$version
	 * @param   string 		$package
	 * @param   boolean 	$method
	 * @return 	void
	 */
	public function index($version, $package, $method = FALSE)
	{
		/** The frames table backup and other operation needed only when it updates from 1.0.42 to 1.0.5 */
		if ($version === '1.0.5')
		{
			/*
			The following code patches existing frames in the database:
			0. Copy/backup the entire frames table
			1. Remove blurb URLs from frame <head> sections
			2. Replace the main blocks class name: yummy/parrot + bg-layer + block
			3. Insert the <div class="overly"></div> element
			4. Bundle name updates: Yummy/Parrot_headers.js/css is now Starter/Professional/Enterprise_headers.js/css
			*/

			// 0. Backup the frames table
			/** Ignore php time limit which is generally 30 sec. */
			set_time_limit(0);
			$stamp = time();
			$this->db->query("CREATE TABLE `frames_$stamp` LIKE `frames`; ");
			$this->db->query("INSERT `frames_$stamp` SELECT * FROM `frames`");

			$query = $this->db->get('frames');

			foreach( $query->result() as $row )
			{
				// echo $row->frames_id."<br>";

				// Yummy or Parrot block?
				$template = "";

				if ( strpos(strtolower($row->frames_original_url), "yummy") !== false )
				{
					$template = "yummy";
				}
				else if ( strpos(strtolower($row->frames_original_url), "parrot") !== false )
				{
					$template = "parrot";
				}
				else // can not determin template from original URL, try using the frame content
				{
					if ( strpos($row->frames_content, "/Yummy_") !== false || strpos($row->frames_content, "/yummy_") !== false )
					{
						$template = "yummy";
					}
					else if ( strpos($row->frames_content, "/Parrot_") !== false || strpos($row->frames_content, "/parrot_") !== false )
					{
						$template = "parrot";
					}
				}

				if ( $template !== '' )
				{
					// Remove the blob URLs
					$expression = '/<link rel=\"stylesheet\" href=\"blob:[a-zA-Z0-9\.:\/\-]*\">/i';

					$updateFrameContent = preg_replace($expression, "", $row->frames_content);

					// 1. Replace the main blocks class name: yummy/parrot + bg-layer + block
					$this->load->library('Simple_html_dom');
					$raw = str_get_html($updateFrameContent, true, true, DEFAULT_TARGET_CHARSET, false);

					foreach ($raw->find('div.block') as $block)
					{

	            		//echo $block->getAttribute('class')."<br>";

	            		// don't mess with the empty block
						if ( strpos($block->getAttribute('class'), "empty") !== false ) continue;

	            		// add the template name to the class attribute
						if ( $template === 'yummy' )
						{
							if ( strpos($block->getAttribute('class'), "yummy") === false )
							{
								$block->setAttribute('class', $block->getAttribute('class') . " yummy");
	            				//echo $row->frames_id." - Added Yummy class to .block<br>";
							}
						}
						else
						{
							if ( strpos($block->getAttribute('class'), "parrot") === false )
							{
								$block->setAttribute('class', $block->getAttribute('class') . " parrot");
	            				//echo $row->frames_id." - Added parrot class to .block<br>";
							}
						}

	            		// 2. make sure the bg-layer class is there
						if ( strpos($block->getAttribute('class'), "bg-layer") === false )
						{
							$block->setAttribute('class', $block->getAttribute('class') . " bg-layer");
	            			//echo $row->frames_id." - Added bg-layer class to .block<br>";
						}

	            		//echo $block->getAttribute('class')."<br><br>";

					}

	            	// 4. Replace bundle names
					foreach ( $raw->find('head > link[href*=/bundles/]') as $link)
					{

						$temp = ($template === 'starter')? 'professional': $template;

						if ( strpos($link->getAttribute('href'), ucfirst($template)."_") !== false )
						{
	            			//echo $row->frames_id." - Fixed the CSS bundle path<br>";
						}

						$link->setAttribute('href', str_replace(ucfirst($temp)."_", ucfirst($package)."_", $link->getAttribute('href')));

					}

					foreach ( $raw->find('script[src*=/bundles/]') as $script )
					{

						$temp = ($template === 'starter')? 'professional': $template;

						if ( strpos($script->getAttribute('src'), ucfirst($template)."_") !== false )
						{
	            			//echo $row->frames_id." - Fixed the JS bundle path<br>";
						}

						$script->setAttribute('src', str_replace(ucfirst($temp)."_", ucfirst($package)."_", $script->getAttribute('src')));

					}

					$updateFrameContent = $raw;

	            	// 3. Insert the <div class="overly"></div> element if not present
					$overly = $raw->find('div.overly');

					if ( count($overly) === 0 )
					{
						$pos = strpos($updateFrameContent, '<div class="container">');
						if ($pos !== false)
						{
							$updateFrameContent = substr_replace($updateFrameContent, '<div class="overly"></div><div class="container">', $pos, '<div class="container">');
	            			//echo $row->frames_id." - Added the overly element<br>";
						}
					}

					$data = array(
						'frames_content' => $updateFrameContent
					);

					$this->db->where('frames_id', $row->frames_id);
					$this->db->update('frames', $data);
				}
			}
		}

		/*
		The following code grabs and imports all the blocks and components from the /elements.json file
		*/

		$elements = json_decode(file_get_contents('./elements/elements.json'), true);

		/** Handle the import of the blocks */

		/** Only run if the "blocks_categories" table and "blocks" table exist and are empty */
		if ( ! $this->db->table_exists('blocks_categories') || ! $this->db->table_exists('blocks'))
		{
			die('Either the table "blocks_categories" or the table "blocks" does not exist (or both). Make sure you have ran the migration for v1.0.5.');
		}

		$q1 = $this->db->get('blocks_categories');
		$q2 = $this->db->get('blocks');

		if ($q1->num_rows() != 0 || $q2->num_rows() != 0)
		{
			die('Either the table "blocks_categories" or the table "blocks" is not empty.');
		}

		foreach ($elements['elements'] as $category => $blocks)
		{
			/** Insert the category */
			$data = array(
				'category_name' => $category,
				'list_order' => 999
			);
			$this->db->insert('blocks_categories', $data);
			$blockCatID = $this->db->insert_id();

			/** Insert the blocks */
			foreach ($blocks as $block)
			{
				$thumb = basename($block['thumbnail']);

				$data = array(
					'blocks_category' => $blockCatID,
					'blocks_url' => $block['url'],
					'blocks_height' => $block['height'],
					'blocks_thumb' => 'images/uploads/' . $thumb
				);
				$this->db->insert('blocks', $data);

				/** Copy the thumb */
				copy('./elements/thumbs/' . $thumb, './images/uploads/' . $thumb);

				/** Delete the original thumb */
				unlink('./elements/thumbs/' . $thumb);
			}
		}

		/** Handle the import of the components */

		/** Only run if the "components_categories" table and "components" table exist and are empty */
		if ( ! $this->db->table_exists('components_categories') || ! $this->db->table_exists('components'))
		{
			die('Either the table "blocks_categories" or the table "blocks" does not exist (or both). Make sure you have ran the migration for v1.0.5.');
		}

		$q1 = $this->db->get('components_categories');
		$q2 = $this->db->get('components');

		if ($q1->num_rows() != 0 || $q2->num_rows() != 0)
		{
			die('Either the table "components_categories" or the table "components" is not empty.');
		}

		foreach ($elements['components'] as $category => $components)
		{
			/** Insert the category */
			$data = array(
				'category_name' => $category,
				'list_order' => 999
			);
			$this->db->insert('components_categories', $data);
			$componentCatID = $this->db->insert_id();

			/** Insert the components */
			foreach ($components as $component)
			{
				$thumb = basename($component['thumbnail']);

				$data = array(
					'components_category' => $componentCatID,
					'components_thumb' => 'images/uploads/' . $thumb,
					'components_height' => $component['height'],
					'components_markup' => $component['markup']
				);
				$this->db->insert('components', $data);

				/** Copy the thumbnail from /elements/thumbs/components to /images/uploads */
				copy('./elements/thumbs/components/' . $thumb, './images/uploads/' . $thumb);

				/** Delete the original thumb */
				unlink('./elements/thumbs/components/' . $thumb);
			}
		}

		if ($method)
		{
			return TRUE;
		}
		else
		{
			$this->session->set_flashdata('success', $this->lang->line('codeupdate_index_success'));
			redirect('auth', 'refresh');
		}

	}

	/**
	 * Test function for try from autoupdate controller
	 *
	 * @return boolean
	 */
	public function succesfullInstallation()
	{
		
		$this->session->set_flashdata('success', $this->lang->line('codeupdate_index_success'));
		redirect('auth');
	}
	
	
	/**
	 * Test function for try from autoupdate controller
	 *
	 * @return boolean
	 */
	public function custom()
	{
		return TRUE;
	}

	/**
	 * Check migration table for proper migration number
	 *
	 * @return 	void
	 */
	public function check_migration_table()
	{
		if ( ! $this->db->table_exists('migrations'))
		{
			/** Create migration table */
			$this->dbforge->add_field(array(
				'version' => array(
					'type' => 'BIGINT'
				)
			));
			$this->dbforge->create_table('migrations');
			/** Insert its version field's value 1 */
			$data = array(
				'version' => 1
			);
			$this->db->insert('migrations', $data);
		}
		else
		{
			/** Update its version field's value 1 */
			$data = array(
				'version' => 1
			);
			$this->db->update('migrations', $data);
		}
	}

}