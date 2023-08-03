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
require("script-loader!./vendor/jquery.zoomer.js");
require("script-loader!./vendor/notify.min.js");

(function () {
    "use strict";

    require('./modules/shared/ui');
    require('./modules/packages/packages');
    require('./modules/shared/account');

}());


/* this attempts to load custom JS code to include in the packages page */
try {
	require('./custom/packages.js');
} catch (e) {
	
}