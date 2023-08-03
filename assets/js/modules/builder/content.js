(function () {
	"use strict";

    var publisher = require('../../vendor/publisher');
	var bConfig = require('../config');
    var utils = require('../shared/utils.js');
    var siteBuilder = require('./builder.js');
    var appUI = require('../shared/ui.js').appUI;

	var contenteditor = {

        customFontNames: {},
        activeEditor: {},
        hasChanges: false,

        init: function() {

            publisher.subscribe('onBlockLoaded', function (block) {
                contenteditor.injectFrameCSS(block);
                contenteditor.fontsInBlock(block);
            });

            publisher.subscribe('onContentClick', function (element) {
                contenteditor.contentClick(element);
            });

            publisher.subscribe('onBeforeSave', function () {

                if ( Object.keys(contenteditor.activeEditor).length !== 0 ) {

                    contenteditor.activeEditor.element.removeAttribute('data-bloxby-editor');
                    contenteditor.activeEditor.element.classList.remove('sb_open');
                    contenteditor.activeEditor.element.classList.remove('sb_hover');

                    $(contenteditor.activeEditor).froalaEditor('destroy');
                }

            });

            publisher.subscribe('siteDataLoaded', function () {
                contenteditor.fontsInApp();
            });

            publisher.subscribe('onSkeletonLoaded', function (skeleton) {
                setTimeout(function () {
                    contenteditor.fontsInSkeleton(skeleton);
                }, 4000);
            });
                        
        },

        /*
            This function injects the font CSS links into the skeleton's <head> section
        */
        fontsInSkeleton: function (skeleton) {

            if (siteBuilder.site.customFonts === null) return false;

            let cssLink = document.createElement('LINK');
            cssLink.href = contenteditor.makeGoogleFontApiString();
            cssLink.rel = "stylesheet";
            cssLink.type = "text/css";

            $(skeleton).contents().find('head').append($(cssLink));

        },

        /*
            This function injects the font CSS links into the block's <head> section
        */
        fontsInBlock: function (block) {

            if (siteBuilder.site.customFonts === null) return false;

            let cssLink = document.createElement('LINK');
            cssLink.href = contenteditor.makeGoogleFontApiString();
            cssLink.rel = "stylesheet";
            cssLink.type = "text/css";

            // Remove existing Google font API links
            let links = block.frameDocument.head.querySelectorAll('link[href*="' + bConfig.google_api_url + '"]');

            links.forEach(function (link) {
                link.remove();
            });

            // Add the new link
            block.frameDocument.head.appendChild(cssLink);

        },

        /*
            This function injects the font CSS links into the application window's <head> section
        */
        fontsInApp: function () {

            if (siteBuilder.site.customFonts === null) return false;

            let cssLink = document.createElement('LINK');
            cssLink.href = contenteditor.makeGoogleFontApiString();
            cssLink.rel = "stylesheet";
            cssLink.type = "text/css";

            $('head').append($(cssLink));

        },

        makeGoogleFontApiString: function () {

            let apiString = "",
                apiHref = bConfig.google_api_url;

            siteBuilder.site.customFonts.forEach(function (font) {

                apiString += font.api_entry;
                apiString += "|";

                contenteditor.customFontNames[font.css_name] = font.nice_name;

            });

            apiString = apiString.substring(0, apiString.length - 1);

            return apiHref + apiString;

        },

        /*
            injects the Medium Editor styling into the iframe's head
        */
        injectFrameCSS: function (block) {

            for( var x = 0; x < bConfig.cssUrls.length; x++ ) {

                if ( block.hasExternalCSS(bConfig.cssUrls[x]) ) continue;

                var cssLink = document.createElement('LINK');
                cssLink.setAttribute('rel', 'stylesheet');
                cssLink.setAttribute('href', bConfig.cssUrls[x]);
                cssLink.setAttribute('type', 'text/css');
                cssLink.setAttribute('media', 'screen');
                cssLink.setAttribute('charset', 'utf-8');
                cssLink.setAttribute('id', 'mediumCss' + x);

                block.frameDocument.head.appendChild(cssLink);

            }

        },


        /*
            Content click handler
        */
        contentClick: function (element) {

            this.activeEditor = element;
            this.hasChanges = false;

            var froalaConfig = bConfig.froalaConfig;
            if (siteBuilder.site.customFonts !== null) {
                //froalaConfig.fontFamily = contenteditor.customFontNames;
                Object.assign(froalaConfig.fontFamily, contenteditor.customFontNames);
            }

            //we'll need to make sure all ancestors are not draggable for Medium editor to function properly in Safari and FF
            $(element.element).parents('*[data-component]').each(function () {
                this.removeAttribute('draggable');
            });

            element.element.setAttribute('data-bloxby-editor', true);
            element.destroyToolBar();

            froalaConfig.enter = $.FroalaEditor.ENTER_BR;

            $(element.element).addClass('sb_open').froalaEditor(froalaConfig).on('froalaEditor.blur', function (e, editor) {

                this.activeEditor = {};

                e.currentTarget.removeAttribute('data-bloxby-editor');
                e.currentTarget.classList.remove('sb_open');
                e.currentTarget.classList.remove('sb_hover');
                editor.destroy();

                //height adjustment
                element.parentBlock.heightAdjustment();

                $(element.element).parents('*[data-component]').each(function () {
                    this.setAttribute('draggable', true);
                });

                if ( contenteditor.hasChanges ) publisher.publish('onBlockChange', element.parentBlock, 'change');

            }).on('froalaEditor.click', function (e, editor, clickEvent) {

                editor.toolbar.hide();

            }.bind(this)).on('froalaEditor.contentChanged', function (e, editor) {

                editor.events.focus();

                //height adjustment
                element.parentBlock.heightAdjustment();
                siteBuilder.site.setPendingChanges(true);

                contenteditor.hasChanges = true;

            }).on('froalaEditor.commands.after', function (e, editor, cmd, param1, param2) {

                if (siteBuilder.site.customFonts !== null) {

                    siteBuilder.site.customFonts.forEach(function (font) {

                        if ( font.css_name === param1 ) {

                            let add = true;

                            // Add to google fonts for this page, only if it's not in the array yet
                            siteBuilder.site.activePage.pageSettings.google_fonts.forEach(function (font2) {

                                if ( font2.css_name === font.css_name ) add = false;

                            });

                            if (add) siteBuilder.site.activePage.pageSettings.google_fonts.push(font);
                        }

                    });

                }

            });

            $(element.element).froalaEditor('events.focus');

        }
        
    };
    
    contenteditor.init();

}());