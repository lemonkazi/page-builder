/*
	CSS
*/
require('../css/load-main.css');
require('../sass/popover_confirmation.scss');

/*
	scripts (as conventional globals)
*/
require('script-loader!./vendor/jquery.min.js');
require('script-loader!./vendor/jquery-ui.min.js');
require('script-loader!./vendor/flat-ui-pro.min.js');
require("script-loader!./vendor/lazyload.min.js");
require("script-loader!./vendor/notify.min.js");
require("script-loader!./vendor/bootstrap-confirmation.min.js");

/*
	application modules
*/
require('./modules/shared/ui');
require('./modules/shared/account');
require('./modules/templates/templates');
require('./modules/templates/categories');

/* this attempts to load custom JS code to include in the sites page */
try {
	require('./custom/templates.js');
} catch (e) {
	
}
