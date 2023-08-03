<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subdomain extends MY_Controller {

	/**
     * Class constructor
     *
     * Loads required models, loads the hook class and add a hook point
     *
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();
		$model_list = [
			'sites/Sites_model' => 'MSites',
			'sites/Pages_model' => 'MPages',
			'shared/Revision_model' => 'MRevisions',
			'user/Users_model' => 'MUsers',
		];
		$this->load->model($model_list);

		$this->hooks = load_class('Hooks', 'core');
		$this->data = [];

		/** Hook point */
		$this->hooks->call_hook('subdomain_construct');
	}

	/**
	 * Generate the site
	 *
	 * @param  string 	$page
	 * @return void
	 */
	public function index($page = NULL)
	{
		/** Hook point */
		$this->hooks->call_hook('subdomain_index_pre');

		/** force .html */
		if ($page != NULL && substr_compare($page, ".html", strlen($page)-strlen(".html"), strlen(".html")) !== 0 && strpos($page, '.') === false)
		{
			$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			redirect($actual_link . '.html');
		}

		/** if $page resembles anything other then something.html, show 404 */
		if ($page != NULL && preg_match('/^[A-Za-z0-9_-]*\.(html)$/i', $page, $matches, PREG_OFFSET_CAPTURE) === 0)
		{
			show_404();
		}

		$server_scheme = server_scheme();
		$req_host = $_SERVER['HTTP_HOST'];
		$base_url = parse_url(base_url());
		$app_host = $base_url['host'];
		$sub = explode('.', $req_host);

		$site_content = $this->MSites->get_by_field_value('sub_domain', $sub[0]);
		if (count($site_content) > 0 && $site_content[0]['status'] == 'Active')
		{
			/** If there is no page value then its home page */
			if ( ! $page)
			{
				$page = 'index';
			}
			else
			{
				$page_arr = explode(".", $page);
				$page = $page_arr[0];
			}

			$page = $this->MPages->getSinglePage($site_content[0]['sites_id'], $page);
			if ( ! $page)
			{
				show_404();
			}
			$page_content = $this->MPages->load_page($page->pages_id);

			$render_page = $page_content;

			/** Add meta info */

			$meta = '';
			if($site_content[0]['favicon'] != '')
			{
				$favicon = '<link rel="shortcut icon" href="'. $this->config->item('images_uploadDir').'/'. $site_content[0]['users_id'] .'/' . 'favicon/'. $site_content[0]['favicon'] .'" />';
				$meta .= $favicon;
			}
			$meta .= '<title>' . $page->pages_title . '</title>' . "\n";
			$meta .= '<meta name="keywords" content="' . $page->pages_meta_keywords . '">' . "\n";
			$meta .= '<meta name="description" content="' . $page->pages_meta_description . '">' . "\n";
			$render_page = str_replace('<!--pageMeta-->', $meta, $render_page);
			/** Add other header */
			$header = '';
			$header .= $page->pages_header_includes . "\n";

			/** Deal with global CSS and page CSS */
            $custom_css = ".yummy input[type=radio]:hover,.yummy input[type=radio]:active,.yummy input[type=radio]:focus{-webkit-appearance: radio !important;}";
            if ($site_content[0]['global_css'] != '')
			{
				$custom_css .= $site_content[0]['global_css'] . "\n";
			}
			if ($page->pages_css != '')
			{
				$custom_css .= $page->pages_css;
			}

			if ($custom_css !== '')
			{
				$render_page = str_replace("</head>", "\n<style>\n" . $custom_css . "\n</style>\n</head>", $render_page);
			}

			/** Deal with global JS */
			if ($site_content[0]['global_js'] != '')
			{
				$custom_js = trim(strip_tags($site_content[0]['global_js'], '<script>'));
                $pos = strpos($custom_js, '<script');
                if ($pos !== FALSE && $pos == 0)
                {
                    $render_page = str_replace("</head>", "\n" . $custom_js . "\n</head>", $render_page);
                }
                else
                {
                    $render_page = str_replace("</head>", "\n<script>\n" . $custom_js . "\n</script>\n</head>", $render_page);
                }
			}

			/** Google fonts */
			if ($page->google_fonts !== '' && $page->google_fonts !== '[]')
			{
				$googleFonts = json_decode($page->google_fonts);
				$apiString = $this->config->item('google_font_api');

				if (is_array($googleFonts))
				{
					foreach ($googleFonts as $font)
					{
						$apiString .= $font->api_entry;
						$apiString .= '|';
					}
				}

				$apiString = '<link href="' . $apiString . '" rel="stylesheet" type="text/css">';

				$render_page = str_replace("</head>", $apiString . "\n</head>", $render_page);
			}
			$render_page = str_replace('<!--headerIncludes-->', $header, $render_page);

			/** Load html with Simple HTML DOM */
			$this->load->library('Simple_html_dom');
			$raw = str_get_html($render_page, true, true, DEFAULT_TARGET_CHARSET, false);
			if (empty($raw))
			{
				show_404();
			}

			/** Fix the menu link */
			foreach($raw->find('a') as $element)
			{
				if (substr($element->href, 0, 1) !== '#' && ! $element->hasAttribute('data-toggle') && ! strpos($element->href, '//') && strpos($element->href, 'mailto:') === false && strpos($element->href, 'tel:'))
				{
					$element->href = $server_scheme . '://' . $req_host . '/' . $element->href;
				}
			}

			/** Strip out video overlays */
			foreach ($raw->find('.frameCover') as $element)
			{
				$element->outertext = "";
			}

			/** Custom header to deal with XSS protection */
			header("X-XSS-Protection: 0");

			/** Hook point */
			$this->hooks->call_hook('subdomain_index_post');

			echo $raw;
		}
		else
		{
			/** Hook point */
			$this->hooks->call_hook('subdomain_index_error');

			show_404();
		}
	}

	/**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
	public function __destruct()
	{
		/** Hook point */
		$this->hooks->call_hook('subdomain_destruct');
	}

}