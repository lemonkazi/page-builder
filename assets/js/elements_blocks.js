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
require('./modules/elements/blockcategories');
require('./modules/elements/blocks');
require('./modules/shared//ui');
require('./modules/shared/account');


/* this attempts to load custom JS code to include in the elements_blocks page */
try {
	require('./custom/elements_blocks.js');
} catch (e) {
	
}