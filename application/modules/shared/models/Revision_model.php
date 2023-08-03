<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Revision_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

		$this->load->library('simple_html_dom');
    }

	/**
	 * Get revisions for a site
	 *
	 * @param  integer 	$site_id
	 * @param  string 	$page
	 * @return mixed 	$q->result()/FALSE
	 */
	public function getForSite($site_id, $page = 'index', $order='DESC')
	{
		// Retrieve page ID first
		$q = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page)->get();

		if ($q->num_rows() > 0)
		{
			$res = $q->result();
			$page_id = $res[0]->pages_id;
			$q = $this->db->from('frames')->distinct()->select('frames_timestamp')->where('sites_id', $site_id)->where('revision', 1)->where('pages_id', $page_id)->order_by('frames_timestamp', $order)->get();

			if ($q->num_rows() > 0)
			{
				return $q->result();
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Generates a revision for previewing
	 *
	 * @param  integer 	$site_id
	 * @param  integer 	$timestamp
	 * @param  string 	$page
	 * @return mixed 	$skeleton/FALSE
	 */
	public function buildRevision($site_id, $timestamp, $page)
	{
		// Retrieve the page ID first
		$q = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page)->get();

		if ($q->num_rows() > 0)
		{
			$res = $q->result();
			$page_id = $res[0]->pages_id;
			$q = $this->db->from('frames')->where('sites_id', $site_id)->where('frames_timestamp', $timestamp)->where('revision', 1)->where('pages_id', $page_id)->get();

			if ($q->num_rows() > 0)
			{
				$res = $q->result();
				//$skeleton = file_get_html('./elements/skeleton.html');
				$skeleton = str_get_html(file_get_contents('./elements/skeleton.html'));
				// Get the page container
				$ret = $skeleton->find('div[id=page]', 0);
				$page = '';

				foreach ($res as $frame)
				{
					$frameHTML = str_get_html($frame->frames_content);
					$frameContent = $frameHTML->find('div[id=page]', 0);
					$page .= $frameContent->innertext;
				}

				$ret->innertext = $page;
				// Print it!
				return $skeleton;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}

	}

	/**
	 * Deletes a page revision
	 *
	 * @param  	integer 	$site_id
	 * @param  	integer 	$timestamp
	 * @param  	string 		$page
	 * @return 	void
	 */
	public function delete($site_id, $timestamp, $page)
	{
		// Retrieve the page ID first
		$q = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page)->get();

		if ($q->num_rows() > 0)
		{
			$res = $q->result();
			$page_id = $res[0]->pages_id;
			// Delete the revision
			$this->db->where('sites_id', $site_id);
			$this->db->where('frames_timestamp', $timestamp);
			$this->db->where('pages_id', $page_id);
			$this->db->delete('frames');
		}
	}

	/**
	 * Restores a revision
	 *
	 * @param  integer 	$site_id
	 * @param  integer 	$timestamp
	 * @param  string 	$page
	 * @return void
	 */
	public function restore($site_id, $timestamp, $page)
	{
		// Retrieve the page ID first
		$q = $this->db->from('pages')->where('sites_id', $site_id)->where('pages_name', $page)->get();

		if ($q->num_rows() > 0)
		{
			$res = $q->result();
			$page_id = $res[0]->pages_id;
			// Push current frames into a revision
			$data = array(
				'revision' => 1
			);
			$this->db->where('sites_id', $site_id);
			$this->db->where('pages_id', $page_id);
			$this->db->where('revision', 0);
			$this->db->update('frames', $data);

			// Restore revision by recreating the old revision
			// Select first
			$q = $this->db->from('frames')->where('frames_timestamp', $timestamp)->where('sites_id', $site_id)->where('pages_id', $page_id)->get();

			if ($q->num_rows() > 0)
			{
				// Copy frames
				foreach ($q->result() as $frame)
				{
					$data = array(
						'sites_id' 				=> $site_id,
						'pages_id' 				=> $page_id,
						'frames_content' 		=> $frame->frames_content,
						'frames_height' 		=> $frame->frames_height,
						'frames_original_url' 	=> $frame->frames_original_url,
						'frames_loaderfunction' => $frame->frames_loaderfunction,
						'frames_sandbox' 		=> $frame->frames_sandbox,
						'frames_timestamp' 		=> time(),
						'revision' 				=> 0
					);

					$this->db->insert('frames', $data);
				}
			}
		}
	}

}