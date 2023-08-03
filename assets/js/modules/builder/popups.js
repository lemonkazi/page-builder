(function () {
	"use strict";

	let bConfig = require('../config.js');
    let siteBuilder = require('./builder.js');
    let publisher = require('../../vendor/publisher');
    let appUI = require('../shared/ui.js').appUI;
    let utils = require('../shared/utils.js');

	var popups = {

        divPopups: document.getElementById('popups'),
        sideSecondPopupsNav: document.querySelector('*[data-sidesecond="popups"] nav'),
        selectPopupType: document.getElementById('selectPopupType'),
        dzEntryPopup: document.getElementById('entryPopup'),
        dzExitPopup: document.getElementById('exitPopup'),
        dzRegularPopup: document.getElementById('regularPopup'),
        popupDz: document.querySelectorAll('.popupDz'),
        popupDzUl: document.querySelectorAll('.popupDz > ul'),

        frameWrapper: document.getElementById('frameWrapper'),

        buttonBlocks: document.querySelector('button[data-side="blocks"]'),
        buttonTemplates: document.querySelector('button[data-side="templates"]'),


        buttonClosePopups: document.getElementById('buttonClosePopups'),

        activePopupDz: "entry",

        lockPopupMode: false,
        
        init: function () {

            this.listenToDZULChanges();


            /**
                Extends the Block() class with a special delete function for popup Blocks
            **/
            siteBuilder.Block.prototype.deletePopup = function () {

                // Remove from DOM
                this.parentLI.remove();

                let block = this;

                //remove from blocks array
                siteBuilder.site.activePage.popups.forEach( (popup, i) => {
                    if ( popup === block ) {
                        siteBuilder.site.activePage.popups.splice(i,  1);
                    }
                });

                siteBuilder.site.setPendingChanges(true);

            };

            $(popups.selectPopupType).on('change', function () {

                for ( let dz of popups.popupDz ) {
                    dz.classList.remove('visible');
                }
                
                if ( this.value === 'Entry' ) {
                    popups.activePopupDz = 'entry';
                    popups.dzEntryPopup.classList.add('visible');
                } else if ( this.value === 'Exit' ) {
                    popups.activePopupDz = 'exit';
                    popups.dzExitPopup.classList.add('visible');
                } else if ( this.value === 'Regular' ) {
                    popups.activePopupDz = 'regular';
                    popups.dzRegularPopup.classList.add('visible');
                }

                popups.setPopupHeight();

            });

            publisher.subscribe('onStyleEditorOpen', () => {
                popups.lockPopupMode = true;
            });

            popups.buttonClosePopups.addEventListener('click', () => {
                popups.lockPopupMode = false;
                publisher.publish('onSideClose');
            });

            publisher.subscribe('onSideOpen', (side) => {

                popups.lockPopupMode = false;

                // Reveal images
                let images = document.querySelectorAll('*[data-sidesecond="popups"] nav img:not([src])'),
                    img;

                for (img of images) {
                    img.setAttribute('src', img.getAttribute('data-original-src'));
                }
                
                if ( side === 'popups' ) {

                    let height = popups.frameWrapper.offsetHeight;

                    popups.divPopups.style.height = height + "px";
                    popups.divPopups.querySelector('.wrapper').style.height = height + "px";
                    popups.divPopups.classList.add('visible');

                    // disable the Blocks and Template buttons
                    popups.buttonBlocks.setAttribute('disabled', true);
                    if ( popups.buttonTemplates !== null ) popups.buttonTemplates.setAttribute('disabled', true);

                    popups.setPopupHeight();

                } else if ( side !== 'components' ) {

                    popups.divPopups.classList.remove('visible');

                    // enable the Blocks and Template buttons
                    popups.buttonBlocks.removeAttribute('disabled');
                    if ( popups.buttonTemplates !== null ) popups.buttonTemplates.removeAttribute('disabled');

                }

            });

            window.addEventListener('resize', utils.debounce(() => {

                let height = popups.frameWrapper.offsetHeight;
                popups.divPopups.style.height = height + "px";
                popups.divPopups.querySelector('.wrapper').style.height = height + "px";

            }, 500, false), false);

            publisher.subscribe('onSideClose', () => {

                if ( !popups.lockPopupMode ) {

                    popups.divPopups.classList.remove('visible');

                    // enable the Blocks and Template buttons
                    popups.buttonBlocks.removeAttribute('disabled');
                    if ( popups.buttonTemplates !== null ) popups.buttonTemplates.removeAttribute('disabled');

                }

            });

            publisher.subscribe('createPopupsSidebar', (catName, thePopups) => {

                popups.createPopupsSidebar(catName, thePopups);

            });

            publisher.subscribe('onChangePage', (page) => {

                // Make popups for the current page visible, hide others
                siteBuilder.site.sitePages.forEach((ipage) => {

                    ipage.popups.forEach((popup) => {

                        if ( ipage === page ) {
                            // active page
                            popup.parentLI.classList.remove('hidden');

                        } else {
                            // not active page
                            popup.parentLI.classList.add('hidden');
                        }

                    });

                });

                // Reset placeholders
                for ( let ul of popups.popupDzUl ) {
                    popups.resetPlaceholders(ul);
                }

            });

            $(popups.popupDzUl).sortable({
                revert: true,
                placeholder: "drop-hover-popup",
                handle: '.dragBlock',
                cancel: '',
                beforeStop: (event, ui) => {

                    // pages can have only a single entry or exit popup
                    if ( popups.activePopupDz === 'entry' ) {

                        siteBuilder.site.activePage.popups.forEach((popup) => {

                            // If this page already has entry popup, let's delete it
                            if ( popup.popupType === 'entry' ) popup.deletePopup();

                        });

                    } else if ( popups.activePopupDz === 'exit' ) {

                        siteBuilder.site.activePage.popups.forEach((popup) => {

                            // If this page already has exit popup, let's delete it
                            if ( popup.popupType === 'exit' ) popup.deletePopup();

                        });

                    }

                    //new Popup
                    let newBlock = new siteBuilder.Block('popup');

                    newBlock.popupType = popups.activePopupDz;
                    newBlock.popupReoccurrence = "All";
                    newBlock.popupDelay = 0;
                    newBlock.popupID = utils.randomString(10);

                    newBlock.placeOnCanvas(ui);

                },
                receive: (event, ui) => {

                }
            });

        },

        /**
            Checks for the provided UL if any popups are visible and determines whether or not to display the dz placeholder
        **/
        resetPlaceholders: function (ul) {

            let LIs = ul.querySelectorAll('li'),
                allHidden = true;

            for ( let li of LIs ) {

                let style = window.getComputedStyle(li);

                if ( style.display !== 'none' ) {
                    allHidden = false;
                }

            }

            if ( allHidden ) ul.nextElementSibling.style.display = 'flex';
            else ul.nextElementSibling.style.display = 'none';

        },

        /**
            Sets up listening for changes to the dz UL's
        **/
        listenToDZULChanges: function () {

            // Options for the observer (which mutations to observe)
            var config = { attributes: false, childList: true, subtree: false };

            // Callback function to execute when mutations are observed
            var callback = function(mutationsList, observer) {
                for(var mutation of mutationsList) {
                    if (mutation.type == 'childList') {

                        popups.resetPlaceholders(mutation.target);

                    }
                }
            };

            for ( let t of popups.popupDzUl ) {

                // Create an observer instance linked to the callback function
                var observer = new MutationObserver(callback);

                // Start observing the target node for configured mutations
                observer.observe(t, config);

                // Later, you can stop observing
                //observer.disconnect();

            }

        },


        setPopupHeight: function () {

            siteBuilder.site.activePage.popups.forEach((popup) => {

                if ( popup.popupType === popups.activePopupDz ) popup.heightAdjustment();

            });

        },


        createPopupsSidebar: function (catName, thePopups) {

            let newItem,
                x,
                popupsUL = document.createElement('UL');
			let blockedIcon =  '<a data-toggle="modal" class="btn btn-info btn-sm unavailableBlockIcon"><i class="fa fa-lock" aria-hidden="true"></i></a>';
			
            for( x = 0; x < thePopups.length; x++ ) {

				let blocksUrl = thePopups[x].blocks_url === '' ? '' : 'data-srcc="' + appUI.baseUrl + thePopups[x].blocks_url + '" ';
				
				
                newItem = $('<li><img data-original-src="' + appUI.baseUrl + thePopups[x].blocks_thumb + '" ' + blocksUrl + 'data-height="' + thePopups[x].blocks_height + '"></li>');
				
				if(blocksUrl === '')	
			    {
				  $(newItem).append(blockedIcon);
				  $(newItem).children('img').addClass('disabledBuilderElement');				 
			    }
				
                popupsUL.appendChild(newItem[0]);

            }

            popups.sideSecondPopupsNav.appendChild(popupsUL);

            // Make the popups draggable onto the canvas
            $('li', popups.sideSecondPopupsNav).each(function () {
				
				if(!$(this).find('img').attr('data-srcc'))
				{
				    return;
				}
				
                $(this).draggable({
                    helper: function() {
                        return $('<div style="height: 100px; width: 300px; background: #F9FAFA; box-shadow: 5px 5px 1px rgba(0,0,0,0.1); text-align: center; line-height: 100px; font-size: 28px; color: #16A085; z-index: 99999"><span class="fui-list"></span></div>');
                    },
                    revert: 'invalid',
                    appendTo: 'body',
                    connectToSortable: '#popups .popupDz > ul',
                    start: function (event, ui) {

                        siteBuilder.site.activePage.transparentOverlay('on');
                        
                        // Entry and Exit popups are only allowd one per page
                        if ( popups.activePopupDz === 'entry' || popups.activePopupDz === 'exit' ) {

                            let popupLIs;

                            if ( popups.activePopupDz === 'entry' ) popupLIs = popups.dzEntryPopup.querySelectorAll(':not(.hidden)[data-page-id="' + siteBuilder.site.activePage.pageID + '"]');
                            else if ( popups.activePopupDz === 'exit' ) popupLIs = popups.dzExitPopup.querySelectorAll(':not(.hidden)[data-page-id="' + siteBuilder.site.activePage.pageID + '"]');

                            for ( let popupIL of popupLIs ) {
                                popupIL.style.display = 'none';
                            }

                        }

                    },
                    stop: function (event, ui) {

                        siteBuilder.site.activePage.transparentOverlay('off');
                        
                        // If case of a cancelled DnD, we'll need to re-show the existing popup
                        if ( popups.activePopupDz === 'entry' || popups.activePopupDz === 'exit' ) {

                            let popupLIs;

                            if ( popups.activePopupDz === 'entry' ) popupLIs = popups.dzEntryPopup.querySelectorAll(':not(.hidden)[data-page-id="' + siteBuilder.site.activePage.pageID + '"]');
                            else if ( popups.activePopupDz === 'exit' ) popupLIs = popups.dzExitPopup.querySelectorAll(':not(.hidden)[data-page-id="' + siteBuilder.site.activePage.pageID + '"]');
                            
                            for ( let popupIL of popupLIs ) {
                                popupIL.style.display = 'block';
                            }

                        }

                    },
                    drag: function (event, ui) {

                        if ( ui.originalPosition.top - ui.offset.top > 100 ) {

                            var st = parseInt($(this).data("startingScrollTop"));
                            ui.position.top -= st;

                        }
                        
                    }
                });

            });

        }
    
    };

    popups.init();

}());