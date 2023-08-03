/*
	CSS
*/
require('../css/load-main.css');
require('../sass/other.scss');

/*
	scripts (as conventional globals)
*/
require('script-loader!./vendor/jquery.min.js');
require('script-loader!./vendor/jquery-ui.min.js');
require('script-loader!./vendor/flat-ui-pro.min.js');
require("script-loader!./vendor/lazyload.min.js");
require("script-loader!./vendor/notify.min.js");
require("script-loader!./vendor/moment.js");
require("script-loader!./vendor/lodash.js");

/*
	application modules
*/
require('./modules/shared/ui');
require('./modules/shared/account');
require('./modules/sites/sites');
require('./modules/shared/sitesettings');
require('./modules/sites/publishing.js');


/* this attempts to load custom JS code to include in the sites page */
try {
	require('./custom/sites.js');
} catch (e) {
	
}
