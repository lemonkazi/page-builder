/* globals file: false */
/*
	CSS
*/
require('../css/load-main.css');
require('../sass/file_editor.scss');
require('../sass/popover_confirmation.scss');

/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/bootstrap-confirmation.min.js");
require("script-loader!./vendor/notify.min.js");

let utils = require('./modules/shared/utils');
let appUI = require('./modules/shared/ui').appUI;
let notify = require('./modules/shared/notify');

(function () {
	"use strict";

	let ace = require('brace');
	require('brace/mode/html');
    require('brace/mode/php');
    require('brace/mode/css');
    require('brace/mode/json');
    require('brace/theme/twilight');

    let editor = ace.edit('editor');
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setUseWrapMode(true);
    editor.getSession().setUseWorker(false);

    let filePath = decodeURIComponent(file);
    let temp = filePath.split(".");

    if ( temp[temp.length-1] === 'html' ) {
        editor.getSession().setMode("ace/mode/html");
    } else if ( temp[temp.length-1] === 'php' ) {
        editor.getSession().setMode("ace/mode/php");
    } else if ( temp[temp.length-1] === 'js' ) {
        editor.getSession().setMode("ace/mode/javascript");
    } else if ( temp[temp.length-1] === 'css' ) {
        editor.getSession().setMode("ace/mode/css");
    } else if ( temp[temp.length-1] === 'json') {
        editor.getSession().setMode("ace/mode/json");
    }

    //editor.getSession().setMode(mode);

    $.ajax({
    	url: appUI.baseUrl + 'file_editor/load_file/?file=' + file,
    	type: 'GET'
    }).done(function (contents) {

    	editor.setValue(utils.custom_base64_decode(contents));

    });

    $('[data-toggle=confirmation]').confirmation({
  		rootSelector: '[data-toggle=confirmation]',
	});

	let closeEditor = function () {

		window.close();

	};
	window.closeEditor = closeEditor;

    let updateFile = function () {

        let fileContents = utils.custom_base64_encode(editor.getValue()),
            data = {
                file: file,
                contents: fileContents
            }

        document.getElementById('buttonSaveFile').setAttribute('disabled', true);

        $.ajax({
            url: appUI.baseUrl + 'file_editor/save_file',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function (ret) {

            let className,
                notifyConfig = notify.config;

            document.getElementById('buttonSaveFile').removeAttribute('disabled');

            if ( ret.responseCode === 1 ) notifyConfig.className = "joy";
            else notifyConfig.className = "bummer";

            $.notify(ret.content, notifyConfig);

        });

    };
    window.updateFile = updateFile;

}());

/* this attempts to load custom JS code to include in the elements_blocks page */
try {
	require('./custom/block_editor.js');
} catch (e) {
	
}