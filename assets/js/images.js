/*
	CSS
*/
require('../css/load-main.css');
require('../css/slim.min.css');

/*
	scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/chosen.min.js");
require("script-loader!./vendor/slim.kickstart.js");
require("script-loader!./vendor/notify.min.js");

/*
	application modules
*/
require('./modules/shared/ui');
require('./modules/builder/builder');
require('./modules/config');
require('./modules/shared/imageLibrary');
require('./modules/shared/account');
require('jquery-lazyload');

$(function() {
    "use strict";
    
    $("#myImagesTab img.lazy").lazyload({
    	failure_limit : 1000,
    });

    $("#adminImages img.lazy").lazyload({
    	failure_limit : 1000,
    	event: "sporty"
    });
});


/* this attempts to load custom JS code to include in the images page */
try {
    require('./custom/images.js');
} catch (e) {
    
}