<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

require_once(APPPATH . 'helpers/general_helper.php');

$request_url = server_scheme() . "://" . $_SERVER['HTTP_HOST'];

$base = get_domaininfo($this->config->item('base_url'));
$request = get_domaininfo($request_url);
//print_r($request); die();

/**
 * redirect to non-www
 */
if ($request['subdomain'] == 'www' && $request['domain'] == $base['domain'])
{
	header('Location: ' . $this->config->item('base_url'));
    die();
}

/**
 * Add custom routes for Bloxby
 */
require_once('routes_custom.php');

if ($request['subdomain'] == $base['subdomain'] && $request['domain'] == $base['domain'])
{
	if ( ! isset($route['default_controller']))
	{
		$route['default_controller'] = 'home';
	}
	if ( ! isset($route['404_override']))
	{
		$route['404_override'] = '';
	}
	if ( ! isset($route['translate_uri_dashes']))
	{
		$route['translate_uri_dashes'] = FALSE;
	}

	// templ controller's method route
	$route['temple/([0-9]+?)'] = "temple/index/$1";
	$route['loadsinglepage/([0-9]+?)'] = "sites/loadsinglepage/$1";
	$route['loadsingleframe/([0-9]+?)'] = "sites/loadsingleframe/$1";

	// Home controller's method route
	$route['home'] = 'home';
	$route['home/(.+)'] = 'home/index/$1';

	// Auth controller's method route
	$route['signup'] = 'auth/register';
	$route['auth'] = 'auth';
	$route['auth/(.+)'] = 'auth/$1';

	// Site controller's method route
	$route['sites'] = 'sites';
	$route['sites/([0-9]+?)'] = 'sites/site/$1';
	$route['sites/(.+)'] = 'sites/$1';

	// Templates controller's method route
	$route['templates'] = 'templates';
	$route['templates/([0-9]+?)'] = 'templates/template/$1';
	$route['templates/(.+)'] = 'templates/$1';

	// Asset controller's method route
	$route['asset'] = 'asset';
	$route['asset/(.+)'] = 'asset/$1';

	// Package controller's method route
	$route['packages'] = 'package';
	$route['packages/(.+)'] = 'package/$1';

	// User controller's method route
	$route['user'] = 'user';
	$route['user/(.+)'] = 'user/$1';

	// Settings controller's method route
	$route['settings'] = 'settings';
	$route['settings/(.+)'] = 'settings/$1';

	// Integrations controller's method route
	$route['integrations'] = 'integrations';
	$route['integrations/(.+)'] = 'integrations/$1';
    $route['integrations/apilist'] = 'integrations/apilist';
		
	// Autoupdate controller's method route
	$route['autoupdate'] = 'autoupdate';
	$route['autoupdate/(.+)'] = 'autoupdate/$1';

	// Codeupdate controller's method route
	$route['codeupdate'] = 'codeupdate';
	$route['codeupdate/(.+)/(.+)'] = 'codeupdate/index/$1/$2';
	$route['codeupdate/succesfullInstallation'] = 'codeupdate/succesfullInstallation';

	// SentAPI controller's method route
	$route['sent'] = 'sent';
	$route['sent/(.+)'] = 'sent/$1';

	// Subscription controller's method route
	$route['subscription'] = 'subscription';
	$route['subscription/(.+)'] = 'subscription/$1';

	// Migrate controller's method route
	$route['migrate'] = 'migrate';

	// Elements controller's method route
	$route['builder_elements'] = 'builder_elements';
	$route['builder_elements/(.+)'] = 'builder_elements/$1';

	// File_editor controller's method route
	$route['file_editor'] = 'file_editor';
	$route['file_editor/(.+)'] = 'file_editor/$1';

	// Maintenance controller's method route
    $route['maintenance'] = 'maintenance';
    $route['maintenance/(.+)'] = 'maintenance/$1';

    // Emailservices controller's method route
    $route['emailservices'] = 'emailservices';
    $route['emailservices/(.+)'] = 'emailservices/$1';

	
	/*
	| -------------------------------------------------------------------------
	| REST API Routes
	| -------------------------------------------------------------------------
	*/
	$route['api/users'] = 'api/users';
	$route['api/users/(.+)'] = 'api/users/$1';
	$route['api/packages'] = 'api/packages';
	$route['api/packages/(.+)'] = 'api/packages/$1';

	// Declare all the controller so that subfolder URL point to subfolder controller
	// With regular expressions, we can catch multiple segments at once.
	$route['(.+)'] = 'subfolder/index/$1';

	//print_r($route); die();
}
else
{
	/** Check if its sub-domain or custom domain */
	if ($request['domain'] == $base['domain'])
	{
		/** If the request protocol is not same as base_url protocol then redirect the request with base_url protocol [http/https] */
		if ($request['protocol'] != $base['protocol'])
		{
			$subdomain = empty($request['subdomain']) ? '' : $request['subdomain'] . '.';
			header('Location: ' . $base['protocol'] . '://' . $subdomain . $request['domain']);
    		die();
		}

		$route['default_controller'] = 'subdomain';
		$route['sent'] = 'sent';
		$route['sent/(.+)'] = 'sent/$1/' . $request['subdomain'] . "." . $request['domain'];
		$route['(.+)'] = 'subdomain/index/$1';
	}
	else
	{
		$route['default_controller'] = 'customdomain';
		$route['sent'] = 'sent';
		$route['sent/(.+)'] = 'sent/$1/' . $request['domain'];		
		$route['(.+)'] = 'customdomain/index/$1';
	}

}