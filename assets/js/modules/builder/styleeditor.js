/* globals shortid: false */
(function (){
	"use strict";

	let canvasElement = require('./canvasElement.js').Element;
	let bConfig = require('../config.js');
	let siteBuilder = require('./builder.js');
    let publisher = require('../../vendor/publisher');
    let utils = require('../shared/utils.js');
    let moment = require('../../../../node_modules/moment/moment.js');

    var styleeditor = {
        buttonSaveChanges: document.getElementById('saveStyling'),
        activeElement: {}, //holds the element currenty being edited
        allStyleItemsOnCanvas: [],
        _oldIcon: [],
        styleEditor: document.getElementById('styleEditor'),
        formStyle: document.getElementById('stylingForm'),
        buttonRemoveElement: document.getElementById('deleteElementConfirm'),
        buttonResetElement: document.getElementById('resetStyleButton'),
        selectLinksInernal: document.getElementById('internalLinksDropdown'),
        selectLinksPages: document.getElementById('pageLinksDropdown'),
        videoInputYoutube: document.getElementById('youtubeID'),
        videoInputVimeo: document.getElementById('vimeoID'),
        inputCustomLink: document.getElementById('internalLinksCustom'),
        linkImage: null,
        linkIcon: null,
        inputLinkText: document.getElementById('linkText'),
        selectIcons: document.getElementById('icons'),
        buttonDetailsAppliedHide: document.getElementById('detailsAppliedMessageHide'),
        buttonCloseStyleEditor: document.querySelector('#styleEditor button.close'),
		buttonMoveCanvas: document.querySelector('#styleEditor button.move'),
        ulPageList: document.getElementById('pageList'),
        responsiveToggle: document.getElementById('responsiveToggle'),
		blocksToggle: document.getElementById('blocks_button'),
		componentsToggle: document.getElementById('components_button'),
		pagesToggle: document.getElementById('pages_button'),
		popupToggle: document.getElementById('popup_button'),
        theScreen: document.getElementById('screen'),
        inputLinkActive: document.getElementById('checkboxLinkActive'),
        checkboxOpeninNewTab: document.getElementById('checkboxOpeninNewTab'),

        checkboxEmailForm: document.getElementById('checkboxEmailForm'),
        checkboxDaily: document.getElementById('checkboxDaily'),
        inputEmailFormTo: document.getElementById('inputEmailFormTo'),
        textareaCustomMessage: document.getElementById('textareaCustomMessage'),
        checkboxCustomAction: document.getElementById('checkboxCustomAction'),
        inputCustomAction: document.getElementById('inputCustomAction'),
        checkboxApiIntegration: document.getElementById('checkboxApiIntegration'),
        selectConfiguredApi: document.getElementById('selectConfiguredApi'),
        selectApiList: document.getElementById('selectApiList'),

        checkboxApiIntegrationWithHtmlCode: document.getElementById('checkboxApiIntegrationWithHtmlCode'),
        textareaHtmlCode: document.getElementById('textareaHtmlCode'),

        inputCombinedGallery: document.getElementById('inputCombinedGallery'),
        inputImageTitle: document.getElementById('inputImageTitle'),
        inputImageAlt: document.getElementById('inputImageAlt'),

        checkboxSliderAutoplay: document.getElementById('checkboxSliderAutoplay'),
        checkboxSliderPause: document.getElementById('checkboxSliderPause'),
        selectSliderAnimation: document.getElementById('selectSliderAnimation'),
        inputSlideInterval: document.getElementById('inputSlideInterval'),
        selectSliderNavArrows: document.getElementById('selectSliderNavArrows'),
        selectSliderNavIndicators: document.getElementById('selectSliderNavIndicators'),

        inputZoomLevel: document.getElementById('inputZoomLevel'),
        textareaAddress: document.getElementById('textareaAddress'),
        textareaInfoMessage: document.getElementById('textareaInfoMessage'),
        checkBoxMapBW: document.getElementById('checkBoxMapBW'),

        checkboxCountdownType: document.getElementById('checkboxCountdownType'),
        countdownPicker: document.getElementById('countdownPicker'),
        checkboxDeadlineUrl: document.getElementById('checkboxDeadlineUrl'),
        //checkboxDailyReset: document.getElementById('checkboxDailyReset'),
        radioCountdownReset: document.querySelector('input[name="radioCountdownReset"]'),
        inputDeadlineRedirect: document.getElementById('inputDeadlineRedirect'),
        selectGmtConversion: document.getElementById('selectGmtConversion'),
        countdownSectionDatetime: document.getElementById('countdownSectionDatetime'),
        checkboxDailySection: document.getElementById('checkboxDailySection'),
        countdownSectionLanding: document.getElementById('countdownSectionLanding'),

        inputCountdownDays: document.getElementById('inputCountdownDays'),
        inputCountdownHours: document.getElementById('inputCountdownHours'),
        inputCountdownMinutes: document.getElementById('inputCountdownMinutes'),
        inputId: null,

        getApiList: function(apiName, apiList = '') {

            styleeditor.selectApiList.setAttribute('disabled', true);

            var newOption;
            var selectedAPI = apiName;
            var data = {
                api: selectedAPI
            };

            $.ajax({
                url: '/integrations/apilist',
                type: 'post',
                data: data,
                dataType: 'json'
            })
                .done(function(res) {

                    if(res.length) {

                        styleeditor.selectApiList.innerHTML = '';
                        newOption = document.createElement('option');
                        newOption.innerText = 'Select List';
                        newOption.setAttribute('value', '');
                        styleeditor.selectApiList.appendChild(newOption);

                        for ( var i = 0; i < res.length; i++ ) {
                            newOption = document.createElement('option');
                            newOption.innerText = res[i]['name'];
                            newOption.setAttribute('value', res[i]['list_id']);
                            styleeditor.selectApiList.appendChild(newOption);
                        }

                        styleeditor.selectApiList.value = apiList;

                        styleeditor.selectApiList.removeAttribute('disabled');

                    } else {
                        styleeditor.selectApiList.innerHTML = '';
                        newOption = document.createElement('option');
                        newOption.innerText = 'Select List';
                        newOption.setAttribute('value', '');
                        styleeditor.selectApiList.appendChild(newOption);
                    }

                })
                .fail(function() {

                    styleeditor.selectApiList.innerHTML = '';
                    var newOption = document.createElement('option');
                    newOption.innerText = 'Select List';
                    newOption.setAttribute('value', '');
                    styleeditor.selectApiList.appendChild(newOption);

                });

        },

        init: function() {

            publisher.subscribe('deleteElement', function () {
                styleeditor.delElementCommand();
            });
			
			publisher.subscribe('cloneElement', function () {
                styleeditor.cloneElementCommand();
            });
			
            publisher.subscribe('closeStyleEditor', function () {
                styleeditor.closeStyleEditor();
            });

            publisher.subscribe('onBlockLoaded', function (block) {
                styleeditor.setupCanvasElements(block);
            });

            publisher.subscribe('onComponentDrop', function (block) {
                styleeditor.setupCanvasElements(block);
            });

            publisher.subscribe('onSetMode', function (mode) {
                styleeditor.responsiveModeChange(mode);
            });

            publisher.subscribe('deSelectAllCanvasElements', function () {
                styleeditor.deSelectAllCanvasElements();
            });

            //events
            $(this.buttonSaveChanges).on('click', this.updateStyling);
            $(this.formStyle).on('focus', 'input', this.animateStyleInputIn).on('blur', 'input:not([name="background-image"])', this.animateStyleInputOut);
            $(this.buttonResetElement).on('click', this.resetElement);
            $(this.videoInputYoutube).on('focus', function(){ $(styleeditor.videoInputVimeo).val(''); });
            $(this.videoInputVimeo).on('focus', function(){ $(styleeditor.videoInputYoutube).val(''); });
            $(this.inputCustomLink).on('focus', this.resetSelectAllLinks);
            $(this.buttonDetailsAppliedHide).on('click', function(){$(this).parent().fadeOut(500);});
            $(this.buttonCloseStyleEditor).on('click', this.closeStyleEditor);
			$(this.buttonMoveCanvas).on('click', this.moveCanvas);
            $(this.inputCustomLink).on('focus', this.inputCustomLinkFocus).on('blur', this.inputCustomLinkBlur);
            $(document).on('modeContent modeBlocks', 'body', this.deActivateMode);

            $(this.checkboxCountdownType).on('change', () => {

                if ( !event.currentTarget.checked ) {
                    this.countdownSectionDatetime.style.display = 'block';
                    this.countdownSectionLanding.style.display = 'none';
                    this.checkboxDailySection.style.display = 'block'
                } else {
                    this.countdownSectionDatetime.style.display = 'none';
                    this.countdownSectionLanding.style.display = 'block';
                    this.checkboxDailySection.style.display = 'none';
                }

            });

            //chosen font-awesome dropdown
            $(this.selectIcons).chosen({'search_contains': true});

            //check if formData is supported
            if (!window.FormData){
                this.hideFileUploads();
            }

            //listen for the beforeSave event
            $('body').on('beforeSave', this.closeStyleEditor);

            //responsive toggle
            $(this.responsiveToggle).on('click', 'a', this.toggleResponsiveClick);

			
			//components menu toggle
			$(this.componentsToggle).on('click', null, this.toggleComponentsClick);
			
			//pages menu toggle
			$(this.pagesToggle).on('click', null, this.toggleLeftMenuClick);			
			
			//blocks menu toggle
			$(this.blocksToggle).on('click', null, this.toggleLeftMenuClick);
			
			//popup menu toggle
			$(this.popupToggle).on('click', null, this.togglePopupMenuClick);
            //set the default responsive mode
            siteBuilder.builderUI.currentResponsiveMode = Object.keys(bConfig.responsiveModes)[0];

            this.setupFormTab();
            this.setupCountdownTab();

        },

        /*
            Configures the checkboxes in the FORM tab
        */
        setupFormTab: function () {

            if ( this.checkboxEmailForm === null ) return false;

            this.checkboxEmailForm.addEventListener('change', function () {
                if ( this.checked ) {

                    //use sent API
                    styleeditor.inputEmailFormTo.removeAttribute('disabled');
                    styleeditor.textareaCustomMessage.removeAttribute('disabled');

                    //make sure custom action is disabled
                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                    //make sure API integration option is disabled
                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);

                    //make sure API integration with html code option is disabled
                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);
                } else {

                    styleeditor.inputEmailFormTo.setAttribute('disabled', true);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);

                }
            });

            this.checkboxCustomAction.addEventListener('change', function () {
                if ( this.checked ) {

                    //use custom action
                    styleeditor.inputCustomAction.removeAttribute('disabled');

                    //make sure sent API is disabled
                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', false);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);

                    //make sure API integration option is disabled
                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);
                } else {

                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                }
            });

            this.checkboxApiIntegration.addEventListener('change', function () {
                if ( this.checked ) {

                    //use api integration option
                    styleeditor.selectConfiguredApi.removeAttribute('disabled');

                    if(styleeditor.selectConfiguredApi.value) {
                        styleeditor.selectApiList.removeAttribute('disabled');
                    }

                    //make sure sent API is disabled
                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', false);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);

                    //make sure custom action is disabled
                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);

                } else {

                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);

                }
            });

            this.checkboxApiIntegrationWithHtmlCode.addEventListener('change', function () {
                if ( this.checked ) {

                    //use api integration option
                    styleeditor.textareaHtmlCode.removeAttribute('disabled');

                    //make sure sent API is disabled
                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', false);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);

                    //make sure custom action is disabled
                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);
                } else {
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);
                }
            });

            this.selectConfiguredApi.addEventListener('change', function () {

                var apiName = styleeditor.selectConfiguredApi.value;

                styleeditor.getApiList(apiName);

            });

        },

        /*
            Configures the checkboxes in the COUNTDOWN tab
        */
        setupCountdownTab: function () {

            if ( this.checkboxDeadlineUrl === null ) return false;

            this.checkboxDeadlineUrl.addEventListener('change', function () {

                if ( this.checked ) styleeditor.inputDeadlineRedirect.removeAttribute('disabled');
                else styleeditor.inputDeadlineRedirect.setAttribute('disabled', true);

            });

        },

        /*
            Deselects all canvas elements
        */
        deSelectAllCanvasElements: function () {

            for ( var i in this.allStyleItemsOnCanvas ) {
                if ( this.allStyleItemsOnCanvas.hasOwnProperty(i) ) {

                    this.allStyleItemsOnCanvas[i].removeOutline();

                }
            }

        },

        /*
            Event handler for responsive mode links
        */
        toggleResponsiveClick: function (e) {

            e.preventDefault();

            styleeditor.responsiveModeChange(this.getAttribute('data-responsive'));

        },
		
		/*
        Event handler for Click on left menu (popup, components, blocks, pages)
        */
        toggleLeftMenuClick: function () {
			if ($(".canvasWrapper").css("margin-left") == "236px")
				$(".canvasWrapper").css("margin-left","0");
        },
		
		toggleComponentsClick: function () {
			setTimeout( function(){			
				if(!$("#components_button").hasClass("active"))
				{
					if ($(".canvasWrapper").css("margin-left") == "236px")
						$(".canvasWrapper").css("margin-left","0");
					console.log("yes");
				}
			}				
			, 100);

        },
		
		togglePopupMenuClick: function () {
			//if(!$("#popups").hasClass("visible"))
			{
				if ($(".canvasWrapper").css("margin-left") == "236px")
					$(".canvasWrapper").css("margin-left","0");
				else
					$(".canvasWrapper").css("margin-left","236px");
			}

        },
				
        /*
            Toggles the responsive mode
        */
        responsiveModeChange: function (mode) {

            if ( styleeditor.responsiveToggle === null ) return false;

            var links,
                i;

            //UI stuff
            links = styleeditor.responsiveToggle.querySelectorAll('li');

            for ( i = 0; i < links.length; i++ ) links[i].classList.remove('active');

            document.querySelector('a[data-responsive="' + mode + '"]').parentNode.classList.add('active');


            for ( var key in bConfig.responsiveModes ) {

                if ( bConfig.responsiveModes.hasOwnProperty(key) ) this.theScreen.classList.remove(key);

            }

            if ( bConfig.responsiveModes[mode] ) {

                this.theScreen.classList.add(mode);
                this.theScreen.style.maxWidth = bConfig.responsiveModes[mode];

                if ( typeof siteBuilder.site.activePage.heightAdjustment === 'function' ) siteBuilder.site.activePage.heightAdjustment();

                publisher.publish('onResponsiveViewChange', mode);

            }

            siteBuilder.builderUI.currentResponsiveMode = mode;

        },


        /*
            Activates style editor mode
        */
        setupCanvasElements: function(block) {

            //needed to move from 1.0.1 to 1.0.2, can be removed after 1.0.4
            $(block.frame).contents().find('*[data-selector]').each(function () {
                this.removeAttribute('data-selector');
            });

            if ( block === undefined ) return false;

            var i;

            //create an object for every editable element on the canvas and setup it's events

            for( var key in bConfig.editableItems ) {

                $(block.frame).contents().find( bConfig.pageContainer + ' ' + key ).each(function () {

                    if ( !this.hasAttribute('data-selector') ) styleeditor.setupCanvasElementsOnElement(this, key);

                });

            }

        },


        /*
            Sets up canvas elements on element
        */
        setupCanvasElementsOnElement: function (element, key) {

            //Element object extention
            canvasElement.prototype.clickHandler = function(el, editEmbed) {
                styleeditor.styleClick(this, editEmbed);
            };

            var newElement = new canvasElement(element);

            newElement.editableAttributes = bConfig.editableItems[key];
            newElement.setParentBlock();
            newElement.activate();
            newElement.unsetNoIntent();

            for ( var i in styleeditor.allStyleItemsOnCanvas ) {

                if ( styleeditor.allStyleItemsOnCanvas[i].element === newElement.element ) {

                    styleeditor.allStyleItemsOnCanvas.splice(i, 1);

                }

            }

            styleeditor.allStyleItemsOnCanvas.push( newElement );

            if ( typeof key !== undefined ) $(element).attr('data-selector', key);

        },


        styleDblClick: function (element) {

            this.closeStyleEditor();

            //content editor?
            if ( element.element.parentNode.hasAttribute('data-content') ) {
                publisher.publish('onClickContent', element.element);
            }

        },


        /*
            Event handler for when the style editor is envoked on an item
        */
        styleClick: function(element, editEmbed) {
            //if we have an active element, make it unactive
            if (Object.keys(this.activeElement).length !== 0) {
                this.activeElement.activate();
            }

            //set the active element
            this.activeElement = element;

            if (element.element.getAttribute('data-component') === 'embed') {
                //if (editEmbed) $('#removeElementButton').click();
                /*else */if(editEmbed) element.parentBlock.source(element.element);
                return;
            }

            //unbind hover and click events and make this item active
            this.activeElement.setOpen();

            var theSelector = $(this.activeElement.element).attr('data-selector');

            $('#editingElement').text( theSelector );

            //activate first tab
            $('#detailTabs a:first').click();

            //hide all by default
            $('ul#detailTabs li:gt(0)').hide();

            //what are we dealing with?
            if( $(this.activeElement.element).prop('tagName') === 'A' ) {

                this.editLink(this.activeElement.element);

            }

			if( $(this.activeElement.element).prop('tagName') === 'IMG' ){

                this.editImage(this.activeElement.element);

            }

			if( $(this.activeElement.element).attr('data-type') === 'video' ) {

                this.editVideo(this.activeElement.element);

            }

			if( $(this.activeElement.element).hasClass('fa') ) {

                this.editIcon(this.activeElement.element);

            }

            if( this.activeElement.element.tagName === 'FORM' ) {

                this.editForm(this.activeElement.element);

            }
            if( this.activeElement.element.tagName === 'INPUT' ) {

                this.editInput(this.activeElement.element);

            }
            if( this.activeElement.element.tagName === 'TEXTAREA' ) {

                this.editTextarea(this.activeElement.element);

            }
            if( this.activeElement.element.classList.contains('countdown') ) {

                this.editCountdown(this.activeElement.element);

            }

            if ( this.activeElement.element.parentNode.parentNode.parentNode.hasAttribute('data-carousel-item') ) {

                this.editSlideshow($(this.activeElement.element).closest('.carousel')[0]);

            }

            if ( this.activeElement.element.classList.contains('mapOverlay') ) {

                this.editMap( $(this.activeElement.element).prev()[0] );

            }

            /*if( this.activeElement.element.tagName === 'NAV' ) {

                this.editNavbar(this.activeElement.element);

            }*/

            //load the attributes
            this.buildeStyleElements(theSelector);

            //open side panel
            this.toggleSidePanel('open');

            publisher.publish('onStyleEditorOpen');

            return false;

        },


        /*
            dynamically generates the form fields for editing an elements style attributes
        */
        buildeStyleElements: function(theSelector) {

            //delete the old ones first
            $('#styleElements > *:not(#styleElTemplate)').each(function(){

                $(this).remove();

            });

            var takeFrom = styleeditor.activeElement.element;

            if ( styleeditor.activeElement.element.classList.contains('mapOverlay') ) {
                takeFrom = $(styleeditor.activeElement.element).prev()[0];
            }

            for( var x=0; x<bConfig.editableItems[theSelector].length; x++ ) {

                //create style elements
                var newStyleEl = $('#styleElTemplate').clone(),
                    newDropDown,
                    z,
                    newOption,
                    labelText = '';
                newStyleEl.attr('id', 'el_' + x);
                newStyleEl.find('input').uniqueId();

                if ( typeof window.language.styles[bConfig.editableItems[theSelector][x]] !== 'undefined' ) {
                    labelText = window.language.styles[bConfig.editableItems[theSelector][x]] + ":";
                } else {
                    labelText = bConfig.editableItems[theSelector][x] + ":";
                }

                newStyleEl.find('.control-label').text( labelText );

                if( theSelector + " : " + bConfig.editableItems[theSelector][x] in bConfig.editableItemOptions) {//we've got a dropdown instead of open text input

                    newStyleEl.find('input').remove();

                    newDropDown = $('<select class="form-control select select-default btn-block select-sm"></select>');
                    newDropDown.attr('name', bConfig.editableItems[theSelector][x]);


                    for( z = 0; z < bConfig.editableItemOptions[ theSelector + " : " + bConfig.editableItems[theSelector][x] ].length; z++ ) {

                        newOption = $('<option value="' + bConfig.editableItemOptions[theSelector + " : " + bConfig.editableItems[theSelector][x]][z] + '">' + bConfig.editableItemOptions[theSelector + " : " + bConfig.editableItems[theSelector][x]][z] + '</option>');

                        // for parallax
                        if ( bConfig.editableItems[theSelector][x] === 'parallax' && takeFrom.hasAttribute('data-parallax') && takeFrom.getAttribute('data-parallax') === 'scroll' && bConfig.editableItemOptions[theSelector + " : " + bConfig.editableItems[theSelector][x]][z] === 'on') {
                            newOption.attr('selected', true)
                        }


                        if( bConfig.editableItemOptions[theSelector + " : " + bConfig.editableItems[theSelector][x]][z] === $(takeFrom).css( bConfig.editableItems[theSelector][x] ) ) {
                            //current value, marked as selected
                            newOption.attr('selected', 'true');

                        }

                        newDropDown.append( newOption );

                    }

                    newStyleEl.append( newDropDown );
                    newDropDown.select2({
                        minimumResultsForSearch: -1
                    });

                    if ( bConfig.editableItems[theSelector][x] === 'parallax' ) {

                        let parallaxInfo = document.importNode(document.getElementById('templateParallaxInfo').content, true);

                        newStyleEl.append( parallaxInfo.querySelector('.alert') );

                    }

                } else if ( bConfig.editableItems[theSelector][x] in bConfig.customStyleDropdowns ) {

                    var somethingSelected = 0,
                        labelText2 = '';

                    if ( typeof window.language.styles[bConfig.editableItems[theSelector][x]] !== 'undefined' ) {
                        labelText2 = window.language.styles[bConfig.editableItems[theSelector][x]] + ":";
                    } else {
                        labelText2 = bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].label + ":";
                    }

                    //this option uses a custom label
                    newStyleEl.find('.control-label').text( labelText2 );

                    newStyleEl.find('input').remove();

                    newDropDown = $('<select class="form-control select select-default btn-block select-sm" data-class-dropdown="' + bConfig.editableItems[theSelector][x] + '"></select>');
                    newDropDown.attr('name', bConfig.editableItems[theSelector][x]);

                    for ( var opt in bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].values ) {

                        if ( bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].values.hasOwnProperty(opt) ) {

                            newOption = $('<option value="' + bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].values[opt] + '">' + opt + '</option>');

                            newDropDown.append( newOption );

                            //detect currently applied class
                            for( var clss in takeFrom.classList ) {
                                if ( takeFrom.classList.hasOwnProperty(clss) ) {

                                    if ( takeFrom.classList[clss] === bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].values[opt] ) {

                                        somethingSelected = 1;
                                        newOption.attr('selected', 'true');

                                    }

                                }
                            }

                        }

                    }

                    //if nothing selected, use the default
                    if ( somethingSelected === 0) {
                        newDropDown.val( bConfig.customStyleDropdowns[bConfig.editableItems[theSelector][x]].default );
                    }

                    newStyleEl.append( newDropDown );
                    newDropDown.select2({
                        minimumResultsForSearch: -1
                    });


                } else if ( utils.contains.call(bConfig.inputAppend, bConfig.editableItems[theSelector][x]) ) {

                    newStyleEl.find('input').val( $(takeFrom).css( bConfig.editableItems[theSelector][x] ).replace('px', '') ).attr('name', bConfig.editableItems[theSelector][x]);

                    newStyleEl.find('input').addClass('padding-right');

                    newStyleEl.append($('<span class="inputAppend">px</span>'));

                } else {

                    newStyleEl.find('input').val( $(takeFrom).css( bConfig.editableItems[theSelector][x] ) ).attr('name', bConfig.editableItems[theSelector][x]);

                    if( bConfig.editableItems[theSelector][x] === 'background-image' ) {

                        newStyleEl.find('input').addClass('padding-right').val( $(takeFrom).css( bConfig.editableItems[theSelector][x] ).replace(/['"]+/g, '') );

                        newStyleEl.append($('<a href="#" class="linkLib"><span class="fui-image"></span></a>'));

                        newStyleEl.find('a.linkLib').bind('click', function(e){

                            e.preventDefault();

                            var theInput = $(this).prev();

                            $('#imageModal').modal('show');

                        });

                    } else if( bConfig.editableItems[theSelector][x].indexOf("color") > -1 ) {

                        if ( bConfig.editableItems[theSelector][x] === 'background-color-overlay' ) {

                            newStyleEl.find('input').val( $(takeFrom).find('.overly').css( 'background-color' ) );

                        } else {

                            if( $(takeFrom).css( bConfig.editableItems[theSelector][x] ) !== 'transparent' && $(takeFrom).css( bConfig.editableItems[theSelector][x] ) !== 'none' && $(takeFrom).css( bConfig.editableItems[theSelector][x] ) !== '' ) {

                                newStyleEl.val( $(takeFrom).css( bConfig.editableItems[theSelector][x] ) );

                            }

                        }

                        newStyleEl.find('input').spectrum({
                            cancelText: window.language.front_end_spectrum_cancel,
                            chooseText: window.language.front_end_spectrum_choose,
                            preferredFormat: "hex",
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

                    }

                }

                newStyleEl.css('display', 'block');

                $('#styleElements').append( newStyleEl );

                $('#styleEditor form#stylingForm').height('auto');

            }

        },


        /*
            Applies updated styling to the canvas
        */
        updateStyling: function() {

            var elementID,
                length,
                applyTo;

            $('#styleEditor #tab1 .form-group:not(#styleElTemplate) input, #styleEditor #tab1 .form-group:not(#styleElTemplate) select').each(function(){

                applyTo = styleeditor.activeElement.element;

                if ( styleeditor.activeElement.element.classList.contains('mapOverlay') ) {
                    applyTo = $(styleeditor.activeElement.element).prev()[0];
                }

				if( $(this).attr('name') !== undefined ) {

                    //custom class dropdown?
                    if ( this.hasAttribute('data-class-dropdown') ) {

                        var dropdownItem = bConfig.customStyleDropdowns[this.getAttribute('data-class-dropdown')];

                        //remove the currently applied class
                        for ( var option in dropdownItem.values ) {
                            if ( dropdownItem.values.hasOwnProperty(option) ) {

                                if ( dropdownItem.values[option] !== '' && styleeditor.activeElement.element.classList.contains(dropdownItem.values[option]) ) {
                                    applyTo.classList.remove(dropdownItem.values[option]);
                                }

                            }
                        }


                        //apply class
                        if ( this.value !== '' ) applyTo.classList.add(this.value);

                    } else {

                        if ( $(this).attr('name').indexOf("color") > -1 ) {//color picker

                            if ( $(this).attr('name') !== 'background-color-overlay' ) { // anything but background color overlay

                                if ( $(this).spectrum('get') !== null ) {
                                    $(applyTo).css( $(this).attr('name'),  $(this).spectrum('get').toRgbString());
                                } else {
                                    $(applyTo).css( $(this).attr('name'),  'transparent');
                                }

                            } else { // background color overlay

                                // if the .overly element does not exist, we'll need to add it
                                if ( applyTo.querySelector('.overly') === null ) {
                                    let divOverly = document.createElement('DIV');
                                    divOverly.classList.add('overly');
                                    applyTo.insertBefore(divOverly, applyTo.firstChild);
                                }

                                if ( $(this).spectrum('get') !== null ) {
                                    applyTo.querySelector('.overly').style.backgroundColor = $(this).spectrum('get').toRgbString();
                                } else {
                                    applyTo.querySelector('.overly').style.backgroundColor = 'transparent';
                                }

                            }

                        } else if ( utils.contains.call(bConfig.inputAppend, $(this).attr('name')) ) {

                            $(applyTo).css( $(this).attr('name'),  $(this).val()+"px");

                        } else if ( $(this).attr('name') === 'parallax' ) {

                            if ( this.value === 'on' ) {

                                // setup the parallax, only if a background image is selected
                                if ( applyTo.style.backgroundImage && applyTo.style.backgroundImage !== '' && applyTo.style.backgroundImage !== 'none' ) {
                                    applyTo.classList.add('parallax-window');
                                    applyTo.setAttribute('data-parallax', 'scroll');
                                    applyTo.setAttribute('data-image-src', applyTo.style.backgroundImage.match(/url\(\"(.+)\"\)/)[1]);
                                }

                            } else {

                                // disable the parallax
                                applyTo.classList.remove('parallax-window');
                                applyTo.removeAttribute('data-parallax');
                                applyTo.removeAttribute('data-image-src');

                            }


                        } else {

                            $(applyTo).css( $(this).attr('name'),  $(this).val());

                        }

                    }

				}

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).css( $(this).attr('name'),  $(this).val() );

                }

                /* END SANDBOX */

            });

            //links
            if( $(styleeditor.activeElement.element).prop('tagName') === 'A' ) {

                //change the href prop?
                styleeditor.activeElement.element.href = document.getElementById('internalLinksCustom').value;

                length = styleeditor.activeElement.element.childNodes.length;

                if ( $(styleeditor.activeElement.element).closest(bConfig.navSelector).size() === 1 && styleeditor.inputLinkActive.checked) {

                    styleeditor.activeElement.element.parentNode.classList.add(bConfig.navActiveClass);

                } else {

                    styleeditor.activeElement.element.parentNode.classList.remove(bConfig.navActiveClass);

                }

                //does the link contain an image?
                if( styleeditor.linkImage ) {

                    //console.log('Case 1');

                    styleeditor.activeElement.element.childNodes[length-1].nodeValue = document.getElementById('linkText').value;

                } else if ( styleeditor.linkIcon ) {

                    //console.log('Case 2');

                    styleeditor.activeElement.element.childNodes[length-1].nodeValue = document.getElementById('linkText').value;

                } else {

                    //console.log('Case 3');

                    styleeditor.activeElement.element.innerText = document.getElementById('linkText').value;

                }

                // Linking to a modal?
                styleeditor.activeElement.element.removeAttribute('data-toggle');

                siteBuilder.site.activePage.popups.forEach( (popup) => {
                    if ( '#' + popup.popupID === document.getElementById('internalLinksCustom').value ) { // Links to a popup
                        styleeditor.activeElement.element.setAttribute('data-toggle', 'modal');
                    }
                });

                // Open in a new window?
                if ( styleeditor.checkboxOpeninNewTab.checked ) {
                    styleeditor.activeElement.element.setAttribute('target', '_blank');
                    console.log('check');
                } else {
                    styleeditor.activeElement.element.removeAttribute('target');
                    console.log('uncheck');
                }

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).attr('href', $('input#internalLinksCustom').val());


                }

                /* END SANDBOX */

            }

            if( $(styleeditor.activeElement.element).parent().prop('tagName') === 'A' ) {

                //change the href prop?
                styleeditor.activeElement.element.parentNode.href = document.getElementById('internalLinksCustom').value;

                length = styleeditor.activeElement.element.childNodes.length;

                // Linking to a modal?
                styleeditor.activeElement.element.parentNode.removeAttribute('data-toggle');

                siteBuilder.site.activePage.popups.forEach( (popup) => {
                    if ( '#' + popup.popupID === document.getElementById('internalLinksCustom').value ) { // Links to a popup
                        styleeditor.activeElement.element.parentNode.setAttribute('data-toggle', 'modal');
                    }
                });


                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).parent().attr('href', $('input#internalLinksCustom').val());

                }

                /* END SANDBOX */

            }

            //icons
            if( $(styleeditor.activeElement.element).hasClass('fa') ) {

                let theHref;

                //out with the old, in with the new :)
                //get icon class name, starting with fa-
                var get = $.grep(styleeditor.activeElement.element.className.split(" "), function(v, i){

                    return v.indexOf('fa-') === 0;

                }).join();

                //if the icons is being changed, save the old one so we can reset it if needed

                if( get !== $('select#icons').val() ) {

                    $(styleeditor.activeElement.element).uniqueId();
                    styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] = get;

                }

                $(styleeditor.activeElement.element).removeClass( get ).addClass( $('select#icons').val() );

                // Link on image?
                if ( styleeditor.selectLinksPages.value !== '#' ) theHref = styleeditor.selectLinksPages.value;
                if ( styleeditor.selectLinksInernal.value !== '#' ) theHref = styleeditor.selectLinksInernal.value;
                if ( styleeditor.inputCustomLink.value !== '' ) theHref = styleeditor.inputCustomLink.value;

                if ( typeof theHref !== 'undefined' ) {

                    if ( styleeditor.activeElement.element.parentNode.tagName === 'A' ) {//parent is already an anchor tag

                        styleeditor.activeElement.element.parentNode.href = theHref;

                    } else {//no anchor tag yet

                        $(styleeditor.activeElement.element).wrap('<a href="' + theHref + '"></a>');

                    }

                    styleeditor.activeElement.element.parentNode.removeAttribute('data-toggle');

                    siteBuilder.site.activePage.popups.forEach( (popup) => {
                        if ( '#' + popup.popupID === document.getElementById('internalLinksCustom').value ) { // Links to a popup
                            styleeditor.activeElement.element.parentNode.setAttribute('data-toggle', 'modal');
                        }
                    });

                } else {

                    // All link fields are empty; if the active element has a parent anchor tag, remove it
                    if ( styleeditor.activeElement.element.parentNode.tagName === 'A' ) $(styleeditor.activeElement.element).unwrap();

                }


                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');
                    $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).removeClass( get ).addClass( $('select#icons').val() );

                }

                /* END SANDBOX */

            }

            //video URL
            if( $(styleeditor.activeElement.element).attr('data-type') === 'video' ) {

                if( $('input#youtubeID').val() !== '' ) {

                    $(styleeditor.activeElement.element).prev().attr('src', "//www.youtube.com/embed/"+$('#video_Tab input#youtubeID').val());

                } else if( $('input#vimeoID').val() !== '' ) {

                    $(styleeditor.activeElement.element).prev().attr('src', "//player.vimeo.com/video/"+$('#video_Tab input#vimeoID').val()+"?title=0&amp;byline=0&amp;portrait=0");

                }

                /* SANDBOX */

                if( styleeditor.activeElement.sandbox ) {

                    elementID = $(styleeditor.activeElement.element).attr('id');

                    if( $('input#youtubeID').val() !== '' ) {

                        $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).prev().attr('src', "//www.youtube.com/embed/"+$('#video_Tab input#youtubeID').val());

                    } else if( $('input#vimeoID').val() !== '' ) {

                        $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).prev().attr('src', "//player.vimeo.com/video/"+$('#video_Tab input#vimeoID').val()+"?title=0&amp;byline=0&amp;portrait=0");

                    }

                }

                /* END SANDBOX */

            }
            //textarea
            if ( styleeditor.activeElement.element.tagName === 'TEXTAREA' ){
                if(styleeditor.inputId !== null){
                    styleeditor.activeElement.element.setAttribute('id', $('#textarea_id').val());
                    styleeditor.activeElement.element.setAttribute('name', $('#textarea_name').val());
                    styleeditor.activeElement.element.setAttribute('placeholder', $('#textarea_placeholder').val());
                }
            }
            //input
            if ( styleeditor.activeElement.element.tagName === 'INPUT' ){
                if(styleeditor.inputId !== null){
                    styleeditor.activeElement.element.setAttribute('id', $('#input_id').val());
                    styleeditor.activeElement.element.setAttribute('name', $('#input_name').val());
                    styleeditor.activeElement.element.setAttribute('placeholder', $('#input_placeholder').val());
                    styleeditor.activeElement.element.setAttribute('type', $('#input_type option:selected').val());
                }
            }
            //forms
            if ( styleeditor.activeElement.element.tagName === 'FORM' ) {

                //sent API / custom action / API integration?

                //remove possible confirmation input
                if ( styleeditor.activeElement.element.querySelector('input[name="_confirmation"]') ) styleeditor.activeElement.element.querySelector('input[name="_confirmation"]').remove();

                //remove possible API integration hidden inputs
                if ( styleeditor.activeElement.element.querySelector('input[name="integration_name"]') ) styleeditor.activeElement.element.querySelector('input[name="integration_name"]').remove();
                if ( styleeditor.activeElement.element.querySelector('input[name="api_list_id"]') ) styleeditor.activeElement.element.querySelector('input[name="api_list_id"]').remove();

                //remove possible API integration with html code hidden inputs
                if ( styleeditor.activeElement.element.querySelector('input[name="integration_name_with_html_code"]') ) styleeditor.activeElement.element.querySelector('input[name="integration_name_with_html_code"]').remove();
                if ( styleeditor.activeElement.element.querySelector('input[name="api_list_id_with_html_code"]') ) styleeditor.activeElement.element.querySelector('input[name="api_list_id_with_html_code"]').remove();
                if ( styleeditor.activeElement.element.querySelector('input[name="textarea_html_code"]') ) styleeditor.activeElement.element.querySelector('input[name="textarea_html_code"]').remove();

                //remove the siteID hidden input
                if ( styleeditor.activeElement.element.querySelector('input[name="hiddenInputSiteID"]') ) styleeditor.activeElement.element.querySelector('input[name="hiddenInputSiteID"]').remove();

                if ( styleeditor.checkboxEmailForm.checked ) {

                    styleeditor.activeElement.element.setAttribute('action', bConfig.sentApiURL + styleeditor.inputEmailFormTo.value);
                    styleeditor.activeElement.element.setAttribute('data-action', 'sentapi');

                    // Insert hidden field for the site ID
                    if ( typeof siteBuilder.site.data.sites_id !== undefined ) {
                        let input = document.createElement('input');
                        input.type = "hidden";
                        input.name = "hiddenInputSiteID";
                        input.value = siteBuilder.site.data.sites_id;
                        styleeditor.activeElement.element.appendChild(input);
                    }

                    //custom confirmation message?
                    if ( styleeditor.textareaCustomMessage.value !== '' ) {

                        var confirmationInput = document.createElement('input');
                        confirmationInput.type = "hidden";
                        confirmationInput.name = "_confirmation";
                        confirmationInput.value = styleeditor.textareaCustomMessage.value;

                        styleeditor.activeElement.element.appendChild(confirmationInput);

                    }

                } else if ( styleeditor.checkboxApiIntegration.checked ) {

                    styleeditor.activeElement.element.setAttribute('action', bConfig.sentApiURL);
                    styleeditor.activeElement.element.setAttribute('data-action', 'api_integration');

                    let inputAPI = document.createElement('input');
                    inputAPI.type = "hidden";
                    inputAPI.name = "integration_name";
                    inputAPI.value = styleeditor.selectConfiguredApi.value;
                    styleeditor.activeElement.element.appendChild(inputAPI);

                    let inputList = document.createElement('input');
                    inputList.type = "hidden";
                    inputList.name = "api_list_id";
                    inputList.value = styleeditor.selectApiList.value;
                    styleeditor.activeElement.element.appendChild(inputList);

                    if ( typeof siteBuilder.site.data.sites_id !== undefined ) {
                        let input = document.createElement('input');
                        input.type = "hidden";
                        input.name = "hiddenInputSiteID";
                        input.value = siteBuilder.site.data.sites_id;
                        styleeditor.activeElement.element.appendChild(input);
                    }

                } else if ( styleeditor.checkboxApiIntegrationWithHtmlCode.checked ) {
                    if (/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi.test(styleeditor.textareaHtmlCode.value)) {
                        alert("Sorry - The Auto-Responder code should be HTML only, a script tag has been found");
                        return false;
                    }

                    if (/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi.test(styleeditor.textareaHtmlCode.value)) {
                        alert("Sorry - The Auto-Responder code should be HTML only, a style tag has been found");
                        return false;
                    }

                    let htmlCodeInputNamesArray = [];
                    $(styleeditor.textareaHtmlCode.value).html(styleeditor.textareaHtmlCode.value).find("input").each(function(l) {
                        if(($(this).attr('type')) !== 'submit') {
                            htmlCodeInputNamesArray.push($(this).attr('name'));
                        }
                    });

                    let hiddenInput;
                    let hiddenInputName;
                    $(styleeditor.textareaHtmlCode.value).html(styleeditor.textareaHtmlCode.value).find("div").each(function(l) {
                        if(($(this).attr('aria-hidden')) === 'true') {
                             hiddenInput = $(this).html();
                             hiddenInputName = hiddenInput.match(/<input.*name=["|'](.*?)["|']/)[1];
                        }
                    });

                    let index = htmlCodeInputNamesArray.indexOf(hiddenInputName);

                    if (index > -1) {
                        htmlCodeInputNamesArray.splice(index, 1);
                    }

                    for (var key in htmlCodeInputNamesArray) {
                        if( !styleeditor.activeElement.element.querySelector('input[name="' + htmlCodeInputNamesArray[key] + '"]')){
                            alert('Ooops something wjnet wrong, input names did not match');
                            return false;
                        }
                    }

                    styleeditor.activeElement.element.setAttribute('action', /\action="(.*?)\"/.exec(styleeditor.textareaHtmlCode.value)[1]);
                    styleeditor.activeElement.element.setAttribute('data-action', 'api_integration_with_html_code');

                    let inputListHtmlCode = document.createElement('input');
                    inputListHtmlCode.type = "hidden";
                    inputListHtmlCode.name = "textarea_html_code";
                    inputListHtmlCode.value = styleeditor.textareaHtmlCode.value;
                    styleeditor.activeElement.element.appendChild(inputListHtmlCode);
                } else {
                    if ( styleeditor.checkboxCustomAction.checked ) {
                        styleeditor.activeElement.element.setAttribute('action', styleeditor.inputCustomAction.value);
                        styleeditor.activeElement.element.setAttribute('data-action', 'custom');
                    }
                }
            }

            //image
            if ( styleeditor.activeElement.element.tagName === 'IMG' ) {

                let theHref;

                //lightbox image
                if ( $(styleeditor.activeElement.element).parents(bConfig.imageLightboxWrapper).size() > 0 ) {
                    $(styleeditor.activeElement.element).parents(bConfig.imageLightboxWrapper).find('a').attr(bConfig.imageLightboxAttr, styleeditor.inputCombinedGallery.value);
                }

                //title attribute
                if ( styleeditor.inputImageTitle.value !== '' ) styleeditor.activeElement.element.setAttribute('title', styleeditor.inputImageTitle.value);
                else styleeditor.activeElement.element.removeAttribute('title');

                //alt attribute
                if ( styleeditor.inputImageAlt.value !== '' ) styleeditor.activeElement.element.setAttribute('alt', styleeditor.inputImageAlt.value);
                else styleeditor.activeElement.element.removeAttribute('alt');

                // Link on image? (not for lightbox images)
                if ( $(styleeditor.activeElement.element).parents('[data-component="image-lightbox"]').size() === 0 ) {
                    if ( styleeditor.selectLinksPages.value !== '#' ) theHref = styleeditor.selectLinksPages.value;
                    if ( styleeditor.selectLinksInernal.value !== '#' ) theHref = styleeditor.selectLinksInernal.value;
                    if ( styleeditor.inputCustomLink.value !== '' ) theHref = styleeditor.inputCustomLink.value;

                    if ( typeof theHref !== 'undefined' ) {

                        if ( styleeditor.activeElement.element.parentNode.tagName === 'A' ) {//parent is already an anchor tag

                            styleeditor.activeElement.element.parentNode.href = theHref;


                        } else {//no anchor tag yet

                            $(styleeditor.activeElement.element).wrap('<a href="' + theHref + '"></a>');

                        }

                        styleeditor.activeElement.element.parentNode.removeAttribute('data-toggle');

                        siteBuilder.site.activePage.popups.forEach( (popup) => {
                            if ( '#' + popup.popupID === document.getElementById('internalLinksCustom').value ) { // Links to a popup
                                styleeditor.activeElement.element.parentNode.setAttribute('data-toggle', 'modal');
                            }
                        });

                    } else {

                        // All link fields are empty; if the active element has a parent anchor tag, remove it
                        if ( $(styleeditor.activeElement.element.parentNode).prop('tagName') === 'A' ) $(styleeditor.activeElement.element).unwrap();

                    }
                }

            }

            //slideshow
            if ( styleeditor.activeElement.element.parentNode.parentNode.parentNode.hasAttribute('data-carousel-item') ) {

                var theSlideshow = $(styleeditor.activeElement.element).closest('.carousel')[0];

                //auto play
                if ( styleeditor.checkboxSliderAutoplay.checked ) {
                    theSlideshow.setAttribute('data-ride', 'carousel');
                } else {
                    theSlideshow.removeAttribute('data-ride');
                }

                //pause on hover
                if ( styleeditor.checkboxSliderPause.checked ) {
                    theSlideshow.setAttribute('data-pause', 'hover');
                } else {
                    theSlideshow.removeAttribute('data-pause');
                }

                //animation
                if ( styleeditor.selectSliderAnimation.value === 'carousel-fade' && !theSlideshow.classList.contains('carousel-fade') ) {
                    theSlideshow.classList.add('carousel-fade');
                } else {
                    theSlideshow.classList.remove('carousel-fade');
                }

                //interval
                if ( styleeditor.inputSlideInterval.value !== '' ) {
                    theSlideshow.setAttribute('data-interval', styleeditor.inputSlideInterval.value);
                } else {
                    theSlideshow.removeAttribute('data-interval');
                }

                //nav arrows
                theSlideshow.classList.remove('nav-arrows-out');
                theSlideshow.classList.remove('nav-arrows-none');
                theSlideshow.classList.remove('nav-arrows-in');

                if ( styleeditor.selectSliderNavArrows.value === 'nav-arrows-out' ) {
                    theSlideshow.classList.add('nav-arrows-out');
                } else if ( styleeditor.selectSliderNavArrows.value === 'nav-arrows-none' ) {
                    theSlideshow.classList.add('nav-arrows-none');
                } else {
                    theSlideshow.classList.add('nav-arrows-in');
                }

                //nav indicators
                theSlideshow.classList.remove('nav-indicators-out');
                theSlideshow.classList.remove('nav-indicators-none');
                theSlideshow.classList.remove('nav-indicators-in');

                if ( styleeditor.selectSliderNavIndicators.value === 'nav-indicators-out' ) {
                    theSlideshow.classList.add('nav-indicators-out');
                } else if ( styleeditor.selectSliderNavIndicators.value === 'nav-indicators-none' ) {
                    theSlideshow.classList.add('nav-indicators-none');
                } else {
                    theSlideshow.classList.add('nav-indicators-in');
                }

            }

            //Map
            if ( styleeditor.activeElement.element.classList.contains('mapOverlay') && typeof bConfig.google_api !== 'undefined' ) {

                var theMap = $(styleeditor.activeElement.element).prev()[0],
                    apiInfo = {};

                //setup the data attributes
                if ( styleeditor.textareaAddress.value !== '' ) {
                    theMap.setAttribute('data-address', styleeditor.textareaAddress.value);
                } else {
                    theMap.removeAttribute('data-address');
                }

                if ( styleeditor.textareaInfoMessage.value !== '' ) {
                    theMap.setAttribute('data-info-message', styleeditor.textareaInfoMessage.value);
                } else {
                    theMap.removeAttribute('data-info-message');
                }

                if ( styleeditor.inputZoomLevel.value !== 0 ) {
                    theMap.setAttribute('data-zoom', styleeditor.inputZoomLevel.value);
                } else {
                    theMap.removeAttribute('data-zoom');
                }

                if ( styleeditor.checkBoxMapBW.checked ) {
                    theMap.setAttribute('data-style', 'blackandwhite');
                } else {
                    theMap.removeAttribute('data-style');
                }


                //load the Google Maps API
                apiInfo.action = "loadMapAPI";
                apiInfo.key = bConfig.google_api;
                styleeditor.activeElement.parentBlock.frame.contentWindow.postMessage(apiInfo, '*');
                document.getElementById('skeleton').contentWindow.postMessage(apiInfo, '*');

            }

            //Countdown
            if ( styleeditor.activeElement.element.classList.contains('countdown') ) {

                let deadline = styleeditor.countdownPicker.value;
                let gmtConversion = styleeditor.selectGmtConversion.options[styleeditor.selectGmtConversion.selectedIndex].getAttribute('data-offset');
                let regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

                if ( styleeditor.selectGmtConversion.value !== '' ) {
                    deadline += " ";
                    deadline += "GMT" + gmtConversion.replace(':', '');
                }

                // Make sure the countdown has a unique ID
                $(styleeditor.activeElement.element).uniqueId();

                // Set the type
                if ( styleeditor.checkboxCountdownType.checked ) {
                    styleeditor.activeElement.element.setAttribute('data-countdown', 'landing');
                    styleeditor.checkboxDaily.checked = false;

                    let dataDays = 0;
                    let dataHours =0;
                    let dataMinutes =0;

                    if (styleeditor.inputCountdownDays.value !== '') {
                        styleeditor.activeElement.element.setAttribute('data-days', styleeditor.inputCountdownDays.value);
                    } else {
                        styleeditor.activeElement.element.removeAttribute('data-days');
                    }

                    if (styleeditor.inputCountdownHours.value !== '') {
                        styleeditor.activeElement.element.setAttribute('data-hours', styleeditor.inputCountdownHours.value);
                        dataDays = styleeditor.inputCountdownDays.value;
                    } else {
                        styleeditor.activeElement.element.removeAttribute('data-hours');
                    }

                    if (styleeditor.inputCountdownMinutes.value !== '') {
                        styleeditor.activeElement.element.setAttribute('data-minutes', styleeditor.inputCountdownMinutes.value);
                        dataHours = styleeditor.inputCountdownHours.value;
                    } else {
                        styleeditor.activeElement.element.removeAttribute('data-minutes');
                        dataMinutes = styleeditor.inputCountdownMinutes.value;
                    }

                    styleeditor.activeElement.element.setAttribute('data-deadline', moment().add({days: dataDays,hours: dataHours,minutes: dataMinutes}).format('YYYY-MM-DD HH:mm:ss'));
                    /*if ( styleeditor.checkboxDailyReset.checked ) {// Daily reset timer

                        styleeditor.activeElement.element.setAttribute('data-daily-reset', true);

                    } else {

                        styleeditor.activeElement.element.removeAttribute('data-daily-reset');

                    }*/

                    styleeditor.activeElement.element.setAttribute('data-countdown-reset', document.querySelector('input[name="radioCountdownReset"]:checked').value);

                    styleeditor.activeElement.element.removeAttribute('data-deadline-timezone');
                } else {
                    styleeditor.activeElement.element.setAttribute('data-countdown', 'fixed');

                    styleeditor.activeElement.element.removeAttribute('data-daily-reset');

                    styleeditor.activeElement.element.setAttribute('data-deadline', deadline);

                    if ( styleeditor.selectGmtConversion.value !== '' ) styleeditor.activeElement.element.setAttribute('data-deadline-timezone', styleeditor.selectGmtConversion.value);
                    else styleeditor.activeElement.element.removeAttribute('data-deadline-timezone');

                    styleeditor.activeElement.element.removeAttribute('data-days');
                    styleeditor.activeElement.element.removeAttribute('data-hours');
                    styleeditor.activeElement.element.removeAttribute('data-minutes');

                }

                if ( styleeditor.checkboxDeadlineUrl.checked ) {// We have a redirect

                    styleeditor.activeElement.element.setAttribute('data-deadline-url', styleeditor.inputDeadlineRedirect.value);

                    if ( styleeditor.inputDeadlineRedirect.value === '' || !regexp.test(styleeditor.inputDeadlineRedirect.value) ) {
                        styleeditor.inputDeadlineRedirect.parentNode.classList.add('has-error');
                        return false;
                    } else {
                        styleeditor.inputDeadlineRedirect.parentNode.classList.remove('has-error');
                    }

                } else { // No redirect required

                    styleeditor.inputDeadlineRedirect.parentNode.classList.remove('has-error');
                    styleeditor.activeElement.element.removeAttribute('data-deadline-url');

                }

                if ( styleeditor.checkboxDaily.checked ) {
                    styleeditor.activeElement.element.setAttribute('daily-countdown', true);
                    let timeToArray = styleeditor.countdownPicker.value.split(':');
                    styleeditor.activeElement.element.setAttribute('data-deadline', moment().set({hours: timeToArray[0],minutes: timeToArray[1],seconds: timeToArray[2]}).format('YYYY-MM-DD HH:mm:ss'));

                    let dailyDuration = styleeditor.countdownPicker.value;

                    let dailyDurationHours = dailyDuration.split(':')[0];
                    let dailyDurationMinutes = dailyDuration.split(':')[1];
                    let dailyDurationSeconds = dailyDuration.split(':')[2];


                    if(dailyDurationHours.charAt(0) === '0' && dailyDurationHours.length === 2) {
                        dailyDurationHours = dailyDurationHours.slice(1);
                    }

                    if(dailyDurationMinutes.charAt(0) === '0' && dailyDurationMinutes.length === 2) {
                        dailyDurationMinutes = dailyDurationMinutes.slice(1);
                    }

                    if(dailyDurationSeconds.charAt(0) === '0' && dailyDurationSeconds.length === 2) {
                        dailyDurationSeconds = dailyDurationSeconds.slice(1);
                    }

                    styleeditor.activeElement.element.setAttribute('daily-duration', dailyDurationHours + ':' + dailyDurationMinutes + ':' + dailyDurationSeconds);
                } else {
                    styleeditor.activeElement.element.removeAttribute('daily-countdown');
                    styleeditor.activeElement.element.removeAttribute('daily-duration');
                }
            }

            $('#detailsAppliedMessage').fadeIn(600, function(){

                setTimeout(function(){ $('#detailsAppliedMessage').fadeOut(1000); }, 3000);

            });

            //adjust frame height
            styleeditor.activeElement.parentBlock.heightAdjustment();


            //we've got pending changes
            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        /*
            on focus, we'll make the input fields wider
        */
        animateStyleInputIn: function() {

            $(this).css('position', 'absolute');
            $(this).css('right', '0px');
            $(this).animate({'width': '100%'}, 500);
            $(this).focus(function(){
                this.select();
            });

        },


        /*
            on blur, we'll revert the input fields to their original size
        */
        animateStyleInputOut: function() {

            $(this).animate({'width': '42%'}, 500, function(){
                $(this).css('position', 'relative');
                $(this).css('right', 'auto');
            });

        },


        /*
            builds the dropdown with #blocks on this page
        */
        buildBlocksDropdown: function (currentVal) {

            $(styleeditor.selectLinksInernal).select2('destroy');

            if( typeof currentVal === 'undefined' ) currentVal = null;

            var x,
                newOption;

            styleeditor.selectLinksInernal.innerHTML = '';

            newOption = document.createElement('OPTION');
            newOption.innerText = styleeditor.selectLinksInernal.getAttribute('data-placeholder');
            newOption.setAttribute('value', '#');
            styleeditor.selectLinksInernal.appendChild(newOption);

            for ( x = 0; x < siteBuilder.site.activePage.blocks.length; x++ ) {

                var frameDoc = siteBuilder.site.activePage.blocks[x].frameDocument;
                var pageContainer  = frameDoc.querySelector(bConfig.pageContainer);

                if ( pageContainer !== null && pageContainer.children[0] ) {

                    var theID = pageContainer.children[0].id;

                    newOption = document.createElement('OPTION');
                    newOption.innerText = '#' + theID;
                    newOption.setAttribute('value', '#' + theID);
                    if( currentVal === '#' + theID ) newOption.setAttribute('selected', true);

                    styleeditor.selectLinksInernal.appendChild(newOption);

                }

            }

            var c = 1;

            for ( x = 0; x < siteBuilder.site.activePage.popups.length; x++ ) {

                if ( siteBuilder.site.activePage.popups[x].popupType !== 'regular' ) continue;

                newOption = document.createElement('OPTION');
                newOption.innerText = 'Popup ' + c + ' (#' + siteBuilder.site.activePage.popups[x].popupID + ')';
                newOption.setAttribute('value', '#' + siteBuilder.site.activePage.popups[x].popupID);
                newOption.setAttribute('data-toggle', 'popup');
                if( currentVal === '#' + siteBuilder.site.activePage.popups[x].popupID ) newOption.setAttribute('selected', true);

                styleeditor.selectLinksInernal.appendChild(newOption);

                c++;

            }

            $(styleeditor.selectLinksInernal).select2({
                minimumResultsForSearch: -1
            });
            $(styleeditor.selectLinksInernal).trigger('change');

            $(styleeditor.selectLinksInernal).off('change').on('change', function () {
                styleeditor.inputCustomLink.value = this.value;
                styleeditor.resetPageDropdown();
            });

        },


        /*
            blur event handler for the custom link input
        */
        inputCustomLinkBlur: function (e) {

            var value = e.target.value,
                x;

            //pages match?
            for ( x = 0; x < styleeditor.selectLinksPages.querySelectorAll('option').length; x++ ) {

                if ( value === styleeditor.selectLinksPages.querySelectorAll('option')[x].value ) {

                    styleeditor.selectLinksPages.selectedIndex = x;
                    $(styleeditor.selectLinksPages).trigger('change').select2();

                }

            }

            //blocks match?
            styleeditor.selectLinksInernal.querySelectorAll('option').forEach(function (option, i) {

                if ( value === option.value ) {

                    styleeditor.selectLinksInernal.selectedIndex = i;
                    $(styleeditor.selectLinksInernal).trigger('change').select2();

                }

            });

        },


        /*
            focus event handler for the custom link input
        */
        inputCustomLinkFocus: function () {

            styleeditor.resetPageDropdown();
            styleeditor.resetBlockDropdown();

        },


        /*
            builds the dropdown with pages to link to
        */
        buildPagesDropdown: function (currentVal) {

            $(styleeditor.selectLinksPages).select2('destroy');

            if( typeof currentVal === 'undefined' ) currentVal = null;

            var x,
                newOption;

            styleeditor.selectLinksPages.innerHTML = '';

            newOption = document.createElement('OPTION');
            newOption.innerText = styleeditor.selectLinksPages.getAttribute('data-placeholder');
            newOption.setAttribute('value', '#');
            styleeditor.selectLinksPages.appendChild(newOption);

            for( x = 0; x < siteBuilder.site.sitePages.length; x++ ) {

                newOption = document.createElement('OPTION');
                newOption.innerText = siteBuilder.site.sitePages[x].name;
                newOption.setAttribute('value', siteBuilder.site.sitePages[x].name + '.html');
                if( currentVal === siteBuilder.site.sitePages[x].name + '.html') newOption.setAttribute('selected', true);

                styleeditor.selectLinksPages.appendChild(newOption);

            }

            $(styleeditor.selectLinksPages).select2({
                minimumResultsForSearch: -1
            });
            $(styleeditor.selectLinksPages).trigger('change');

            $(styleeditor.selectLinksPages).off('change').on('change', function () {
                styleeditor.inputCustomLink.value = this.value;
                styleeditor.resetBlockDropdown();
            });

        },


        /*
            reset the block link dropdown
        */
        resetBlockDropdown: function () {

            styleeditor.selectLinksInernal.selectedIndex = 0;
            $(styleeditor.selectLinksInernal).select2('destroy').select2();

        },


        /*
            reset the page link dropdown
        */
        resetPageDropdown: function () {

            styleeditor.selectLinksPages.selectedIndex = 0;
            $(styleeditor.selectLinksPages).select2('destroy').select2();

        },


        /*
            when the clicked element is an anchor tag (or has a parent anchor tag)
        */
        editLink: function(el) {

            var theHref;

            $('a#link_Link').parent().show();

            styleeditor.inputLinkText.parentNode.style.display = 'block';

            //set theHref
            if( $(el).prop('tagName') === 'A' ) {

                theHref = $(el).attr('href');

            } else if( $(el).parent().prop('tagName') === 'A' ) {

                theHref = $(el).parent().attr('href');

            }

            if ( $(el).closest(bConfig.navSelector).size() === 1) {

                styleeditor.inputLinkActive.parentNode.style.display = 'block';

                //link is active?
                if ( el.parentNode.classList.contains(bConfig.navActiveClass) ) {
                    //$(styleeditor.inputLinkActive).radiocheck('checked');
                    $(styleeditor.inputLinkActive).prop('checked', true);
                } else {
                    //$(styleeditor.inputLinkActive).radiocheck('unchecked');
                    $(styleeditor.inputLinkActive).prop('checked', false);
                }

            } else {

                styleeditor.inputLinkActive.parentNode.style.display = 'none';

            }

            // Open in new window?
            if ( el.hasAttribute('target') && el.getAttribute('target') === '_blank' ) {
                styleeditor.checkboxOpeninNewTab.checked = true;
            } else {
                styleeditor.checkboxOpeninNewTab.checked = false;
            }

            styleeditor.buildPagesDropdown(theHref);
            styleeditor.buildBlocksDropdown(theHref);
            styleeditor.inputCustomLink.value = theHref;

            //grab an image?
            if ( el.querySelector('img') ) styleeditor.linkImage = el.querySelector('img');
            else styleeditor.linkImage = null;

            //grab an icon?
            if ( el.querySelector('.fa') ) styleeditor.linkIcon = el.querySelector('.fa').cloneNode(true);
            else styleeditor.linkIcon = null;

            styleeditor.inputLinkText.value = el.innerText;

        },


        /*
            when the clicked element is an image
        */
        editImage: function(el) {

            var theHref;

            $('a#img_Link').parent().show();
            if ( $(el).parents('[data-component="image-lightbox"]').size() === 0 ) $('a#link_Link').parent().show();

            // Link stuff
            styleeditor.inputLinkText.parentNode.style.display = 'none';
            styleeditor.inputLinkActive.parentNode.style.display = 'none';

            if( $(el).parent().prop('tagName') === 'A' ) theHref = $(el).parent().attr('href');
            else theHref = '';

            styleeditor.buildPagesDropdown(theHref);
            styleeditor.buildBlocksDropdown(theHref);
            styleeditor.inputCustomLink.value = theHref;

            //set the current SRC
            $('.imageFileTab').find('input#imageURL').val( $(el).attr('src') );

            //reset the file upload
            $('.imageFileTab').find('a.fileinput-exists').click();

            //are we dealing with a lightbox image?
            if ( $(el).parents(bConfig.imageLightboxWrapper).size() > 0 ) {
                if ( $(el).parents(bConfig.imageLightboxWrapper).find('a')[0].hasAttribute( bConfig.imageLightboxAttr ) ) {
                   styleeditor.inputCombinedGallery.value = $(el).parents(bConfig.imageLightboxWrapper).find('a').attr( bConfig.imageLightboxAttr );
                } else {
                    styleeditor.inputCombinedGallery.value = "";
                }
                styleeditor.inputCombinedGallery.style.display = 'block';
            } else {
                styleeditor.inputCombinedGallery.value = "";
                styleeditor.inputCombinedGallery.style.display = 'none';
            }

            //image title
            if ( el.hasAttribute('title') ) styleeditor.inputImageTitle.value = el.getAttribute('title');

            //image alt
            if ( el.hasAttribute('alt') ) styleeditor.inputImageAlt.value = el.getAttribute('alt');

        },


        /*
            when the clicked element is a video element
        */
        editVideo: function(el) {

            var matchResults;

            $('a#video_Link').parent().show();
            $('a#video_Link').click();

            //inject current video ID,check if we're dealing with Youtube or Vimeo

            if( $(el).prev().attr('src').indexOf("vimeo.com") > -1 ) {//vimeo

                matchResults = $(el).prev().attr('src').match(/player\.vimeo\.com\/video\/([0-9]*)/);

                $('#video_Tab input#vimeoID').val( matchResults[matchResults.length-1] );
                $('#video_Tab input#youtubeID').val('');

            } else {//youtube

                //temp = $(el).prev().attr('src').split('/');
                var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
                matchResults = $(el).prev().attr('src').match(regExp);

                $('#video_Tab input#youtubeID').val( matchResults[1] );
                $('#video_Tab input#vimeoID').val('');

            }

        },


        /*
            when the clicked element is an fa icon
        */
        editIcon: function() {

            var theHref;

            $('a#icon_Link').parent().show();
            $('a#link_Link').parent().show();

            // Link stuff
            styleeditor.inputLinkText.parentNode.style.display = 'none';
            styleeditor.inputLinkActive.parentNode.style.display = 'none';

            if( $(this.activeElement.element).parent().prop('tagName') === 'A' ) theHref = $(this.activeElement.element).parent().attr('href');
            else theHref = '';

            styleeditor.buildPagesDropdown(theHref);
            styleeditor.buildBlocksDropdown(theHref);
            styleeditor.inputCustomLink.value = theHref;

            //get icon class name, starting with fa-
            var get = $.grep(this.activeElement.element.className.split(" "), function(v, i){

                return v.indexOf('fa-') === 0;

            }).join();

            $('select#icons option').each(function(){

                this.removeAttribute('selected');

                if( $(this).val() === get ) {

                    $(this).attr('selected', true);

                    $('#icons').trigger('chosen:updated');

                }

            });

        },


        editNavbar: function (element) {

            var links,
                buttons;

            $('a#menuitems_Link').parent().show();

            //retrieve the links

            if ( styleeditor.activeElement.element.hasAttribute('class') ) {

                if ( styleeditor.activeElement.element.getAttribute('class').indexOf('bloxby-navbar-left') !== -1 ) {

                    links = styleeditor.activeElement.element.querySelectorAll('.collapse > ul:nth-child(1) a:not(.btn)');
                    buttons = styleeditor.activeElement.element.querySelectorAll('.collapse a.btn');

                } else if ( styleeditor.activeElement.element.getAttribute('class').indexOf('bloxby-navbar-left-right') !== -1 ) {

                    links = styleeditor.activeElement.element.querySelectorAll('.collapse > ul:nth-child(1) a:not(.btn)');
                    buttons = styleeditor.activeElement.element.querySelectorAll('.collapse a.btn');

                } else if ( styleeditor.activeElement.element.getAttribute('class').indexOf('bloxby-navbar-right') !== -1 ) {

                    links = styleeditor.activeElement.element.querySelectorAll('.collapse > ul:nth-child(2) a:not(.btn)');
                    buttons = styleeditor.activeElement.element.querySelectorAll('.collapse a.btn');

                } else if ( styleeditor.activeElement.element.getAttribute('class').indexOf('bloxby-navbar-centered') !== -1 ) {

                    links = styleeditor.activeElement.element.querySelectorAll('.collapse > ul:nth-child(1) a:not(.btn)');
                    buttons = styleeditor.activeElement.element.querySelectorAll('.collapse a.btn');

                }

            }

            //console.log("links", links);
            //console.log("buttons", buttons);

        },


        editForm: function (form) {

            var email;
            var apiName = '';
            var apiList = '';

            $('a#form_Link').parent().show();

            if ( form.hasAttribute('data-action') ) {

                if ( form.getAttribute('data-action') === 'sentapi' ) {

                    email = form.getAttribute('action').replace(bConfig.sentApiURL, '');

                    styleeditor.checkboxEmailForm.checked = true;
                    styleeditor.inputEmailFormTo.removeAttribute('disabled');
                    styleeditor.textareaCustomMessage.removeAttribute('disabled');
                    styleeditor.inputEmailFormTo.value = email;

                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.value = "";
                    styleeditor.inputCustomAction.setAttribute('disabled', false);

                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);

                    //confirmation input?
                    if ( form.querySelector('input[name="_confirmation"]') ) {
                        styleeditor.textareaCustomMessage.value = form.querySelector('input[name="_confirmation"]').value;
                    }

                }
                else if ( form.getAttribute('data-action') === 'api_integration' ) {

                    if ( styleeditor.activeElement.element.querySelector('input[name="integration_name"]') ) {
                        apiName = styleeditor.activeElement.element.querySelector('input[name="integration_name"]').value;
                        styleeditor.selectConfiguredApi.value = apiName;
                    }

                    if ( styleeditor.activeElement.element.querySelector('input[name="api_list_id"]') ) {
                        apiList = styleeditor.activeElement.element.querySelector('input[name="api_list_id"]').value;

                        styleeditor.getApiList(apiName, apiList);
                    }

                    styleeditor.checkboxApiIntegration.checked = true;

                    styleeditor.selectConfiguredApi.removeAttribute('disabled');

                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', true);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);
                    //styleeditor.inputEmailFormTo.value = "";
                    styleeditor.textareaCustomMessage.value = "";

                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.value = "";
                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);
                }
                else if ( form.getAttribute('data-action') === 'api_integration_with_html_code' ) {

                    if ( form.querySelector('input[name="textarea_html_code"]') ) {
                        styleeditor.textareaHtmlCode.value = form.querySelector('input[name="textarea_html_code"]').value;
                    }

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = true;
                    styleeditor.textareaHtmlCode.removeAttribute('disabled');

                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', true);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);
                    //styleeditor.inputEmailFormTo.value = "";
                    styleeditor.textareaCustomMessage.value = "";

                    styleeditor.checkboxCustomAction.checked = false;
                    styleeditor.inputCustomAction.value = "";
                    styleeditor.inputCustomAction.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);
                }
                else if ( form.getAttribute('data-action') === 'custom' ) {

                    styleeditor.checkboxEmailForm.checked = false;
                    styleeditor.inputEmailFormTo.setAttribute('disabled', true);
                    styleeditor.textareaCustomMessage.setAttribute('disabled', true);
                    //styleeditor.inputEmailFormTo.value = "";
                    styleeditor.textareaCustomMessage.value = "";

                    styleeditor.checkboxCustomAction.checked = true;
                    styleeditor.inputCustomAction.value = form.getAttribute('action');
                    styleeditor.inputCustomAction.removeAttribute('disabled');

                    styleeditor.checkboxApiIntegration.checked = false;
                    styleeditor.selectConfiguredApi.setAttribute('disabled', true);
                    styleeditor.selectApiList.setAttribute('disabled', true);

                    styleeditor.checkboxApiIntegrationWithHtmlCode.checked = false;
                    styleeditor.textareaHtmlCode.setAttribute('disabled', true);
                }

            } else {

                //nothing set, disable both options
                styleeditor.checkboxEmailForm.checked = false;
                styleeditor.inputEmailFormTo.setAttribute('disabled', true);
                styleeditor.textareaCustomMessage.setAttribute('disabled', true);
                //styleeditor.inputEmailFormTo.value = "";
                styleeditor.textareaCustomMessage.value = "";
                styleeditor.checkboxCustomAction.checked = false;
                styleeditor.inputCustomAction.value = "";
                styleeditor.inputCustomAction.setAttribute('disabled', false);

            }

        },
        editTextarea: function(textarea){
            this.inputId = textarea.getAttribute('id');
            $('#textarea_id').val(this.inputId);
            $('#textarea_placeholder').val(textarea.getAttribute('placeholder'));
            $('#textarea_name').val(textarea.getAttribute('name'));
            $('a#textarea_Link').parent().show();
        },
        editInput: function(input){
            this.inputId = input.getAttribute('id');
            $("#input_type option[value='"+input.getAttribute('type')+"']").prop('selected', true);
            $('#input_id').val(this.inputId);
            $('#input_name').val(input.getAttribute('name'));
            $('#input_placeholder').val(input.getAttribute('placeholder'));
            $('a#input_Link').parent().show();
        },

        editCountdown: function (countdown) {
            let deadline = '';

            if ( countdown.hasAttribute('data-deadline') ) {

                let rawDeadline = countdown.getAttribute('data-deadline');
                let tempArray = rawDeadline.split(' ');

                if ( tempArray.length === 3 ) {

                    tempArray.pop();

                    deadline = tempArray.join(' ');

                } else {

                    deadline = rawDeadline;

                }

            }

            $('a#countdown_Link').parent().show();

            styleeditor.countdownPicker.value = deadline;

            // Type
            if ( countdown.hasAttribute('data-countdown') && countdown.getAttribute('data-countdown') === 'landing' ) {
                styleeditor.checkboxCountdownType.checked = true;

                this.countdownSectionDatetime.style.display = 'none';
                this.countdownSectionLanding.style.display = 'block';
                this.checkboxDailySection.style.display = 'none';
            } else {
                styleeditor.checkboxCountdownType.checked = false;
                this.countdownSectionDatetime.style.display = 'block';
                this.countdownSectionLanding.style.display = 'none';
                this.checkboxDailySection.style.display = 'block';
            }

            tail.DateTime("#countdownPicker", {
                position: "#datetime-demo-holder",
                startOpen: false,
                stayOpen: false,
                closeButton: true,
            });

            $(this.checkboxDaily).on('change', () => {
                if (event.currentTarget.checked && styleeditor.checkboxCountdownType.checked === false) {
                    this.countdownPicker.value = '23:59:59';
                    styleeditor.inputDeadlineRedirect.setAttribute('disabled', true);
                    styleeditor.countdownSectionDatetime.style.marginBottom = '40px';

                    tail.DateTime("#countdownPicker").remove();
                    tail.DateTime("#countdownPicker", {
                        dateFormat: false,
                        position: "#datetime-demo-holder",
                        startOpen: false,
                        stayOpen: false,
                        closeButton: true,
                        timeStepHours: 1,
                        timeStepMinutes: 1,
                        timeStepSeconds: 1,
                    }).on("change", function(){
                        let timeToArray = styleeditor.countdownPicker.value.split(':');
                        styleeditor.activeElement.element.setAttribute('data-deadline', moment().set({hours: timeToArray[0],minutes: timeToArray[1],seconds: timeToArray[2]}).format('YYYY-MM-DD HH:mm:ss'));
                    });
                } else {
                    tail.DateTime("#countdownPicker").remove();
                    tail.DateTime("#countdownPicker", {
                        position: "#datetime-demo-holder",
                        startOpen: false,
                        stayOpen: false,
                        closeButton: true,
                    });
                    this.countdownPicker.value = '';
                    styleeditor.activeElement.element.setAttribute('checkbox-daily', 'false');
                    styleeditor.countdownSectionDatetime.style.marginBottom = '40px';
                }
            });

            if ( countdown.hasAttribute('data-deadline-timezone') ) {
                $(styleeditor.selectGmtConversion).select2('val', countdown.getAttribute('data-deadline-timezone'));
            } else {
                $(styleeditor.selectGmtConversion).select2('val', '');
            }

            // Deadling redirect and URL
            if ( countdown.hasAttribute('data-deadline-url') ) {
                styleeditor.checkboxDeadlineUrl.checked = true;
                styleeditor.inputDeadlineRedirect.value = countdown.getAttribute('data-deadline-url');
            } else {
                styleeditor.checkboxDeadlineUrl.checked = false;
                styleeditor.inputDeadlineRedirect.value = '';
            }

            // Daily countdown
            if ( countdown.hasAttribute('daily-countdown') ) {
                styleeditor.checkboxDaily.checked = true;
                styleeditor.countdownSectionDatetime.style.marginBottom = '40px';

                let dailyDuration = styleeditor.activeElement.element.getAttribute('daily-duration');

                let dailyDurationHours = dailyDuration.split(':')[0];
                let dailyDurationMinutes = dailyDuration.split(':')[1];
                let dailyDurationSeconds = dailyDuration.split(':')[2];

                tail.DateTime("#countdownPicker").remove();
                tail.DateTime("#countdownPicker", {
                    dateFormat: false,
                    position: "#datetime-demo-holder",
                    startOpen: false,
                    stayOpen: false,
                    closeButton: true,
                    timeStepHours: 1,
                    timeStepMinutes: 1,
                    timeStepSeconds: 1,
                    timeHours: parseInt(dailyDurationHours),
                    timeMinutes: parseInt(dailyDurationMinutes),
                    timeSeconds: parseInt(dailyDurationSeconds),
                }).on("change", function(){
                    let timeToArray = styleeditor.countdownPicker.value.split(':');
                    styleeditor.activeElement.element.setAttribute('data-deadline', moment().set({hours: timeToArray[0],minutes: timeToArray[1],seconds: timeToArray[2]}).format('YYYY-MM-DD HH:mm:ss'));
                });
            } else {
                styleeditor.checkboxDaily.checked = false;
                styleeditor.countdownSectionDatetime.style.marginBottom = '40px';
            }

            // Daily reset
            if ( countdown.hasAttribute('data-countdown-reset') ) {

                let options = document.querySelectorAll('input[name="radioCountdownReset"]');

                options.forEach((option) => {

                    if ( option.value === countdown.getAttribute('data-countdown-reset') ) {
                        option.checked = true;
                    } else {
                        option.checked = false;
                    }

                });

            }
            /*if ( countdown.hasAttribute('data-daily-reset') ) {

                styleeditor.checkboxDailyReset.checked = true;

            } else {

                styleeditor.checkboxDailyReset.checked = false;

            }*/

            // Days, hours and minutes
            if (countdown.hasAttribute('data-days')) {
                styleeditor.inputCountdownDays.value = countdown.getAttribute('data-days');
            } else {
                styleeditor.inputCountdownDays.value = '';
            }

            if (countdown.hasAttribute('data-hours')) {
                styleeditor.inputCountdownHours.value = countdown.getAttribute('data-hours');
            } else {
                styleeditor.inputCountdownHours.value = '';
            }

            if (countdown.hasAttribute('data-minutes')) {
                styleeditor.inputCountdownMinutes.value = countdown.getAttribute('data-minutes');
            } else {
                styleeditor.inputCountdownMinutes.value = '';
            }
        },


        editSlideshow: function (slideshow) {

            $('a#slideshow_Link').parent().show();

            //auto play
            if ( slideshow.hasAttribute('data-ride') && slideshow.getAttribute('data-ride') === 'carousel' ) {
                $(styleeditor.checkboxSliderAutoplay).bootstrapSwitch('state', true, true);
            } else {
                $(styleeditor.checkboxSliderAutoplay).bootstrapSwitch('state', false, true);
            }

            //pause on hover
            if ( slideshow.hasAttribute('data-pause') && slideshow.getAttribute('data-pause') === 'hover' ) {
                $(styleeditor.checkboxSliderPause).bootstrapSwitch('state', true, true);
            } else {
                $(styleeditor.checkboxSliderPause).bootstrapSwitch('state', false, true);
            }

            //animation
            if ( slideshow.classList.contains('carousel-fade') ) {
                styleeditor.selectSliderAnimation.value = "carousel-fade";
            } else {
                styleeditor.selectSliderAnimation.value = "";
            }
            $(styleeditor.selectSliderAnimation).trigger('change');

            //interval
            if ( slideshow.hasAttribute('data-interval') ) {
                styleeditor.inputSlideInterval.value = slideshow.getAttribute('data-interval');
            } else {
                styleeditor.inputSlideInterval.value = "";
            }

            //nav arrows
            if ( slideshow.classList.contains('nav-arrows-out') ) {
                styleeditor.selectSliderNavArrows.value = 'nav-arrows-out';
            } else if ( slideshow.classList.contains('nav-arrows-none') ) {
                styleeditor.selectSliderNavArrows.value = 'nav-arrows-none';
            } else {
                styleeditor.selectSliderNavArrows.value = 'nav-arrows-in';
            }
            $(styleeditor.selectSliderNavArrows).trigger('change');

            //nav indicators
            if ( slideshow.classList.contains('nav-indicators-out') ) {
                styleeditor.selectSliderNavIndicators.value = 'nav-indicators-out';
            } else if ( slideshow.classList.contains('nav-indicators-none') ) {
                styleeditor.selectSliderNavIndicators.value = 'nav-indicators-none';
            } else {
                styleeditor.selectSliderNavIndicators.value = 'nav-indicators-in';
            }
            $(styleeditor.selectSliderNavIndicators).trigger('change');

        },

        editMap: function (map) {

            $('a#map_Link').parent().show();

            if ( map.hasAttribute('data-address') ) styleeditor.textareaAddress.value = map.getAttribute('data-address');

            if ( map.hasAttribute('data-info-message') ) styleeditor.textareaInfoMessage.value = map.getAttribute('data-info-message');

            if ( map.hasAttribute('data-zoom') ) styleeditor.inputZoomLevel.value = map.getAttribute('data-zoom');

            if ( map.hasAttribute('data-style') && map.getAttribute('data-style') === 'blackandwhite' ) {
                $(styleeditor.checkBoxMapBW).bootstrapSwitch('state', true, true);
            } else {
                $(styleeditor.checkBoxMapBW).bootstrapSwitch('state', false, true);
            }

        },
		
		/* clone element command from toolbar */
		cloneElementCommand: function() {			
			this.cloneElement();
			this.closeStyleEditor();
			this.deSelectAllCanvasElements();			
		},
		
		/* delete element command from toolbar */
		delElementCommand: function() {
			this.deSelectAllCanvasElements();
			this.deleteElement();
			this.closeStyleEditor();
		},

        /*
            delete selected element
        */
        deleteElement: function() {

            publisher.publish('onBeforeDelete');

            var toDel,
                daddy,
                slideShowDeleted = false;


            //determine what to delete
            if ( styleeditor.activeElement.element.parentNode.parentNode.parentNode.hasAttribute('data-carousel-item') ) {

                toDel = $(styleeditor.activeElement.element.parentNode.parentNode.parentNode);

                slideShowDeleted = true;

            } else if( $(styleeditor.activeElement.element).prop('tagName') === 'A' ) {//ancor

                if( $(styleeditor.activeElement.element).parent().prop('tagName') ==='LI' ) {//clone the LI

                    toDel = $(styleeditor.activeElement.element).parent();

                } else {

                    toDel = $(styleeditor.activeElement.element);

                }

            } else if( $(styleeditor.activeElement.element).prop('tagName') === 'IMG' ) {//image

                if( $(styleeditor.activeElement.element).parent().prop('tagName') === 'A' ) {//clone the A

                    toDel = $(styleeditor.activeElement.element).parent();

                } else {

                    toDel = $(styleeditor.activeElement.element);

                }

            } else if ( styleeditor.activeElement.element.classList.contains('frameCover') ) {//video

                toDel = $(styleeditor.activeElement.element).closest('*[data-component="video"]');

            } else if ( styleeditor.activeElement.element.classList.contains('mapOverlay') ) {

                toDel = $(styleeditor.activeElement.element).closest('*[data-component="map"]');

            } else {//everything else

                toDel = $(styleeditor.activeElement.element);

            }

            //remove empty spaces from parent
            daddy = toDel[0].parentNode;


            toDel.fadeOut(500, function(){

                var randomEl = $(this).closest('body').find('*:first'),
                    daddysDaddy;

                toDel.remove();

                /* SANDBOX */

                var elementID = $(styleeditor.activeElement.element).attr('id');

                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).remove();

                /* END SANDBOX */

                if ( slideShowDeleted && typeof bConfig.rebuildSlideshowNavigation === 'function' ) bConfig.rebuildSlideshowNavigation( $(daddy).closest('.carousel')[0] );

                styleeditor.activeElement.parentBlock.heightAdjustment();

                //we've got pending changes
                siteBuilder.site.setPendingChanges(true);

                if ( daddy.hasAttribute('data-component') && daddy.querySelectorAll('*').length === 0 ) {

                    daddysDaddy = daddy.parentNode;

                    daddy.remove();

                    if (daddysDaddy.querySelectorAll('*').length === 0) daddysDaddy.innerHTML = '';

                } else {

                    if (daddy.querySelectorAll('*').length === 0) daddy.innerHTML = '';

                }



                //if daddy is an empty data-component, delete it
                if ( daddy.hasAttribute('data-component') && daddy.querySelectorAll('*').length === 0 ) daddy.remove();

            });

            $('#deleteElement').modal('hide');

            styleeditor.closeStyleEditor();

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        /*
            clones the selected element
        */
        cloneElement: function() {

            publisher.publish('onBeforeClone');

            var theClone, theClone2, theOne, cloned, elementID, slideShowCloned = false;

            styleeditor.activeElement.removeOutline();

            if( styleeditor.activeElement.element.hasAttribute('data-parent') ) {//clone the parent element

                theClone = $(styleeditor.activeElement.element).parent().clone();
                theClone.find( $(styleeditor.activeElement.element).prop('tagName') ).attr('style', '');

                theClone2 = $(styleeditor.activeElement.element).parent().clone();
                theClone2.find( $(styleeditor.activeElement.element).prop('tagName') ).attr('style', '');

                theOne = theClone.find( $(styleeditor.activeElement.element).prop('tagName') );
                cloned = $(styleeditor.activeElement.element).parent();

            } else if ( styleeditor.activeElement.element.tagName === 'LI' ) {

                theClone = $(styleeditor.activeElement.element).clone();

                theClone2 = $(styleeditor.activeElement.element).clone();

                theOne = theClone;
                cloned = $(styleeditor.activeElement.element);

            } else if ( styleeditor.activeElement.element.tagName === 'INPUT' || styleeditor.activeElement.element.tagName === 'TEXTAREA') {
                theClone = $(styleeditor.activeElement.element.parentElement).clone();

                theClone2 = $(styleeditor.activeElement.element.parentElement).clone();

                theOne = $(styleeditor.activeElement.element);
                cloned = $(styleeditor.activeElement.element.parentElement);
            } else if (styleeditor.activeElement.element.parentNode.parentNode.parentNode.hasAttribute('data-carousel-item')) {

                theClone = $(styleeditor.activeElement.element.parentNode.parentNode.parentNode).clone();

                theClone.removeClass('active');

                theOne = theClone.find( $(styleeditor.activeElement.element).prop('tagName') );

                cloned = $(styleeditor.activeElement.element.parentNode.parentNode.parentNode);

                slideShowCloned = theClone;

            } else if ( styleeditor.activeElement.element.hasAttribute('data-component') && styleeditor.activeElement.element.getAttribute('data-component') === 'grid' ) {

                theClone = $(styleeditor.activeElement.element).closest('*[data-component]').clone();
                theOne = theClone;

                cloned = $(styleeditor.activeElement.element);

            } else if ( $(styleeditor.activeElement.element).closest('*[data-component]')[0] !== undefined ) {

                theClone = $(styleeditor.activeElement.element).closest('*[data-component]').clone();

                if ( $(styleeditor.activeElement.element).closest('*[data-component]').attr('data-component') === 'video' ) {
                    theOne = theClone.find('.frameCover');
                } else {
                    theOne = theClone.find( $(styleeditor.activeElement.element).prop('tagName') );
                }

                cloned = $(styleeditor.activeElement.element).closest('*[data-component]');

            } else {//clone the element itself

                theClone = $(styleeditor.activeElement.element).clone();

                //theClone.attr('style', '');

                /*if( styleeditor.activeElement.sandbox ) {
                    theClone.attr('id', '').uniqueId();
                }*/

                theClone2 = $(styleeditor.activeElement.element).clone();
                //theClone2.attr('style', '');

                /*
                if( styleeditor.activeElement.sandbox ) {
                    theClone2.attr('id', theClone.attr('id'));
                }*/

                theOne = theClone;
                cloned = $(styleeditor.activeElement.element);

            }

            theOne[0].classList.remove('sb_open');

            theClone[0].querySelectorAll('[data-embed-id]').forEach(embed => {
                const oldId = embed.getAttribute('data-embed-id');
                const newID = shortid.generate();
                embed.setAttribute('data-embed-id', newID);
                embed.innerHTML = siteBuilder.site.embeds[oldId];
                siteBuilder.site.embeds[newID] = siteBuilder.site.embeds[oldId];
            });

            cloned.after(theClone);

            /* SANDBOX */

            if( styleeditor.activeElement.sandbox ) {

                elementID = $(styleeditor.activeElement.element).attr('id');
                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).after( theClone2 );

            }

            /* END SANDBOX */

            //make sure the new element gets the proper events set on it
            var newElement = new canvasElement(theOne.get(0));
            newElement.setParentBlock();
            newElement.activate();
            newElement.unsetNoIntent();

            styleeditor.setupCanvasElements( styleeditor.activeElement.parentBlock );

            //possible height adjustments
            if ( typeof styleeditor.activeElement.parentBlock.heightAdjustment === 'function' ) styleeditor.activeElement.parentBlock.heightAdjustment();

            //we've got pending changes
            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

            if ( slideShowCloned && typeof bConfig.rebuildSlideshowNavigation === 'function' ) bConfig.rebuildSlideshowNavigation( $(styleeditor.activeElement.element).closest('.carousel')[0] );

        },


        /*
            resets the active element
        */
        resetElement: function() {

            if( $(styleeditor.activeElement.element).closest('body').width() !== $(styleeditor.activeElement.element).width() ) {

                $(styleeditor.activeElement.element).attr('style', '').css({'outline': '3px dashed red', 'cursor': 'pointer'});

            } else {

                $(styleeditor.activeElement.element).attr('style', '').css({'outline': '3px dashed red', 'outline-offset':'-3px', 'cursor': 'pointer'});

            }

            /* SANDBOX */

            if( styleeditor.activeElement.sandbox ) {

                var elementID = $(styleeditor.activeElement.element).attr('id');
                $('#'+styleeditor.activeElement.sandbox).contents().find('#'+elementID).attr('style', '');

            }

            /* END SANDBOX */

            $('#styleEditor form#stylingForm').height( $('#styleEditor form#stylingForm').height()+"px" );

            $('#styleEditor form#stylingForm .form-group:not(#styleElTemplate)').fadeOut(500, function(){

                $(this).remove();

            });


            //reset icon

            if( styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] !== null ) {

                var get = $.grep(styleeditor.activeElement.element.className.split(" "), function(v, i){

                    return v.indexOf('fa-') === 0;

                }).join();

                $(styleeditor.activeElement.element).removeClass( get ).addClass( styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] );

                $('select#icons option').each(function(){

                    if( $(this).val() === styleeditor._oldIcon[$(styleeditor.activeElement.element).attr('id')] ) {

                        $(this).attr('selected', true);
                        $('#icons').trigger('chosen:updated');

                    }

                });

            }

            setTimeout( function(){styleeditor.buildeStyleElements( $(styleeditor.activeElement.element).attr('data-selector') );}, 550);

            siteBuilder.site.setPendingChanges(true);

            publisher.publish('onBlockChange', styleeditor.activeElement.parentBlock, 'change');

        },


        resetSelectLinksPages: function() {

            $('#internalLinksDropdown').select2('val', '#');

        },

        resetSelectLinksInternal: function() {

            $('#pageLinksDropdown').select2('val', '#');

        },

        resetSelectAllLinks: function() {

            $('#internalLinksDropdown').select2('val', '#');
            $('#pageLinksDropdown').select2('val', '#');
            this.select();

        },

        /*
            hides file upload forms
        */
        hideFileUploads: function() {

            $('form#imageUploadForm').hide();
            $('#imageModal #uploadTabLI').hide();

        },


        /*
            closes the style editor
        */
        closeStyleEditor: function (e) {

            if ( e !== undefined ) e.preventDefault();

            if ( styleeditor.activeElement.editableAttributes && styleeditor.activeElement.editableAttributes.indexOf('content') === -1 ) {
                styleeditor.activeElement.removeOutline();
                styleeditor.activeElement.activate();
            }

            if ( styleeditor.styleEditor.classList.contains('open') ) {

                styleeditor.toggleSidePanel('close');

            }
			
			if ($(".canvasWrapper").css("margin-right") == "285px")
			{
				$(".canvasWrapper").css("margin-right","0");
				$("span.fui-arrow-right").attr('class', 'fui-arrow-left');
				
			}

        },
        /*
            moves the canvas
        */		
        moveCanvas: function () {
			
			if ($(".canvasWrapper").css("margin-right") == "285px")
			{
				$(".canvasWrapper").css("margin-right","0");
				$("span.fui-arrow-right").attr('class', 'fui-arrow-left');
			}
			else
			{
				$(".canvasWrapper").css("margin-right","285px");
				$("span.fui-arrow-left").attr('class', 'fui-arrow-right');
			}
        },		

        /*
            toggles the side panel
        */
        toggleSidePanel: function(val) {

            if ( val === 'open' ) 
			{
				styleeditor.styleEditor.classList.add('open');
				if ($(".canvasWrapper").css("margin-left") == "236px")
					$(".canvasWrapper").css("margin-left","0");
			}
			else if ( val === 'close' ) 
			{
				styleeditor.styleEditor.classList.remove('open');
			}
			
			
            //height adjustment
            setTimeout(function(){
                siteBuilder.site.activePage.heightAdjustment();
            }, 1000);

        },

    };

    styleeditor.init();

    exports.styleeditor = styleeditor;

}());