/*
    CSS
*/
require('../css/vendor/bootstrap.min.css');
require('../css/flat-ui-pro.css');
require('../css/sent.css');


/* this attempts to load custom JS code to include in the sent page */
try {
	require('./custom/sent.js');
} catch (e) {
	
}