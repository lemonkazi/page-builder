/* globals siteUrl: false */

/*
    CSS
*/
require('../css/load-main.css');

/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/lazyload.min.js");

/*
    application modules
*/
require('./modules/shared//ui');
require('./modules/shared/account');
require('./modules/elements/componentcategories');
require('./modules/elements/components');


/* this attempts to load custom JS code to include in the elements_components page */
try {
	require('./custom/elements_components.js');
} catch (e) {
	
}