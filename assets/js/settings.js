/*
    CSS
*/
require('../css/load-main.css');
require('../css/spectrum.css');

/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/spectrum.js");
require("script-loader!./vendor/bootstrap-confirmation.min.js");

const bConfig = require('./modules/config');
const utils = require('./modules/shared/utils');
const ace = require('brace');
require('brace/mode/html');
require('brace/theme/twilight');

(function () {
    "use strict";

    require('./modules/shared/account');

    $('#configHelp').affix({
        offset: {
            top: 200
        }
    });

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
    });
    
    //set the width for the configHelp
    $('.configHelp').width( $('.configHelp').width() );
                
    //help info
    $('form.settingsForm textarea').focus(function(){

        var theHelp = $(this).closest('.row').find('.configHelp');

        $('div:first', theHelp).html( $(this).next().html() );
            
        theHelp.fadeIn(500);
            
        //set the width for the configHelp
        theHelp.width( theHelp.width() );
            
    });
	
	$('form.settingsForm .bootstrap-switch').hover(function(){

        var theHelp = $(this).closest('.row').find('.configHelp');

        $('div:first', theHelp).html( $(this).next().next().html() );
            
        theHelp.fadeIn(500);
            
        //set the width for the configHelp
        theHelp.width( theHelp.width() );
            
    });
        
    $('form.settingsForm textarea:not(#customCSS)').blur(function(){
            
        $('#configHelp').hide();
            
    }).on('keyup',  (e) => autoHeight(e.target)).each( function () {

        autoHeight(this);

    } );

    //hash?
    if(window.location.hash) {
        $('#settingsTabs a[href="'+ window.location.hash + '"]').tab('show');
    }

    $('select#payment_gateway').on('change', function () {

        if ( this.value === 'paypal' ) $('#paypalWarningModal').modal('show');

    });

    function autoHeight(textarea) {
        while($(textarea).outerHeight() < textarea.scrollHeight + parseFloat($(textarea).css("borderTopWidth")) + parseFloat($(textarea).css("borderBottomWidth"))) {
            $(textarea).height($(textarea).height()+1);
        }
    }

    // color pickers
    $('input.spectrum').spectrum({
        preferredFormat: "rgb",
        showPalette: true,
        allowEmpty: true,
        showInput: true,
        showAlpha: true,
        palette: [
            ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
            ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
            ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
            ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
            ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
            ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
            ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
            ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
        ]
    });

    //Ace editor for custom CSS
    let editor = ace.edit( 'customCSS' );
    editor.setTheme("ace/theme/" + bConfig.aceTheme);
    editor.getSession().setUseWrapMode(true);
    editor.getSession().setUseWorker(false);
    editor.getSession().setMode("ace/mode/css");

    editor.setOptions({
        maxLines: Infinity,
        minLines: 25
    });

    //$('#buttonWhiteLabelUpdate').on('click', function () {
    $('form#formWhiteLabel').on('submit', function () {

        let colorInputs = $('form#formWhiteLabel').find('input.spectrum');

        colorInputs.each(function () {
            if (this.value !== '') this.name = utils.custom_base64_encode(this.name);
            else this.name = '';
        });

        console.log(editor.getValue());
        $('#textAreaCustomCSS').val( utils.custom_base64_encode(editor.getValue()) );
        console.log($('#textAreaCustomCSS').val());

        return true;

    });

    $('#inputLogoUpload').on('change', function () {

        $('#inputLogoFile_').val('');

    });

    editor.setValue($('#textAreaCustomCSS').val());

    let resetColors = function () {

        let inputs = $('input.spectrum');

        inputs.each(function () {

            $(this).spectrum("set", "");

        });

        return false;

    };
    window.resetColors = resetColors;


    let resetLogo = function () {

        $('#textLogoText').val('');

        if ( $('#inputLogoFile_').size() > 0 ) $('#inputLogoFile_').val('');

        $('#fileiInputWidget').removeClass('fileinput-exists').addClass('fileinput-new').find('.fileinput-filename').text('');

        return false

    }
    window.resetLogo = resetLogo;


    let resetCss = function () {

        editor.setValue('');

        return false;

    }
    window.resetCss = resetCss;

}());


/* this attempts to load custom JS code to include in the settings page */
try {
    require('./custom/settings.js');
} catch (e) {
    
}