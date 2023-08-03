/*
	CSS
*/
require('../css/load-main.css');

/*
	scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");

$("select").select2({dropdownCssClass: 'dropdown-inverse'});


/* this attempts to load custom JS code to include in the register page */
try {
	require('./custom/register.js');
} catch (e) {
	
}