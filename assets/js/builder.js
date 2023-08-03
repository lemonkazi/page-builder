/*
	CSS
*/

require('../css/load-main.css');
require('../css/load-builder.css');
require('../sass/builder.scss');
require('./vendor/froala_editor_sources_2/css/froala_editor.pkgd.min.css');
require('./vendor/froala_editor_sources_2/css/froala_style.min.css');

/*
	scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/chosen.min.js");
require("script-loader!./vendor/jquery.zoomer.js");
require("script-loader!./vendor/spectrum.js");
require("script-loader!./vendor/jquery.dnd_page_scroll.js");
require("script-loader!./vendor/froala_editor_sources_2/js/froala_editor.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/link.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/font_family.min.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/font_size.min.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/align.min.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/paragraph_format.min.js");
require("script-loader!./vendor/froala_editor_sources_2/js/plugins/colors.min.js");
require("script-loader!./vendor/slim.kickstart.js");
require("script-loader!./vendor/jquery.hoverIntent.min.js");
require("script-loader!./vendor/notify.min.js");
require("script-loader!./vendor/tail.datetime.js");

/*
	application modules
*/
require('./modules/shared/ui.js');
require('./modules/config.js');
require('./modules/shared/utils.js');
require('./modules/builder/canvasElement.js');
require('./modules/builder/styleeditor.js');
require('./modules/shared/imageLibrary.js');
require('./modules/builder/content.js');
require('./modules/shared/sitesettings.js');
require('./modules/sites/publishing.js');
require('./modules/builder/export.js');
require('./modules/builder/preview.js');
require('./modules/builder/revisions.js');
require('./modules/builder/templates.js');
require('./modules/builder/components.js');
require('./modules/builder/grid.js');
require('./modules/builder/popups.js');
require('./modules/shared/polyfills.js');

$(document).ready(function() {
	"use strict";

	$().dndPageScroll();

	$(".tagsinput").tagsinput();

});


/* this attempts to load custom JS code to include in the builder page */
try {
	require('./custom/builder.js');
} catch (e) {
	
}