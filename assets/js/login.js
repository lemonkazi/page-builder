/*
	CSS
*/
require('../css/load-main.css');
require('../css/login.css');

/*
	scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");

/*
	application modules
*/
require('./modules/shared/ui.js');

(function(){
	"use strict";

	console.log('Yay');

	if (window.location.hash) {
  	
		let packageID = window.location.hash.substr(1)
		let packageSelect = document.getElementById('package_id');

		packageSelect.value = packageID;
		$(packageSelect).trigger('change');

	}

}());


/* this attempts to load custom JS code to include in the login page */
try {
	require('./custom/login.js');
} catch (e) {
	
}