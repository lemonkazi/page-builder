/*
    CSS
*/
require('../css/load-main.css');
require('../css/spectrum.css');
require('../sass/cards.scss');

/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/spectrum.js");
require("script-loader!./vendor/bootstrap-confirmation.min.js");

require('./modules/shared/ui');
require('./modules/shared/account');
require('./modules/integration/integration');
require('./modules/shared/sitesettings');
