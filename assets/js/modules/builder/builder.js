(function() {
  'use strict';

  let siteBuilderUtils = require('../shared/utils.js');
  let bConfig = require('../config.js');
  let appUI = require('../shared/ui.js').appUI;
  let publisher = require('../../vendor/publisher');
  let ace = require('brace');
  let utils = require('../shared/utils.js');
  let notify = require('../shared/notify');
  const shortid = require('shortid');

  /*
        Basic Builder UI initialisation
    */
  var builderUI = {
    allBlocks: {}, //holds all blocks loaded from the server
    primarySideMenuWrapper: document.getElementById('main'),
    buttonBack: document.getElementById('backButton'),
    buttonBackConfirm: document.getElementById('leavePageButton'),

    aceEditors: {},
    frameContents: '', //holds frame contents
    templateID: 0, //holds the template ID for a page (???)

    modalDeleteBlock: document.getElementById('deleteBlock'),
    modalResetBlock: document.getElementById('resetBlock'),
    modalDeletePage: document.getElementById('deletePage'),
    buttonDeletePageConfirm: document.getElementById('deletePageConfirm'),
	buttonDeleteElementConfirm: document.getElementById('deleteElementConfirm'),
    dropdownPageLinks: document.getElementById('internalLinksDropdown'),

    pageInUrl: null,

    tempFrame: {},

    currentResponsiveMode: {},

    sideSecondBlocksNav: document.querySelector(
      '*[data-sidesecond="blocks"] nav'
    ),
    sideSecondComponentsNav: document.querySelector(
      '*[data-sidesecond="components"] nav'
    ),

    init: function() {
      if (document.body.classList.contains('builderUI')) {
        builderUI.loadBlocksComponents();
		//add 'blocked' icon for unavailable blocks
		setTimeout(function() {
			builderUI.addBlockedIcon();
		},0);
      }

      //prevent click event on ancors in the block section of the sidebar
      $(this.primarySideMenuWrapper).on(
        'click',
        'a:not(.actionButtons)',
        function(e) {
          e.preventDefault();
        }
      );

      $(this.buttonBack).on('click', this.backButton);
      $(this.buttonBackConfirm).on('click', this.backButtonConfirm);

      $(builderUI.sideSecondBlocksNav).on(
        'click',
        'li a.delFavBlock',
        function() {
          builderUI.deleteFavBlock(this.getAttribute('data-block-id'));

          return false;
        }
      );

      //notify the user of pending chnages when clicking the back button
      $(window).bind('beforeunload', function() {
        if (site.pendingChanges === true) {
          return "Your site contains changed which haven't been saved yet. Are you sure you want to leave?";
        }
      });
	  //confirm delete element button
	  $(this.buttonDeleteElementConfirm).on('click', function(){ publisher.publish('deleteElement');} );
      //URL parameters
      builderUI.pageInUrl = siteBuilderUtils.getParameterByName('p');

      publisher.subscribe('onBeforeSave', function() {
        if (typeof bConfig.onBeforeSave === 'function') bConfig.onBeforeSave();
      });

      window.addEventListener('message', receiveMessage, false);

      function receiveMessage(event) {
        if (event.data === 'onFrameContentChanged') {
          site.setPendingChanges(true);
        }
        if (event.data === 'onHeightChange') {
          site.activePage.heightAdjustment();
        }
      }

      publisher.subscribe('canvasWidthChanged', function() {
        site.activePage.heightAdjustment();
      });

      publisher.subscribe('onPendingChanges', function() {
        site.setPendingChanges(true);
      });

      publisher.subscribe('onResponsiveViewChange', function(mode) {
        if (site.activePage.blocks === undefined) return false;

        site.activePage.blocks.forEach(function(block) {
          if (mode === 'mobile') block.hideFrameCover();
          else block.unhideFrameCover();
        });
      });
	  
	  /*setTimeout(function() {
		builderUI.setNumberAvailablePages(); 
	  },100);*/

    },

    /*
            Loads blocks and components off the server and creates both sidebars
        */
    loadBlocksComponents: function(blocks = true, components = true) {
      //load blocks
      $.getJSON(appUI.baseUrl + 'builder_elements/loadAll', function(data) {
        builderUI.allBlocks = data;
        if (blocks) builderUI.implementBlocks();
        if (components) builderUI.implementComponents();
        publisher.publish('onSidebarDataReady');
      });
    },
	
	setNumberAvailablePages: function() {
	  $.ajax({
		url: appUI.siteUrl + 'builder_elements/availablePages',
		type: 'post',
		dataType: 'json'
	  })
	  .done(function(ret) {
			let availablePages = ret.available_pages === -1 ? '&#8734;' : ret.available_pages;
			$('#pages_counter').html( site.sitePages.length + " / " + availablePages );
			
			if(ret.available_pages - site.sitePages.length > 0)
				$('#addPage').removeClass('disabled');
			else if(ret.available_pages !== -1)
				$('#addPage').addClass('disabled');	
						  
	  })
	  .fail(function(jqXHR, textStatus, errorThrown) {
		  if (
			jqXHR.getResponseHeader('Refresh') !== null &&
			jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
		  ) {
			site.setPendingChanges(false);
			location.href =
			  appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
		  }
	  });
	},	
    /*
            builds the blocks into the site bar
        */
    implementBlocks: function() {
      var category,
        catCounter = 0,
        niceKey,
        catButton,
        catButtonSpan,
        catButtonSVG,
		catIconLocked,
        x,
        blockUL,
        newItem,
        allBlocks = [];

      // Empty out the category navigation
      this.sideSecondBlocksNav.innerHTML = '';

      if (typeof this.allBlocks.elements === 'undefined') {
        // No blocks were send from the server, let's remove block related stuff
        $('button[data-side="blocks"]').remove();
        $('div[data-sidesecond="blocks"]').remove();
      }

      for (category in this.allBlocks.elements) {
        // Skip the "Popups category"
        if (category.toLowerCase() === 'popups') {
          publisher.publish(
            'createPopupsSidebar',
            category,
            this.allBlocks.elements[category]
          );
          continue;
        }

        //create the category button
        niceKey = category.toLowerCase().replace(/ /g, '_');

        catButton = document.createElement('BUTTON');
        catButtonSpan = document.createElement('SPAN');

        catButtonSpan.innerText = category;

        catButton.appendChild(catButtonSpan);

        catButtonSVG = siteBuilderUtils.htmlToElement(bConfig.sideMenuArrowSVG);
		
		catIconLocked = siteBuilderUtils.htmlToElement(bConfig.sideMenuCatBlockedIcon);
		

		for (x = 0; x < this.allBlocks.elements[category].length; x++) {
			if(this.allBlocks.elements[category][x].blocks_url !== '')
				break;
		}
		
		if(x !== this.allBlocks.elements[category].length || catCounter === 0)
			catButton.appendChild(catButtonSVG);
		else
			catButton.appendChild(catIconLocked);
			
		
        //catButton.appendChild(catButtonSVG);

        this.sideSecondBlocksNav.appendChild(catButton);

        //create the block thumbnails

        blockUL = document.createElement('UL');

        if (catCounter === 0) {
          blockUL.setAttribute(
            'data-no-fav-blocks',
            builderUI.sideSecondBlocksNav.getAttribute('data-no-fav-blocks')
          );
        }

        for (x = 0; x < this.allBlocks.elements[category].length; x++) {
          //console.log(this.allBlocks.elements[category][x]);

          if ( this.allBlocks.elements[category][x].blocks_url.indexOf('popup') !== -1 ) continue;
		  
		  let blocksUrl = this.allBlocks.elements[category][x].blocks_url;		  
		  let blockedIcon =  '<a data-toggle="modal" class="btn btn-info btn-sm unavailableBlockIcon"><i class="fa fa-lock" aria-hidden="true"></i></a>';
		  
		  if(blocksUrl !== '')
		  {
		    blocksUrl = 'data-srcc="' + appUI.baseUrl + blocksUrl + '" ';
		  }
			  		  
          if (catCounter === 0) {
			
            newItem = $(
              '<li><a href="" class="delFavBlock" data-block-id="' +
                this.allBlocks.elements[category][x].blocks_id +
                '"><i class="fui-cross-circle"></i></a><img data-original-src="' +
                appUI.baseUrl +
                this.allBlocks.elements[category][x].blocks_thumb +
				'" ' + 
                blocksUrl +															 
                'data-height="' +
                this.allBlocks.elements[category][x].blocks_height +
                '"></li>'
            );
          } else {
            newItem = $(
              '<li><img data-original-src="' +
                appUI.baseUrl +
                this.allBlocks.elements[category][x].blocks_thumb +
				'" ' + 
                blocksUrl +													 
                'data-height="' +
                this.allBlocks.elements[category][x].blocks_height +
                '"></li>'
            );
          }
		  
		  if(blocksUrl === '')
		  {
			 $(newItem).append(blockedIcon);
		     $(newItem).children('img').addClass('disabledBuilderElement');
			 			 
		  }
		  
          blockUL.appendChild(newItem[0]);
        }

        this.sideSecondBlocksNav.appendChild(blockUL);

        catCounter++;
      }

      //draggables
      builderUI.makeDraggable();
    },

    /*
            Builds the components into the sidebar
        */
    implementComponents: function() {
      var newItem,
        category,
        niceKey,
        catButton,
        catButtonSpan,
        catButtonSVG,
        x,
        componentsUL;

      //for( category in this.allBlocks.components ) {

      var key =
        typeof this.allBlocks.elements === 'undefined'
          ? Object.keys(this.allBlocks)[0]
          : Object.keys(this.allBlocks)[1];

      for (category in this.allBlocks[key]) {
        //create the category button
        niceKey = category.toLowerCase().replace(' ', '_');

        catButton = document.createElement('BUTTON');
        catButtonSpan = document.createElement('SPAN');

        catButtonSpan.innerText = category;

        catButton.appendChild(catButtonSpan);

        catButtonSVG = siteBuilderUtils.htmlToElement(bConfig.sideMenuArrowSVG);

        catButton.appendChild(catButtonSVG);

        this.sideSecondComponentsNav.appendChild(catButton);

        //create the block thumbnails

        componentsUL = document.createElement('UL');

        for (x = 0; x < this.allBlocks[key][category].length; x++) {
          newItem = $(
            '<li class="component ' +
              niceKey +
              '"><img data-original-src="' +
              appUI.baseUrl +
              this.allBlocks[key][category][x].components_thumb +
              '" data-height="' +
              this.allBlocks[key][category][x].components_height +
              '"></li>'
          );

          newItem
            .find('img')
            .attr(
              'data-insert-html',
              this.allBlocks[key][category][x].components_markup
            );

          componentsUL.appendChild(newItem[0]);
        }

        this.sideSecondComponentsNav.appendChild(componentsUL);
      }
    },

    /*
            event handler for when the back link is clicked
        */
    backButton: function() {
      if (site.pendingChanges === true) {
        $('#backModal').modal('show');
        return false;
      }
    },
	/*
		Adds 'blocked' icon for all unavailable blocks for the user
	*/
	addBlockedIcon: function(){
		let ulBlocks = document.querySelectorAll('[data-sidesecond="blocks"] .sideSecondInner ul');
		$(ulBlocks).find('li').each(function(){
			let blockedIcon =  $('<a data-toggle="modal" class="btn btn-info btn-sm"><i class="fa fa-lock" aria-hidden="true"></i></a>');
			$(this).append( blockedIcon );
			
		});		
		/*  if(blocksUrl === '') {
		let blockedIcon = '<a data-toggle="modal" class="btn btn-info btn-sm"><i class="fa fa-lock" aria-hidden="true"></i></a>';
		
		newItem += blockedIcon;
		  
	  }*/
	},
    /*
            button for confirming leaving the page
        */
    backButtonConfirm: function() {
      site.pendingChanges = false; //prevent the JS alert after confirming user wants to leave
    },

    /*
            makes the blocks and templates in the sidebar draggable onto the canvas
        */
    makeDraggable: function() {
      $('[data-sidesecond="blocks"] ul li, #templates li').each(function() {
		 if(!$(this).find('img').attr('data-srcc'))
		 {
			 return;
		 }
			
        $(this).draggable({
          helper: function() {
            return $(
              '<div style="height: 100px; width: 300px; background: #F9FAFA; box-shadow: 5px 5px 1px rgba(0,0,0,0.1); text-align: center; line-height: 100px; font-size: 28px; color: #16A085"><span class="fui-list"></span></div>'
            );
          },
          revert: 'invalid',
          appendTo: 'body',
          connectToSortable: '#pageList > ul:visible',
          start: function(event, ui) {
            site.activePage.transparentOverlay('on');
            $(this).data('startingScrollTop', window.pageYOffset);
          },
          stop: function(event, ui) {
            site.activePage.transparentOverlay('off');
          },
          drag: function(event, ui) {
            if (ui.originalPosition.top - ui.offset.top > 100) {
              var st = parseInt($(this).data('startingScrollTop'));
              ui.position.top -= st;
            }
          }
        });
      });

      $('#elements li a').each(function() {
        $(this)
          .unbind('click')
          .bind('click', function(e) {
            e.preventDefault();
          });
      });
    },

    /*
            Implements the site on the canvas, called from the Site object when the siteData has completed loading
        */
    populateCanvas: function() {
      var i,
        counter = 1;

      //loop through the pages

      for (i in site.pages) {
        var newPage = new Page(i, site.pages[i], counter);

        counter++;

        //set this page as active?
        if (builderUI.pageInUrl === i) {
          newPage.selectPage();
        }
      }

      //activate the first page
      if (site.sitePages.length > 0 && builderUI.pageInUrl === null) {
        site.sitePages[0].selectPage();
      }
    },

    /*
            Canvas loading on/off
        */
    canvasLoading: function(value) {
      if (
        value === 'on' &&
        document
          .getElementById('frameWrapper')
          .querySelectorAll('#canvasOverlay').length === 0
      ) {
        var overlay = document.createElement('DIV');

        overlay.style.display = 'flex';
        $(overlay).hide();
        overlay.id = 'canvasOverlay';

        overlay.innerHTML =
          '<div class="loader"><span>{</span><span>}</span></div>';

        document.getElementById('frameWrapper').appendChild(overlay);

        $('#canvasOverlay').fadeIn(500);
      } else if (
        value === 'off' &&
        document
          .getElementById('frameWrapper')
          .querySelectorAll('#canvasOverlay').length === 1
      ) {
        site.loaded();

        $('#canvasOverlay').fadeOut(500, function() {
          this.remove();
        });
      }
    },

    /*
            Deletes a favourite block
        */
    deleteFavBlock: function(blockID) {
      $.ajax({
        url: appUI.siteUrl + 'sites/favourite_block_del/' + blockID,
        type: 'post',
        dataType: 'json'
      })
        .done(function(ret) {
          if (ret.responseCode === 1) {
            $(
              'a[data-block-id="' + blockID + '"]',
              builderUI.sideSecondBlocksNav
            )
              .parent()
              .fadeOut(function() {
                this.remove();
              });
          }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          if (
            jqXHR.getResponseHeader('Refresh') !== null &&
            jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
          ) {
            site.setPendingChanges(false);
            location.href =
              appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
          }
        });
    }
  };

  /*
        Page constructor
    */
  function Page(pageName, page, counter) {
    this.name = pageName || '';
    this.pageID = page.page_id || 0;
    this.blocks = [];
    this.popups = [];
    this.parentUL = {}; //parent UL on the canvas
    this.status = ''; //'', 'new' or 'changed'
    this.scripts = []; //tracks script URLs used on this page

    this.pageSettings = {
      title: page.pages_title || '',
      meta_description: page.meta_description || '',
      meta_keywords: page.meta_keywords || '',
      header_includes: page.header_includes || '',
      page_css: page.page_css || '',
      google_fonts: page.google_fonts || []
    };

    this.pageMenuTemplate = `<a href="" class="menuItemLink">page</a>
            <span class="pageButtons">
                <button class="btn btn-xs btn-primary fileEdit fui-new"></button>
                <button class="btn btn-xs btn-danger fileDel fui-cross"></button>
                <button class="btn btn-xs btn-primary fileSave fui-check" href="#"></button>
            </span>`;

    this.menuItem = {}; //reference to the pages menu item for this page instance
    this.linksDropdownItem = {}; //reference to the links dropdown item for this page instance

    this.parentUL = document.createElement('UL');
    this.parentUL.setAttribute('id', 'page' + counter);

    /*
            makes the clicked page active
        */
    this.selectPage = function() {
      //console.log('select:');
      //console.log(this);

      // build blocks
      if (
        this.parentUL.children.length === 0 &&
        page.hasOwnProperty('blocks')
      ) {
        for (var x = 0; x < page.blocks.length; x++) {
          //create new Block

          var newBlock = new Block('block');

          page.blocks[x].src =
            appUI.siteUrl + 'sites/getframe/' + page.blocks[x].frames_id;

          newBlock.frameID = page.blocks[x].frames_id;
          newBlock.page = this;
          if (page.blocks[x].frames_global === '1') newBlock.global = true;
          newBlock.createParentLI(page.blocks[x].frames_height);
          newBlock.createFrame(page.blocks[x]);
          newBlock.createFrameCover();
          newBlock.insertBlockIntoDom(this.parentUL);

          //add the block to the new page
          this.blocks.push(newBlock);
        }

        if (page.popups !== undefined) {
          for (var y = 0; y < page.popups.length; y++) {
            //create new Block

            var newPopupBlock = new Block('block');

            newPopupBlock.type = 'popup';
            newPopupBlock.popupType = page.popups[y].frames_popup;

            if (page.popups[y].frames_settings) {
              let additionalSettings = JSON.parse(
                page.popups[y].frames_settings
              );

              for (let key in additionalSettings) {
                newPopupBlock[key] = additionalSettings[key];
              }
            }

            page.popups[y].src =
              appUI.siteUrl + 'sites/getframe/' + page.popups[y].frames_id;

            newPopupBlock.frameID = page.popups[y].frames_id;
            newPopupBlock.page = this;
            newPopupBlock.createParentLI(page.popups[y].frames_height);
            newPopupBlock.parentLI.setAttribute('data-page-id', this.pageID);
            newPopupBlock.createFrame(page.popups[y]);
            newPopupBlock.createFrameCover();

            if (newPopupBlock.popupType === 'entry')
              newPopupBlock.insertBlockIntoDom(
                document.querySelector('#entryPopup > ul')
              );
            else if (newPopupBlock.popupType === 'exit')
              newPopupBlock.insertBlockIntoDom(
                document.querySelector('#exitPopup > ul')
              );
            else if (newPopupBlock.popupType === 'regular')
              newPopupBlock.insertBlockIntoDom(
                document.querySelector('#regularPopup > ul')
              );

            this.popups.push(newPopupBlock);
          }
        }
      }

      //mark the menu item as active
      site.deActivateAll();
      $(this.menuItem).addClass('active');

      //let Site know which page is currently active
      site.setActive(this);

      //display the name of the active page on the canvas
      site.pageTitle.innerHTML = this.name;

      //load the page settings into the page settings modal
      site.spanPageName.innerText = this.name + '.html';
      site.inputPageSettingsTitle.value = this.pageSettings.title;
      site.inputPageSettingsMetaDescription.value = this.pageSettings.meta_description;
      site.inputPageSettingsMetaKeywords.value = this.pageSettings.meta_keywords;
      site.inputPageSettingsIncludes.value = this.pageSettings.header_includes;
      site.inputPageSettingsPageCss.value = this.pageSettings.page_css;

      //google fonts
      $(site.inputPageSettingsGoogleFonts).tagsinput('removeAll');
      site.activePage.pageSettings.google_fonts.forEach(function(font) {
        $(site.inputPageSettingsGoogleFonts).tagsinput('add', font.nice_name);
      });

      //trigger custom event
      $('body').trigger('changePage');

      publisher.publish('onChangePage', this);

      //reset the heights for the blocks on the current page
      for (var i in this.blocks) {
        if (Object.keys(this.blocks[i].frameDocument).length > 0) {
          this.blocks[i].heightAdjustment();
        }
      }

      //show the empty message?
      this.isEmpty();

      return false;
    };

    /*
            changed the location/order of a block within a page
        */
    this.setPosition = function(frameID, newPos) {
      //we'll need the block object connected to iframe with frameID

      for (var i in this.blocks) {
        if (this.blocks[i].frame.getAttribute('id') === frameID) {
          //change the position of this block in the blocks array
          this.blocks.splice(newPos, 0, this.blocks.splice(i, 1)[0]);
        }
      }
    };

    /*
            Locates the proper Block object using frameID and publishes the load event
        */
    this.fireBlockLoadEvent = function(frameID) {
      for (var i in this.blocks) {
        if (this.blocks[i].frame.getAttribute('id') === frameID) {
          publisher.publish('onBlockLoaded', this.blocks[i]);
        }
      }
    };

    /*
            delete block from blocks array
        */
    this.deleteBlock = function(block) {
      //remove from blocks array
      for (var i in this.blocks) {
        if (this.blocks[i] === block) {
          //found it, remove from blocks array
          this.blocks.splice(i, 1);
        }
      }

      site.setPendingChanges(true);
    };

    /*
            Places a transparent DIV over the frames on the page
        */
    this.transparentOverlay = function(onOrOff = 'on') {
      for (var i in this.blocks) {
        this.blocks[i].transparentOverlay(onOrOff);
      }

      for (var j in this.popups) {
        this.popups[j].transparentOverlay(onOrOff);
      }
    };

    /*
            setup for editing a page name
        */
    this.editPageName = function() {
      if (!this.menuItem.classList.contains('edit')) {
        //hide the link
        this.menuItem.querySelector('a.menuItemLink').style.display = 'none';

        //insert the input field
        var newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.setAttribute('name', 'page');
        newInput.setAttribute('value', this.name);
        this.menuItem.insertBefore(newInput, this.menuItem.firstChild);

        newInput.focus();

        var tmpStr = newInput.getAttribute('value');
        newInput.setAttribute('value', '');
        newInput.setAttribute('value', tmpStr);

        this.menuItem.classList.add('edit');
      }
    };

    /*
            Updates this page's name (event handler for the save button)
        */
    this.updatePageNameEvent = function(el) {
      if (this.menuItem.classList.contains('edit')) {
        //el is the clicked button, we'll need access to the input
        var theInput = this.menuItem.querySelector('input[name="page"]');

        //make sure the page's name is OK
        if (site.checkPageName(theInput.value)) {
          this.name = site.prepPageName(theInput.value);

          this.menuItem.querySelector('input[name="page"]').remove();
          this.menuItem.querySelector('a.menuItemLink').innerHTML = this.name;
          this.menuItem.querySelector('a.menuItemLink').style.display = 'block';

          this.menuItem.classList.remove('edit');

          //update the links dropdown item
          this.linksDropdownItem.text = this.name;
          this.linksDropdownItem.setAttribute('value', this.name + '.html');

          //update the page name on the canvas
          site.pageTitle.innerHTML = this.name;
          console.log("=================");
          console.log("sssssssss");

          //changed page title, we've got pending changes
          site.setPendingChanges(true);
        } else {
          alert(site.pageNameError);
        }
      }
    };

    /*
            deletes this entire page
        */
    this.delete = function() {
      //delete from the Site
      for (var i in site.sitePages) {
        if (site.sitePages[i] === this) {
          //got a match!

          //delete from site.sitePages
          site.sitePages.splice(i, 1);

          //delete from canvas
          this.parentUL.remove();

          //add to deleted pages
          site.pagesToDelete.push(this.name);

          //delete the page's menu item
          this.menuItem.remove();

          //delet the pages link dropdown item
          this.linksDropdownItem.remove();

          //activate the first page
          site.sitePages[0].selectPage();

          //page was deleted, so we've got pending changes
          site.setPendingChanges(true);
        }
      }
    };

    /*
            checks if the page is empty, if so show the 'empty' message
        */
    this.isEmpty = function() {
      if (this.blocks.length === 0) {
        site.messageStart.style.display = 'block';
        site.divFrameWrapper.classList.add('empty');
      } else {
        site.messageStart.style.display = 'none';
        site.divFrameWrapper.classList.remove('empty');
      }
    };

    /*
            preps/strips this page data for a pending ajax request
        */
    this.prepForSave = function() {
      this.detectGoogleFonts();

      var page = {};

      page.name = this.name;
      page.pageSettings = this.pageSettings;
      page.status = this.status;
      page.pageID = this.pageID;
      page.blocks = [];
      page.popups = [];

      //process the blocks

      for (var x = 0; x < this.blocks.length; x++) {
        var block = {};

        if (typeof bConfig.inBlockBeforeSave === 'function')
          bConfig.inBlockBeforeSave(this.blocks[x].frameDocument);

        //dump possible Google Map links from the heads
        $('head', this.blocks[x].frameDocument)
          .find('script[src*="maps.googleapis.com"]')
          .remove();
        const blockSource = this.blocks[x].getSource();

        block.frames_embeds = blockSource.embeds;
        block.frameContent = blockSource.source;
        block.sandbox = false;
        block.loaderFunction = '';

        block.frameHeight = this.blocks[x].frameHeight;
        block.originalUrl = this.blocks[x].originalUrl;
        if (this.blocks[x].global) block.frames_global = true;

        page.blocks.push(block);
      }

      // process popups
      for (var y = 0; y < this.popups.length; y++) {
        let popup = {};

        if (typeof bConfig.inBlockBeforeSave === 'function')
          bConfig.inBlockBeforeSave(this.popups[y].frameDocument);

        //dump possible Google Map links from the heads
        $('head', this.popups[y].frameDocument)
          .find('script[src*="maps.googleapis.com"]')
          .remove();

        const popupSource = this.popups[y].getSource();
        popup.frameContent = popupSource.source;
        popup.frames_embeds = popupSource.embeds;
        popup.sandbox = false;
        popup.loaderFunction = '';

        popup.frameHeight = this.popups[y].frameHeight;
        popup.originalUrl = this.popups[y].originalUrl;
        if (this.popups[y].global) popup.frames_global = true;

        let additionalSettings = {};

        if (typeof this.popups[y].popupReoccurrence !== 'undefined')
          additionalSettings.popupReoccurrence = this.popups[
            y
          ].popupReoccurrence;
        else additionalSettings.popupReoccurrence = 'All';

        if (typeof this.popups[y].popupDelay !== 'undefined')
          additionalSettings.popupDelay = this.popups[y].popupDelay;
        else additionalSettings.popupDelay = 0;

        if (typeof this.popups[y].popupID !== 'undefined')
          additionalSettings.popupID = this.popups[y].popupID;
        else additionalSettings.popupID = utils.randomString(10);

        if (Object.keys(additionalSettings).length !== 0)
          popup.additionalSettings = additionalSettings;

        popup.type = this.popups[y].popupType;

        page.popups.push(popup);
      }

      return page;
    };

    /*
            generates the full page, using skeleton.html
        */
    this.fullPage = function() {
      var page = this; //reference to self for later
      page.scripts = []; //make sure it's empty, we'll store script URLs in there later

      var newDocMainParent = $('iframe#skeleton')
        .contents()
        .find(bConfig.pageContainer);
      var newDocMainParentPopups = $('iframe#skeleton')
        .contents()
        .find('#popups');

      //empty out the skeleton first
      $('iframe#skeleton')
        .contents()
        .find(bConfig.pageContainer)
        .html('');
      $('iframe#skeleton')
        .contents()
        .find('#popups')
        .html('');

      //remove old script tags
      $('iframe#skeleton')
        .contents()
        .find('script')
        .each(function() {
          //$(this).remove();
        });

      var theContents;

      for (var i in this.blocks) {
        //grab the block content
        if (this.blocks[i].sandbox !== false) {
          theContents = $('#sandboxes #' + this.blocks[i].sandbox)
            .contents()
            .find(bConfig.pageContainer)
            .clone();
        } else {
          theContents = $(this.blocks[i].frameDocument.body)
            .find(bConfig.pageContainer)
            .clone();
        }

        //remove video frameCovers
        theContents.find('.frameCover').each(function() {
          $(this).remove();
        });

        // remove .resize-triggers divs
        theContents.find('.resize-triggers').each(function() {
          $(this).remove();
        });

        theContents[0]
          .querySelectorAll('script')
          .forEach(script => script.remove());

        //remove style leftovers from the style editor
        for (var key in bConfig.editableItems) {
          theContents.find(key).each(function() {
            $(this).removeAttr('data-selector');

            $(this).css('outline', '');
            $(this).css('outline-offset', '');
            $(this).css('cursor', '');

            if ($(this).attr('style') === '') {
              $(this).removeAttr('style');
            }
          });
        }

        //append to DOM in the skeleton
        newDocMainParent.append($(theContents.html()));

        //remove background images in parallax blocks
        newDocMainParent.find('*[data-parallax]').each(function() {
          this.style.backgroundImage = '';
        });

        //remove draggable attributes
        newDocMainParent.find('*[draggable]').each(function() {
          this.removeAttribute('draggable');
        });
      }

      // Process popups
      for (var j in this.popups) {
        theContents = $(this.popups[j].frameDocument.body)
          .find('.modal .modal-body > div:first-child')
          .clone();

        //remove video frameCovers
        theContents.find('.frameCover').each(function() {
          $(this).remove();
        });

        theContents[0]
          .querySelectorAll('script')
          .forEach(script => script.remove());

        //remove style leftovers from the style editor
        for (key in bConfig.editableItems) {
          theContents.find(key).each(function() {
            $(this).removeAttr('data-selector');

            $(this).css('outline', '');
            $(this).css('outline-offset', '');
            $(this).css('cursor', '');

            if ($(this).attr('style') === '') {
              $(this).removeAttr('style');
            }
          });
        }

        let theContentsWrapped = utils.htmlToElement(
          builderUI.settings.popup_wrapping_html.replace(/%s/g, '')
        );

        // Attributes
        theContentsWrapped.setAttribute('data-popup', this.popups[j].popupType);

        if (
          this.popups[j].popupType === 'entry' ||
          this.popups[j].popupType === 'exit'
        ) {
          theContentsWrapped.setAttribute(
            'data-popup-occurrence',
            this.popups[j].popupReoccurrence
          );
          theContentsWrapped.setAttribute(
            'data-popup-delay',
            this.popups[j].popupDelay
          );
        } else {
          theContentsWrapped.setAttribute('id', this.popups[j].popupID);
        }

        theContentsWrapped
          .querySelector('.modal-body')
          .appendChild(theContents.get(0));

        //append to DOM in the skeleton
        newDocMainParentPopups.get(0).appendChild(theContentsWrapped);

        newDocMainParentPopups
          .get(0)
          .querySelectorAll('[data-embed-id]')
          .forEach(embed => {
            const embedId = embed.getAttribute('data-embed-id');
            embed.innerHTML = site.embeds[embedId];
          });

        newDocMainParent.get(0).querySelectorAll('[data-embed-id]').forEach(embed => {
          const embedId = embed.getAttribute('data-embed-id');
          embed.innerHTML = site.embeds[embedId];
        });

        //remove draggable attributes
        newDocMainParentPopups.find('*[draggable]').each(function() {
          this.removeAttribute('draggable');
        });
      }
    };

    /*
            Checks if all blocks on this page have finished loading
        */
    this.loaded = function() {
      var i;

      for (i = 0; i < this.blocks.length; i++) {
        if (!this.blocks[i].loaded) return false;
      }

      return true;
    };

    /*
            clear out this page
        */
    this.clear = function() {
      var block = this.blocks.pop();

      while (block !== undefined) {
        block.delete();

        block = this.blocks.pop();
      }
    };

    /*
            Height adjustment for all blocks on the page
        */
    this.heightAdjustment = function() {
      for (var i = 0; i < this.blocks.length; i++) {
        this.blocks[i].heightAdjustment();
      }
    };

    /*
            Turn grid view on/off
        */
    this.gridView = function(on) {
      var i,j;
      for (i in this.blocks) this.blocks[i].gridView(on);
	  for(j in this.popups) this.popups[j].gridView(on); 
    };

    /*
            Attempt to detect which Google fonts are used on this page
        */
    this.detectGoogleFonts = function() {
      /*let usedFonts = [];

            this.blocks.forEach(function (block) {

                let elements = block.frameDocument.querySelectorAll('span[style*="font-family"]');

                if ( elements.length > 0 ) {

                    for ( let element of elements ) {
                        usedFonts.push(element.style.fontFamily);
                    }

                }

            });

            //console.log(usedFonts);

            // Rebuild the google_fonts from scratch
            site.customFonts.forEach(function (font) {

                usedFonts.forEach(function (f) {

                    if ( f == font.css_name ) console.log(font.css_name);

                });

            });*/
    };

    //add this page to the site object
    site.sitePages.push(this);

    //plant the new UL in the DOM (on the canvas)
    site.divCanvas.appendChild(this.parentUL);

    //make the blocks/frames in each page sortable

    var thePage = this;

    $(this.parentUL).sortable({
      revert: true,
      placeholder: 'drop-hover',
      handle: '.dragBlock',
      cancel: '',
      stop: function() {
        site.activePage.transparentOverlay('off');
        site.setPendingChanges(true);
        if (!site.loaded()) builderUI.canvasLoading('on');
      },
      beforeStop: function(event, ui) {
        //template or regular block?
        var attr = ui.item.attr('data-id');

        var newBlock;

        if (typeof attr !== typeof undefined && attr !== false) {
          //template, build it

          $('#start').hide();

          //clear out all blocks on this page
          thePage.clear();

          $.ajax({
            url: appUI.siteUrl + 'templates/templateData/' + attr,
            type: 'GET',
            dataType: 'json'
          })
            .done(function(ret) {
              let notifyConfig = notify.config;

              if (ret.responseCode === 1) {
                notifyConfig.className = 'joy';
                $.notify(ret.content, notifyConfig);

                ret.templateData.forEach(function(frame,i) {
					
			      if(i === 0) {
					  //display the name of the active page on the canvas
					  site.pageTitle.innerHTML = frame.pages_title;

					  //load the page settings into the page settings modal
					  site.spanPageName.innerText = frame.pages_title + '.html';
					  site.inputPageSettingsTitle.value = frame.pages_title;
					  site.inputPageSettingsMetaDescription.value = frame.pages_meta_description;
					  site.inputPageSettingsMetaKeywords.value = frame.pages_meta_keywords;
					  site.inputPageSettingsIncludes.value = frame.pages_header_includes;
					  site.inputPageSettingsPageCss.value = frame.pages_css;
						
					  //google fonts						
					  $(site.inputPageSettingsGoogleFonts).tagsinput('removeAll');
					  if(frame.google_fonts) {
					    	frame.google_fonts.forEach(function(font) {
								$(site.inputPageSettingsGoogleFonts).tagsinput('add', font.nice_name);
							});
					  }
						
					  site.updatePageSettings();
						
						
				  }
				  else {
					  newBlock = new Block('block');
					  newBlock.createParentLI(frame.frames_height);

					  var frameData = {};

					  frameData.src =
						appUI.siteUrl + 'sites/getframe/' + frame.frames_id;
					  frameData.frames_original_url =
						appUI.siteUrl + 'sites/getframe/' + frame.frames_id;
					  frameData.frames_height = frame.frames_height;

					  newBlock.createFrame(frameData);
					  newBlock.createFrameCover();
					  newBlock.insertBlockIntoDom(thePage.parentUL);

					  //add the block to the new page
					  thePage.blocks.push(newBlock);
				  }

                });
	
                //dropped element, so we've got pending changes
                site.setPendingChanges(true);
              } else {
                notifyConfig.className = 'bummer';
                $.notify(ret.content, notifyConfig);
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              if (
                jqXHR.getResponseHeader('Refresh') !== null &&
                jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
              ) {
                site.setPendingChanges(false);
                location.href =
                  appUI.siteUrl +
                  'auth/?url=' +
                  encodeURIComponent(location.href);
              }
            });

          //set the tempateID
          builderUI.templateID = ui.item.attr('data-id');

          //make sure nothing gets dropped in the lsit
          ui.item.html(null);

          //delete drag place holder
          $('body .ui-sortable-helper').remove();
        } else {
          //regular block

          //are we dealing with a new block being dropped onto the canvas, or a reordering og blocks already on the canvas?

          if (ui.item.find('.frameCover > button').size() > 0) {
            //re-ordering of blocks on canvas

            //no need to create a new block object, we simply need to make sure the position of the existing block in the Site object
            //is changed to reflect the new position of the block on th canvas

            var frameID = ui.item.find('iframe').attr('id');
            var newPos = ui.item.index();

            site.activePage.setPosition(frameID, newPos);

            //swap iframe's content with builder.frameContent
            //ui.item.find('iframe').contents().find( bConfig.pageContainer ).html(builderUI.frameContent);

            ui.item.find('iframe').on('load', function() {
              $(this)
                .contents()
                .find(bConfig.pageContainer)
                .html(builderUI.frameContents);
              site.activePage.heightAdjustment();
              site.activePage.fireBlockLoadEvent(frameID);
            });
          } else {
            //new block on canvas

            //new block
            newBlock = new Block('block');

            newBlock.placeOnCanvas(ui);
          }
        }
      },
      start: function(event, ui) {
        site.activePage.transparentOverlay('on');

        if (ui.item.find('.frameCover').size() !== 0) {
          builderUI.frameContents = ui.item
            .find('iframe')
            .contents()
            .find(bConfig.pageContainer)
            .html();
        }
      },
      over: function() {
        $('#start').hide();
      }
    });

    //add to the pages menu
    this.menuItem = document.createElement('LI');
    this.menuItem.innerHTML = this.pageMenuTemplate;

    $(this.menuItem)
      .find('a:first')
      .text(pageName)
      .attr('href', '#page' + counter);

    var theLink = $(this.menuItem)
      .find('a:first')
      .get(0);

    //bind some events
    this.menuItem.addEventListener('click', this, false);

    if (counter === 1) {
      this.menuItem.querySelector('.pageButtons').remove();
    } else {
      this.menuItem
        .querySelector('.fileEdit')
        .addEventListener('click', this, false);
      this.menuItem
        .querySelector('.fileSave')
        .addEventListener('click', this, false);
      this.menuItem
        .querySelector('.fileDel')
        .addEventListener('click', this, false);
    }

    //add to the page link dropdown
    this.linksDropdownItem = document.createElement('OPTION');
    this.linksDropdownItem.setAttribute('value', pageName + '.html');
    this.linksDropdownItem.text = pageName;

    builderUI.dropdownPageLinks.appendChild(this.linksDropdownItem);

    site.pagesMenu.appendChild(this.menuItem);
  }

  Page.prototype.handleEvent = function(event) {
    switch (event.type) {
      case 'click':
        if (event.target.classList.contains('fileEdit')) {
          this.editPageName();
        } else if (event.target.classList.contains('fileSave')) {
          console.log("===============");
          console.log('aaaaa');
          //this.updatePageNameEvent(event.target);
        } else if (event.target.classList.contains('fileDel')) {
          var thePage = this;

          $(builderUI.modalDeletePage).modal('show');

          $(builderUI.modalDeletePage)
            .off('click', '#deletePageConfirm')
            .on('click', '#deletePageConfirm', function() {
              thePage.delete();

              $(builderUI.modalDeletePage).modal('hide');
			  
			  builderUI.setNumberAvailablePages();
			  
            });
        } else {
          event.preventDefault();

          this.selectPage();
        }
    }
  };

  /*
        Block constructor
    */
  function Block(type) {
    this.type = type;
    this.frameID = 0;
    this.page = {};
    this.loaded = false;
    this.sandbox = false;
    this.sandbox_loader = '';
    this.status = ''; //'', 'changed' or 'new'
    this.global = false;
    this.originalUrl = '';

    this.parentLI = {};
    this.frameCover = {};
    this.frame = {};
    this.frameDocument = {};
    this.frameHeight = 0;

    this.annot = {};
    this.annotTimeout = {};

    this.oldWidth = 0; //used to determine the end of width animations

    /*
            creates the parent container (LI)
        */
    this.createParentLI = function(height) {
      this.parentLI = document.createElement('LI');
      this.parentLI.setAttribute('class', 'element');
      //this.parentLI.setAttribute('style', 'height: '+height+'px');
    };

    /*
            creates the iframe on the canvas
        */
    this.createFrame = function(frame) {
      this.frame = document.createElement('IFRAME');
      this.frame.setAttribute('frameborder', 0);
      this.frame.setAttribute('scrolling', 0);
      this.frame.setAttribute('src', frame.src);
      this.frame.setAttribute('data-originalurl', frame.frames_original_url);
      this.originalUrl = frame.frames_original_url;
      //this.frame.setAttribute('data-height', frame.frames_height);
      this.frameHeight = frame.frames_height;

      //vh heights require special attention
      if (frame.frames_height.indexOf('vh') !== -1)
        this.frame.style.height = frame.frames_height;

      $(this.frame).uniqueId();

      //sandbox?
      if (this.sandbox !== false) {
        this.frame.setAttribute('data-loaderfunction', this.sandbox_loader);
        this.frame.setAttribute('data-sandbox', this.sandbox);

        //recreate the sandboxed iframe elsewhere
        var sandboxedFrame = $(
          '<iframe src="' +
            frame.src +
            '" id="' +
            this.sandbox +
            '" sandbox="allow-same-origin"></iframe>'
        );
        $('#sandboxes').append(sandboxedFrame);
      }
    };

    /*
            Applies global and page styles to the block's iframe
        */
    this.applyCustomCSS = function() {
      // remove possible old <style> section
      let oldStyle = this.frameDocument.querySelector('head style#custom_css');
      if (oldStyle) oldStyle.remove();

      let theStyle = document.createElement('style');
      theStyle.id = 'custom_css';
      theStyle.innerText = '';

      // do we have any custom to apply?
      if (site.data.global_css) {
        theStyle.innerText += site.data.global_css;
      }

	  
      if (
        this.page.pageSettings !== undefined &&
        this.page.pageSettings.page_css !== ''
      ) {
        theStyle.innerText =
          theStyle.innerText + '\n' + this.page.pageSettings.page_css;
      }
	  else if(
        site.activePage.pageSettings !== undefined &&
        site.activePage.pageSettings.page_css !== ''
      ) {
        theStyle.innerText =
          theStyle.innerText + '\n' + site.activePage.pageSettings.page_css;
      }

      // only apply if there's custom css to apply
      if (theStyle.innerText !== '')
        this.frameDocument.querySelector('head').appendChild(theStyle);
    };

    /*
            insert the iframe into the DOM on the canvas
        */
    this.insertBlockIntoDom = function(theUL) {
      this.parentLI.appendChild(this.frame);
      theUL.appendChild(this.parentLI);

      this.frame.addEventListener('load', this, false);

      builderUI.canvasLoading('on');
    };

    /*
            sets the frame document for the block's iframe
        */
    this.setFrameDocument = function() {
      //set the frame document as well
      if (this.frame.contentDocument) {
        this.frameDocument = this.frame.contentDocument;
      } else {
        this.frameDocument = this.frame.contentWindow.document;
      }

      //this.heightAdjustment();
      //event
      /*this.frame.contentWindow.addEventListener('resize', function (e){
                this.oldWidth = e.currentTarget.innerWidth;
                console.log(this.oldWidth);
            });*/
    };

    /*
            hides the frame toolbar
        */
    this.hideFrameCover = function() {
      this.frameCover.style.display = 'none';
    };

    /*
            un-hides the frame toolbar
        */
    this.unhideFrameCover = function() {
      this.frameCover.style.display = 'block';
    };

    /*
            creates the frame cover and block action button
        */
    this.createFrameCover = function() {
      let newFramecover =
        this.type !== 'popup'
          ? document.importNode(
              document.getElementById('frameCoverTemplate').content,
              true
            )
          : document.importNode(
              document.getElementById('frameCoverPopupTemplate').content,
              true
            );

      this.frameCover = newFramecover.querySelector('.frameCover');
      this.parentLI.appendChild(this.frameCover);

      if (this.type === 'popup') {
        if (this.popupType === 'exit' || this.popupType === 'entry') {
          this.frameCover.classList.add('entry');

          //hide the ID section
          $('.divPopupID', this.frameCover).remove();

          // Set the re-orrurrence dropdown
          $('select.selectPopupRecurrence', this.frameCover).val(
            this.popupReoccurrence
          );
          $('select.selectPopupRecurrence', this.frameCover).trigger('change');

          // Set the delay dropdown
          $('select.selectPopupDelay', this.frameCover).val(this.popupDelay);
          $('select.selectPopupDelay', this.frameCover).trigger('change');

          if (this.popupType === 'exit' || this.popupType === 'regular') {
            $('select.selectPopupDelay', this.frameCover).val('0');
            $('select.selectPopupDelay', this.frameCover).trigger('change');
            $('select.selectPopupDelay', this.frameCover).select2('destroy');
            $('select.selectPopupDelay', this.frameCover).prop(
              'disabled',
              true
            );
            $('select.selectPopupDelay', this.frameCover).select2();
          }

          $('select.selectPopupRecurrence', this.frameCover).on(
            'change',
            event => {
              this.popupReoccurrence = event.currentTarget.value;
              site.setPendingChanges(true);
            }
          );

          $('select.selectPopupDelay', this.frameCover).on('change', event => {
            this.popupDelay = event.currentTarget.value;
            site.setPendingChanges(true);
          });
        } else {
          this.frameCover.classList.add('regular');

          this.frameCover.querySelector('.divPopupDelayWrapper').remove();
          this.frameCover.querySelector('.divPopupOccurrenceWrapper').remove();

          $('.divPopupID', this.frameCover).text('#' + this.popupID);
        }
      }

      /* setup the global block checkbox */

      if (this.global === true)
        $('input[type="checkbox"]', $(this.frameCover)).attr('checked', true);

      var theBlock = this;

      $('input[type="checkbox"]', $(this.frameCover))
        .on('change', function(e) {
          theBlock.toggleGlobal(e);
        })
        .radiocheck();

      /* setup the trash, reset, source and favourite links */

      let buttons = this.frameCover.querySelectorAll('.btn:not(.dragBlock)');

      for (let button of buttons) button.addEventListener('click', this, false);

      /* 
                should this block have the delete and save original buttons? (does not apply
                to saved/favourite blocks) 
            */
      if (
        this.frame
          .getAttribute('data-originalurl')
          .indexOf('sites/getframe') !== -1 &&
        this.frame.getAttribute('data-originalurl').indexOf('.html') === -1
      ) {
        if (this.frameCover.querySelector('button.buttonDelOriginal'))
          this.frameCover
            .querySelector('button.buttonDelOriginal')
            .setAttribute('disabled', true);
        if (this.frameCover.querySelector('button.buttonSaveOriginal'))
          this.frameCover
            .querySelector('button.buttonSaveOriginal')
            .setAttribute('disabled', true);
      } else {
        //this.frameCover.querySelector('buttonSaveOriginal')
      }

      /* setup the toggle button */
      $(this.frameCover.querySelector('button.frameCoverToggle')).on(
        'click',
        function() {
          if (this.frameCover.classList.contains('stay')) {
            this.frameCover.classList.remove('stay');
            this.frameCover.querySelector('button.frameCoverToggle').innerHTML =
              '<span class="fui-gear"></span>';
            this.parentLI.style.overflow = 'hidden';
          } else {
            this.frameCover.classList.add('stay');
            this.frameCover.querySelector('button.frameCoverToggle').innerHTML =
              '<span class="fui-cross-circle"></span>';
          }
        }.bind(this)
      );

      $('select', this.frameCover).select2({
        minimumResultsForSearch: -1
      });

      // Setup the clone in cat links
      $('.dropdownCloneInCat a', this.frameCover).on(
        'click',
        function(e) {
          this.cloneInCategory(e.currentTarget);

          return false;
        }.bind(this)
      );

      $('.dropdownCloneInCat', this.frameCover).on('show.bs.dropdown', function(
        e
      ) {
        $(e.currentTarget)
          .closest('li')
          .css('overflow', 'visible');
      });

      $('.dropdownCloneInCat', this.frameCover).on('hide.bs.dropdown', function(
        e
      ) {
        $(e.currentTarget)
          .closest('li')
          .css('overflow', 'hidden');
      });
    };

    /*
            Configures the frameCover button tooltips once the frame has finished loading
        */
    this.frameCovertooltips = function() {
      if (this.frameDocument.body.clientHeight > 200) {
        $('[data-toggle="tooltip"]', $(this.frameCover)).tooltip({
          trigger: 'hover',
          placement: 'bottom'
        });
      }
    };

    /*
            Places a transparent overlay over the block
        */
    this.transparentOverlay = function(onOrOff = 'on') {
      var div, divs;

      if (onOrOff === 'on') {
        //show the overlay

        divs = this.parentLI.querySelectorAll('div[data-overlay]');

        for (div of divs) {
          div.remove();
        }

        div = document.createElement('DIV');
        div.style.position = 'absolute';
        div.style.left = '0px';
        div.style.top = '0px';
        div.style.width = '100%';
        div.style.height = '100%';
        div.style.background = 'none';
        div.setAttribute('data-overlay', true);

        this.parentLI.appendChild(div);
      } else if (onOrOff === 'off') {
        //hide the overlay

        divs = this.parentLI.querySelectorAll('div[data-overlay]');

        for (div of divs) {
          div.remove();
        }
      }
    };

    /*

        */
    this.toggleGlobal = function(e) {
      if (e.currentTarget.checked) this.global = true;
      else this.global = false;

      //we've got pending changes
      site.setPendingChanges(true);
    };

    /*
            automatically corrects the height of the block's iframe depending on its content
        */
    this.heightAdjustment = function() {
      if (
        Object.keys(this.frameDocument).length !== 0 &&
        this.frame.style.height.indexOf('vh') === -1
      ) {
        this.frame.style.height = '0px';

        this.frameDocument.body.style.display = 'inline-block';

        var height = this.frameDocument.querySelector('html').offsetHeight;

        this.frameDocument.body.style.display = '';

        this.frame.style.height = height + 'px';
        this.parentLI.style.height = height + 'px';
        //this.frameCover.style.height = height+"px";

        this.frameHeight = height;
      } else if (
        this.frame.style !== undefined &&
        this.frame.style.height.indexOf('vh') !== -1
      ) {
        this.parentLI.style.height = this.frame.style.height;
      }
    };

    /*
            deletes a block
        */
    this.delete = function() {
      //remove from DOM/canvas with a nice animation
      $(this.frame.parentNode).fadeOut(500, function() {
        this.remove();

        site.activePage.isEmpty();
      });

      //remove from blocks array in the active page
      site.activePage.deleteBlock(this);

      //sanbox
      if (this.sanbdox) {
        document.getElementById(this.sandbox).remove();
      }

      //element was deleted, so we've got pending change
      site.setPendingChanges(true);
    };

    /*
            resets a block to it's orignal state
        */
    this.reset = function(fireEvent) {
      if (typeof fireEvent === 'undefined') fireEvent = true;

      //reset frame by reloading it
      this.frame.contentWindow.location = this.frame.getAttribute(
        'data-originalurl'
      );

      //sandbox?
      if (this.sandbox) {
        var sandboxFrame = document
          .getElementById(this.sandbox)
          .contentWindow.location.reload();
      }

      //element was deleted, so we've got pending changes
      site.setPendingChanges(true);

      builderUI.canvasLoading('on');

      if (fireEvent) publisher.publish('onBlockChange', this, 'reload');
    };

    /*
            launches the source code editor
        */
    this.source = function(element = null) {
      //hide the iframe
      if (!element) this.frame.style.display = 'none';

      //disable sortable on the parentLI
      $(this.parentLI.parentNode).sortable('disable');

      //built editor element
      var theEditor = document.createElement('DIV');
      theEditor.classList.add('aceEditor');
      $(theEditor).uniqueId();

      this.parentLI.appendChild(theEditor);

      //build and append error drawer
      var newLI = document.createElement('LI');
      var errorDrawer = document.createElement('DIV');
      errorDrawer.classList.add('errorDrawer');
      errorDrawer.setAttribute('id', 'div_errorDrawer');
      errorDrawer.innerHTML =
        '<button type="button" class="btn btn-xs btn-embossed btn-default button_clearErrorDrawer" id="button_clearErrorDrawer">CLEAR</button>';
      newLI.appendChild(errorDrawer);
      errorDrawer
        .querySelector('button')
        .addEventListener('click', this, false);
      this.parentLI.parentNode.insertBefore(newLI, this.parentLI.nextSibling);

      require('brace/mode/html');
      require('brace/theme/twilight');

      var theId = theEditor.getAttribute('id');
      var editor = ace.edit(theId);

      //editor.getSession().setUseWrapMode(true);

      var pageContainer = this.frameDocument.querySelector(
        bConfig.pageContainer
      );

      if (element && element.getAttribute('data-embed-id'))
        editor.setValue(site.embeds[element.getAttribute('data-embed-id')]);
      else if (element) editor.setValue(element.innerHTML);
      else {
        pageContainer.querySelectorAll('[data-embed-id]').forEach(embed => {
          embed.innerHTML = site.embeds[embed.getAttribute('data-embed-id')];
        });
        editor.setValue(pageContainer.innerHTML);
      }

      editor.setTheme('ace/theme/' + bConfig.aceTheme);
      editor.getSession().setMode('ace/mode/html');

      var block = this;

      editor.getSession().on('changeAnnotation', function() {
        block.annot = editor.getSession().getAnnotations();

        clearTimeout(block.annotTimeout);

        var timeoutCount;

        if ($('#div_errorDrawer p').size() === 0) {
          timeoutCount = bConfig.sourceCodeEditSyntaxDelay;
        } else {
          timeoutCount = 100;
        }

        block.annotTimeout = setTimeout(function() {
          for (var key in block.annot) {
            if (block.annot.hasOwnProperty(key)) {
              if (
                block.annot[key].text !==
                'Start tag seen without seeing a doctype first. Expected e.g. <!DOCTYPE html>.'
              ) {
                var newLine = $('<p></p>');
                var newKey = $('<b>' + block.annot[key].type + ': </b>');
                var newInfo = $(
                  '<span> ' +
                    block.annot[key].text +
                    'on line ' +
                    ' <b>' +
                    block.annot[key].row +
                    '</b></span>'
                );
                newLine.append(newKey);
                newLine.append(newInfo);

                $('#div_errorDrawer').append(newLine);
              }
            }
          }

          if (
            $('#div_errorDrawer').css('display') === 'none' &&
            $('#div_errorDrawer')
              .find('p')
              .size() > 0
          ) {
            $('#div_errorDrawer').slideDown();
          }
        }, timeoutCount);
      });

      var buttonWrapper = document.createElement('DIV');
      buttonWrapper.classList.add('editorButtons');

      let editorButtons = document.importNode(
        document.getElementById('sourceEditorButtons').content,
        true
      );

      buttonWrapper.append(editorButtons.querySelector('#editCancelButton'));
      buttonWrapper.append(editorButtons.querySelector('#editSaveButton'));

      buttonWrapper
        .querySelector('#editCancelButton')
        .addEventListener('click', this, false);
      buttonWrapper
        .querySelector('#editSaveButton')
        .addEventListener('click', () => this.saveSourceBlock(element), false);

      this.parentLI.appendChild(buttonWrapper);

      //should be make it a little higher?
      if (this.parentLI.offsetHeight < 300) {
        this.parentLI.setAttribute(
          'data-original-height',
          this.parentLI.offsetHeight + 'px'
        );
        this.parentLI.style.height = '300px';
      }

      builderUI.aceEditors[theId] = editor;
    };

    /*
            cancels the block source code editor
        */
    this.cancelSourceBlock = function() {
      //enable draggable on the LI
      $(this.parentLI.parentNode).sortable('enable');

      //delete the errorDrawer
      $(this.parentLI.nextSibling).remove();

      //delete the editor
      this.parentLI.querySelector('.aceEditor').remove();
      $(this.frame).fadeIn(500);

      if (this.parentLI.hasAttribute('data-original-height')) {
        this.parentLI.style.height = this.parentLI.getAttribute(
          'data-original-height'
        );
        this.parentLI.removeAttribute('data-original-height');
      }

      $(this.parentLI.querySelector('.editorButtons')).fadeOut(500, function() {
        $(this).remove();
      });
    };

    /*
            updates the blocks source code
        */
    this.saveSourceBlock = function(element) {
      //enable draggable on the LI
      $(this.parentLI.parentNode).sortable('enable');

      var theId = this.parentLI.querySelector('.aceEditor').getAttribute('id');
      var theContent = builderUI.aceEditors[theId].getValue();

      //delete the errorDrawer
      document.getElementById('div_errorDrawer').parentNode.remove();

      //delete the editor
      this.parentLI.querySelector('.aceEditor').remove();

      //update the frame's content
      if (element) {
        element.innerHTML = theContent;
        const id = shortid.generate();
        element.setAttribute('data-embed-id', id);
        site.embeds[id] = theContent;
      } else {
        const pageContainer = this.frameDocument.querySelector(
          bConfig.pageContainer
        );
        pageContainer.innerHTML = theContent;
        pageContainer.querySelectorAll('[data-embed-id]').forEach(embed => {
          site.embeds[embed.getAttribute('data-embed-id')] = embed.innerHTML;
        });
      }
      this.frame.style.display = 'block';

      $(this.parentLI.querySelector('.editorButtons')).fadeOut(500, function() {
        $(this).remove();
      });

      if (this.parentLI.hasAttribute('data-original-height'))
        this.parentLI.removeAttribute('data-original-height');

      //adjust height of the frame
      this.heightAdjustment();

      //new page added, we've got pending changes
      site.setPendingChanges(true);

      //block has changed
      this.status = 'changed';

      publisher.publish('onBlockChange', this, 'change');
      publisher.publish('onBlockLoaded', this);
    };

    /*
            clears out the error drawer
        */
    this.clearErrorDrawer = function() {
      var ps = this.parentLI.nextSibling.querySelectorAll('p');

      for (var i = 0; i < ps.length; i++) {
        ps[i].remove();
      }
    };

    /*
            returns the full source code of the block's frame
        */
    this.getSource = function(appendEmbeds) {
      let embeds = {};
      let source = '<html>';
      const blockHead = this.frameDocument.head.cloneNode(true);
      blockHead
        .querySelectorAll('.resize-triggers-styles')
        .forEach(style => style.remove());
      const blockBody = this.frameDocument.body.cloneNode(true);
      blockBody
        .querySelectorAll('.resize-triggers')
        .forEach(trigger => trigger.remove());
      blockBody.querySelectorAll('[data-embed-id]').forEach(embed => {
        const originalId = embed.getAttribute('data-embed-id');
        embed.innerHTML = site.embeds[originalId];
        embeds[originalId] = site.embeds[originalId];
        if (appendEmbeds) {
          const newId = shortid.generate();
          embed.setAttribute('data-embed-id', newId);
          const embedScript = document.createElement('SCRIPT');
          embedScript.text = `
            if (!window.blockEmbeds) window.blockEmbeds = {};
            window.blockEmbeds["${newId}"] = "${utils.custom_base64_encode(
            embeds[originalId]
          )}";
          `;
          embedScript.classList.add('embed-data');
          blockBody.appendChild(embedScript);
        }
      });
      source += blockHead.outerHTML + blockBody.outerHTML;
      source = utils.custom_base64_encode(source);
      embeds = JSON.stringify(embeds);
      embeds = utils.custom_base64_encode(embeds);

      return { source, embeds };
    };

    /*
            places a dragged/dropped block from the left sidebar onto the canvas
        */
    this.placeOnCanvas = function(ui) {
      //frame data, we'll need this before messing with the item's content HTML
      var frameData = {},
        attr;

      if (ui.item.find('iframe').size() > 0) {
        //iframe thumbnail

        frameData.src = ui.item.find('iframe').attr('src');
        frameData.frames_original_url = ui.item.find('iframe').attr('src');
        frameData.frames_height = ui.item.height();

        //sandboxed block?
        attr = ui.item.find('iframe').attr('sandbox');

        if (typeof attr !== typeof undefined && attr !== false) {
          this.sandbox = siteBuilderUtils.getRandomArbitrary(10000, 1000000000);
          this.sandbox_loader = ui.item
            .find('iframe')
            .attr('data-loaderfunction');
        }
      } else {
        //image thumbnail

        frameData.src = ui.item.find('img').attr('data-srcc');
        frameData.frames_original_url = ui.item.find('img').attr('data-srcc');
        frameData.frames_height = ui.item.find('img').attr('data-height');

        //sandboxed block?
        attr = ui.item.find('img').attr('data-sandbox');

        if (typeof attr !== typeof undefined && attr !== false) {
          this.sandbox = siteBuilderUtils.getRandomArbitrary(10000, 1000000000);
          this.sandbox_loader = ui.item.find('img').attr('data-loaderfunction');
        }
      }

      //create the new block object
      this.frameID = 0;
      this.parentLI = ui.item.get(0);
      this.parentLI.innerHTML = '';
      this.status = 'new';
      this.createFrame(frameData);

      if (this.type === 'popup') {
        this.parentLI.setAttribute('data-page-name', site.activePage.name);
        this.parentLI.setAttribute('data-page-id', site.activePage.pageID);
      }

      if (frameData.frames_height.indexOf('vh') !== -1)
        this.parentLI.style.height = frameData.frames_height;
      else this.parentLI.style.height = this.frameHeight + 'px';

      this.createFrameCover();

      this.frame.addEventListener('load', this);

      //insert the created iframe
      ui.item.append($(this.frame));

      //add the block to the current page
      if (this.type === 'block')
        site.activePage.blocks.splice(ui.item.index(), 0, this);
      else site.activePage.popups.splice(ui.item.index(), 0, this);

      //custom event
      ui.item.find('iframe').trigger('canvasupdated');

      //dropped element, so we've got pending changes
      site.setPendingChanges(true);
    };

    /*
            injects external JS (defined in config.js) into the block
        */
    this.loadJavascript = function() {
      var i, old, newScript;

      //remove old ones
      old = this.frameDocument.querySelectorAll('script.builder');

      /*for ( i = 0; i < old.length; i++ ) old[i].remove();*/

      //inject
      for (i = 0; i < bConfig.externalJS.length; i++) {
        newScript = document.createElement('SCRIPT');
        newScript.classList.add('builder');
        newScript.src = bConfig.externalJS[i];

        this.frameDocument.querySelector('body').appendChild(newScript);
      }
    };

    /*
            Checks if this block has external stylesheet
        */
    this.hasExternalCSS = function(src) {
      var externalCss, x;

      externalCss = this.frameDocument.querySelectorAll(
        'link[href*="' + src + '"]'
      );

      return externalCss.length !== 0;
    };

    /*
            Turn grid view on or off
        */
    this.gridView = function(on) {
      if (on) {
        this.frameDocument.querySelector('body').classList.add('gridView');
      } else {
        this.frameDocument.querySelector('body').classList.remove('gridView');
      }
    };

    this.cloneInCategory = function(el) {
      let theBlock = this;
      let dropdown = $(el)
        .closest('.btn-group')
        .find('.dropdown-toggle');

      let frames_content = theBlock.getSource(true).source;
      let frames_height = theBlock.frameHeight;
      let frames_width = theBlock.frameDocument.querySelector('body')
        .offsetWidth;
      let category = el.getAttribute('data-cat-id');

      // Collapse the dropdown
      dropdown.dropdown('toggle');

      // disable the dropdown for now
      dropdown.attr('disabled', true);

      $.ajax({
        url: appUI.siteUrl + 'sites/cloneoriginal_block',
        type: 'POST',
        data: {
          frames_content: frames_content,
          frames_height: frames_height,
          frames_width: frames_width,
          category: category
        },
        dataType: 'json'
      })
        .done(function(ret) {
          let notifyConfig = notify.config;

          dropdown.removeAttr('disabled');

          if (ret.responseCode === 1) {
            notifyConfig.className = 'joy';
            $.notify(ret.content, notifyConfig);

            builderUI.loadBlocksComponents(true, false);
          } else {
            notifyConfig.className = 'bummer';
            $.notify(ret.content, notifyConfig);
          }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          if (
            jqXHR.getResponseHeader('Refresh') !== null &&
            jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
          ) {
            site.setPendingChanges(false);
            location.href =
              appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
          }
        });
    };

    /*
            Deletes the original block's template
        */
    this.deleteOriginalBlock = function(el) {
      let theBlock = this;
      let element = el.classList.contains('btn') ? el : el.parentNode;

      element.blur();

      function hideButton() {
        return new Promise(function(resolve, reject) {
          $('i', element).fadeOut(() => {
            let newSpan = document.createElement('SPAN');
            newSpan.innerText = element.getAttribute('data-saving');

            element.appendChild(newSpan);

            resolve(element);
          });
        });
      }

      function deleteBlockRemote(el) {
        let frames_url = theBlock.frame.getAttribute('data-originalurl');

        return new Promise(function(resolve, reject) {
          $.ajax({
            url: appUI.siteUrl + 'sites/deleteoriginal_block',
            type: 'POST',
            data: {
              frames_url: frames_url
            },
            dataType: 'json'
          })
            .done(function(ret) {
              let notifyConfig = notify.config;

              if (ret.responseCode === 1) {
                notifyConfig.className = 'joy';
                $.notify(ret.content, notifyConfig);
                builderUI.loadBlocksComponents(true, false);
                resolve(el);
              } else {
                notifyConfig.className = 'bummer';
                $.notify(ret.content, notifyConfig);
                reject(el);
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              if (
                jqXHR.getResponseHeader('Refresh') !== null &&
                jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
              ) {
                site.setPendingChanges(false);
                location.href =
                  appUI.siteUrl +
                  'auth/?url=' +
                  encodeURIComponent(location.href);
              }
            });
        });
      }

      function hideSaving(el) {
        return new Promise(function(resolve, reject) {
          $('span', el).fadeOut(function() {
            this.remove();

            let newSpan = document.createElement('SPAN');
            newSpan.innerText = el.getAttribute('data-confirmation');

            el.appendChild(newSpan);

            resolve(el);
          });
        });
      }

      function deleteRemoteFailed(el) {
        $('span', el).fadeOut(function() {
          this.remove();
          $('i', el).fadeIn();
        });
      }

      function deletedTimeout(el) {
        setTimeout(() => {
          $('span', el).fadeOut(function() {
            this.remove();
            $('i', el).fadeIn();

            theBlock.frameCover.classList.remove('stay');
          });
        }, 3000);
      }

      let q = hideButton()
        .then(deleteBlockRemote)
        .then(hideSaving)
        .then(deletedTimeout)
        .catch(deleteRemoteFailed);
    };

    /*
            Save / overwrite the original block
        */
    this.saveOriginalBlock = function(el) {
      let theBlock = this;
      let element = el.classList.contains('btn') ? el : el.parentNode;

      element.blur();

      function hideButton() {
        return new Promise(function(resolve, reject) {
          $('i', element).fadeOut(() => {
            let newSpan = document.createElement('SPAN');
            newSpan.innerText = element.getAttribute('data-saving');

            element.appendChild(newSpan);

            resolve(element);
          });
        });
      }

      function saveBlockRemote(el) {
        let frames_content = theBlock.getSource(true).source;
        let frames_url = theBlock.frame.getAttribute('data-originalurl');
        let frames_height = theBlock.frameHeight;
        let frames_width = theBlock.frameDocument.querySelector('body')
          .offsetWidth;

        return new Promise(function(resolve, reject) {
          $.ajax({
            url: appUI.siteUrl + 'sites/updateoriginal_block',
            type: 'POST',
            data: {
              frames_content: frames_content,
              frames_url: frames_url,
              frames_height: frames_height,
              frames_width: frames_width
            },
            dataType: 'json'
          })
            .done(function(ret) {
              let notifyConfig = notify.config;

              if (ret.responseCode === 1) {
                notifyConfig.className = 'joy';
                $.notify(ret.content, notifyConfig);
                builderUI.loadBlocksComponents(true, false);
                resolve(el);
              } else {
                notifyConfig.className = 'bummer';
                $.notify(ret.content, notifyConfig);
                reject(el);
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              if (
                jqXHR.getResponseHeader('Refresh') !== null &&
                jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
              ) {
                site.setPendingChanges(false);
                location.href =
                  appUI.siteUrl +
                  'auth/?url=' +
                  encodeURIComponent(location.href);
              }
            });
        });
      }

      function hideSaving(el) {
        return new Promise(function(resolve, reject) {
          $('span', el).fadeOut(function() {
            this.remove();

            let newSpan = document.createElement('SPAN');
            newSpan.innerText = el.getAttribute('data-confirmation');

            el.appendChild(newSpan);

            resolve(el);
          });
        });
      }

      function saveRemoteFailed(el) {
        $('span', el).fadeOut(function() {
          this.remove();
          $('i', el).fadeIn();
        });
      }

      function savedTimeout(el) {
        setTimeout(() => {
          $('span', el).fadeOut(function() {
            this.remove();
            $('i', el).fadeIn();

            theBlock.frameCover.classList.remove('stay');
          });
        }, 3000);
      }

      let q = hideButton()
        .then(saveBlockRemote)
        .then(hideSaving)
        .then(savedTimeout)
        .catch(saveRemoteFailed);
    };

    /*
            Save this block as favourite
        */
    this.saveAsFav = function(el) {
      let theBlock = this;
      let element = el.classList.contains('btn') ? el : el.parentNode;

      element.blur(); // remove focus / depressed state

      function hideFavButton() {
        return new Promise(function(resolve, reject) {
          $('i', element).fadeOut(() => {
            let newSpan = document.createElement('SPAN');
            newSpan.innerText = element.getAttribute('data-saving');

            element.appendChild(newSpan);

            // prevent the frameCover from disappearing
            theBlock.frameCover.classList.add('stay');

            resolve(element);
          });
        });
      }

      function saveFavRemote(el) {
        let frames_content = theBlock.getSource(true).source;
        let frames_height = theBlock.frameHeight;
        let frames_original_url = theBlock.originalUrl;
        let frames_width = theBlock.frameDocument.querySelector('body')
          .offsetWidth;

        return new Promise(function(resolve, reject) {
          $.ajax({
            url: appUI.siteUrl + 'sites/favourite_block',
            type: 'POST',
            data: {
              frames_content: frames_content,
              frames_height: frames_height,
              frames_original_url: frames_original_url,
              frames_width: frames_width
            },
            dataType: 'json'
          })
            .done(function(ret) {
              if (ret.responseCode === 1) {
                // add the new fav block to the sidebar
                let newItem = $(
                  '<li style="display: none"><a href="" class="delFavBlock" data-block-id="' +
                    ret.block.blocks_id +
                    '"><i class="fui-cross-circle"></i></a><img src="' +
                    ret.block.blocks_thumb +
                    '" data-srcc="' +
                    ret.block.blocks_url +
                    '" data-height="' +
                    ret.block.blocks_height +
                    '"></li>'
                );

                $(builderUI.sideSecondBlocksNav)
                  .find('ul:first')
                  .append(newItem);

                newItem.fadeIn();

                builderUI.makeDraggable();

                resolve(el);
              } else {
                reject(el);
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              if (
                jqXHR.getResponseHeader('Refresh') !== null &&
                jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
              ) {
                site.setPendingChanges(false);
                location.href =
                  appUI.siteUrl +
                  'auth/?url=' +
                  encodeURIComponent(location.href);
              }
            });
        });
      }

      function hideSaving(el) {
        return new Promise(function(resolve, reject) {
          $('span', el).fadeOut(function() {
            this.remove();

            let newSpan = document.createElement('SPAN');
            newSpan.innerText = el.getAttribute('data-confirmation');

            el.appendChild(newSpan);

            resolve(el);
          });
        });
      }

      function saveRemoteFailed(el) {
        alert('Could not save the block as favourite, please try again');

        $('span', el).fadeOut(function() {
          this.remove();
          $('i', el).fadeIn();
        });
      }

      function savedTimeout(el) {
        setTimeout(() => {
          $('span', el).fadeOut(function() {
            this.remove();
            $('i', el).fadeIn();

            theBlock.frameCover.classList.remove('stay');
          });
        }, 3000);
      }

      let q = hideFavButton()
        .then(saveFavRemote)
        .then(hideSaving)
        .then(savedTimeout)
        .catch(saveRemoteFailed);
    };
  }

  Block.prototype.handleEvent = function(event) {
    switch (event.type) {
      case 'load':
        this.setFrameDocument();
        this.applyCustomCSS();
        this.heightAdjustment();
        this.loadJavascript();
        this.frameCovertooltips();

        $(this.frameCover).removeClass('fresh', 500);

        publisher.publish('onBlockLoaded', this);

        this.loaded = true;

        builderUI.canvasLoading('off');

        break;

      case 'click':
        var theBlock = this;

        //figure out what to do next

        if (
          event.target.classList.contains('deleteBlock') ||
          event.target.parentNode.classList.contains('deleteBlock')
        ) {
          //delete this block

          $(builderUI.modalDeleteBlock).modal('show');

          $(builderUI.modalDeleteBlock)
            .off('click', '#deleteBlockConfirm')
            .on('click', '#deleteBlockConfirm', function() {
              if (theBlock.type === 'popup') theBlock.deletePopup();
              else theBlock.delete(event);
              $(builderUI.modalDeleteBlock).modal('hide');
            });
        } else if (
          event.target.classList.contains('resetBlock') ||
          event.target.parentNode.classList.contains('resetBlock')
        ) {
          //reset the block

          $(builderUI.modalResetBlock).modal('show');

          $(builderUI.modalResetBlock)
            .off('click', '#resetBlockConfirm')
            .on('click', '#resetBlockConfirm', function() {
              theBlock.reset();
              $(builderUI.modalResetBlock).modal('hide');
            });
        } else if (
          event.target.classList.contains('htmlBlock') ||
          event.target.parentNode.classList.contains('htmlBlock')
        ) {
          //source code editor
		  $.ajax({
			url: appUI.siteUrl + 'builder_elements/canEditBlockSourceCode',
			type: 'post',
			dataType: 'json'
		  })
		  .done(function(ret) {
							
			    if (ret.responseCode === 1) {
				  theBlock.source();
				  $('.htmlBlock').removeClass('disabled');	
			    } else {
				  let notifyConfig = notify.config;
				  notifyConfig.className = 'bummer';
				  $.notify(ret.content, notifyConfig);
				  $('.htmlBlock').addClass('disabled');	
			    }
							  
		  })
		  .fail(function(jqXHR, textStatus, errorThrown) {
			  if (
				jqXHR.getResponseHeader('Refresh') !== null &&
				jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
			  ) {
				site.setPendingChanges(false);
				location.href =
				  appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
			  }
		  });
		  
          
        } else if (
          event.target.classList.contains('favBlock') ||
          event.target.parentNode.classList.contains('favBlock')
        ) {
          theBlock.saveAsFav(event.target);
        } else if (
          event.target.classList.contains('editCancelButton') ||
          event.target.parentNode.classList.contains('editCancelButton')
        ) {
          //cancel source code editor

          theBlock.cancelSourceBlock();
        } else if (
          event.target.classList.contains('editSaveButton') ||
          event.target.parentNode.classList.contains('editSaveButton')
        ) {
          //save source code

          theBlock.saveSourceBlock();
        } else if (event.target.classList.contains('button_clearErrorDrawer')) {
          //clear error drawer

          theBlock.clearErrorDrawer();
        } else if (
          event.target.classList.contains('buttonSaveOriginal') ||
          event.target.parentNode.classList.contains('buttonSaveOriginal')
        ) {
          // Save original

          theBlock.saveOriginalBlock(event.target);
        } else if (
          event.target.classList.contains('buttonDelOriginal') ||
          event.target.parentNode.classList.contains('buttonDelOriginal')
        ) {
          // Delete original

          theBlock.deleteOriginalBlock(event.target);
        }
    }
  };

  /*
        Site object literal
    */
  /*jshint -W003 */
  var site = {
    pendingChanges: false, //pending changes or no?
    pages: {}, //array containing all pages, including the child frames, loaded from the server on page load
    is_admin: 0, //0 for non-admin, 1 for admin
    data: {}, //container for ajax loaded site data
    pagesToDelete: [], //contains pages to be deleted

    embeds: {}, // Any code embeds on the site blocks

    sitePages: [], //this is the only var containing the recent canvas contents

    sitePagesReadyForServer: {}, //contains the site data ready to be sent to the server

    activePage: {}, //holds a reference to the page currently open on the canvas

    pageTitle: document.getElementById('pageTitle'), //holds the page title of the current page on the canvas

    divCanvas: document.getElementById('pageList'), //DIV containing all pages on the canvas

    pagesMenu: document.getElementById('pages'), //UL containing the pages menu in the sidebar

    buttonNewPage: document.getElementById('addPage'),
    liNewPage: document.getElementById('newPageLI'),

    spanPageName: document.getElementById('spanPageName'),
    inputPageSettingsTitle: document.getElementById('pageData_title'),
    inputPageSettingsMetaDescription: document.getElementById(
      'pageData_metaDescription'
    ),
    inputPageSettingsMetaKeywords: document.getElementById(
      'pageData_metaKeywords'
    ),
    inputPageSettingsIncludes: document.getElementById(
      'pageData_headerIncludes'
    ),
    inputPageSettingsPageCss: document.getElementById('pageData_headerCss'),
    inputPageSettingsGoogleFonts: document.getElementById(
      'pageData_googleFonts'
    ),

    buttonSubmitPageSettings: document.getElementById(
      'pageSettingsSubmittButton'
    ),

    modalPageSettings: document.getElementById('pageSettingsModal'),

    buttonSave: document.getElementById('savePage'),

    messageStart: document.getElementById('start'),
    divFrameWrapper: document.getElementById('frameWrapper'),

    skeleton: document.getElementById('skeleton'),

    autoSaveTimer: {},

    customFonts: {},

    init: function() {
      $.getJSON(appUI.siteUrl + 'sites/siteData', function(data) {
        site.customFonts = data.fonts;

        if (data.language) window.language = data.language;

        if (data.settings) builderUI.settings = data.settings;

        if (data.site !== undefined) {
          site.data = data.site;

          if (data.site.viewmode) {
            publisher.publish('onSetMode', data.site.viewmode);
          }
        }

        if (data.pages !== undefined) {
          site.pages = data.pages;
          for (const page in site.pages) site.setPageEmbeds(site.pages[page]);
        }

        site.is_admin = data.is_admin;

        if (data.google_api !== undefined) {
          bConfig.google_api = data.google_api;
        }

        if ($('#pageList').size() > 0) {
          builderUI.populateCanvas();
		  builderUI.setNumberAvailablePages(); 
        }

        if (data.templateID !== undefined) {
          builderUI.templateID = data.templateID;
        }

        //fire custom event
        $('body').trigger('siteDataLoaded');
        publisher.publish('siteDataLoaded');
      });

      $(this.buttonNewPage).on('click', site.newPage);
      $(this.modalPageSettings).on('show.bs.modal', site.loadPageSettings);
      $(this.buttonSubmitPageSettings).on('click', site.updatePageSettings);
      $(this.buttonSave).on('click', function() {
        site.save(true);
      });

      //auto save time
      this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);

      publisher.subscribe('onBlockChange', function(block, type) {
        if (block.global) {
          for (var i = 0; i < site.sitePages.length; i++) {
            for (var y = 0; y < site.sitePages[i].blocks.length; y++) {
              if (
                site.sitePages[i].blocks[y] !== block &&
                site.sitePages[i].blocks[y].originalUrl === block.originalUrl &&
                site.sitePages[i].blocks[y].global
              ) {
                if (type === 'change') {
                  // Remove blue outline, sb_open class
                  let theClone = block.frameDocument.body.cloneNode(true);
                  let opens = theClone.querySelectorAll('.sb_open');

                  for (let el of opens) {
                    el.classList.remove('sb_open');
                  }

                  /*opens.forEach(function (el) {
                                        el.classList.remove('sb_open');
                                    });*/

                  site.sitePages[i].blocks[y].frameDocument.body = theClone;

                  publisher.publish(
                    'onBlockLoaded',
                    site.sitePages[i].blocks[y]
                  );
                } else if (type === 'reload') {
                  site.sitePages[i].blocks[y].reset(false);
                }

                site.sitePages[i].status = 'changed';
              }
            }
          }
        }
      });

      /*
                This to make sure we update some site details when the site details form is submitted
            */
      publisher.subscribe('onSiteDetailsSaved', function(formData) {
        formData.forEach(function(entry) {
          if (entry.name === 'sites_name') site.data.sites_name = entry.value;
          if (entry.name === 'global_css') site.data.global_css = entry.value;
        });

        // apply possible custom styles to each block on the canvas
        site.applyCustomCSS();
      });

      $(site.skeleton).on('load', function() {
        publisher.publish('onSkeletonLoaded', this);
      });

      publisher.subscribe('onBlockLoaded', function(block) {
        block.frameDocument
          .querySelectorAll('[data-embed-id]')
          .forEach(embed => {
            const embedId = embed.getAttribute('data-embed-id');
            if (
              typeof site.embeds[embedId] === 'undefined' &&
              block.frame.contentWindow.blockEmbeds
            ) {
              site.embeds[embedId] = decodeURIComponent(
                utils.custom_base64_decode(
                  block.frame.contentWindow.blockEmbeds[embedId]
                )
              );
            }
          });
      });
    },

    setPageEmbeds(page) {
      if (!page.blocks) return;
      page.blocks.forEach(block => {
        let embeds = {};
        if (block.frames_embeds) {
          embeds = utils.custom_base64_decode(block.frames_embeds);
          embeds = decodeURIComponent(embeds);
          embeds = JSON.parse(embeds);
        }
        for (const id in embeds) site.embeds[id] = embeds[id];
      });
    },

    applyCustomCSS: function() {
      for (let page of site.sitePages) {
        for (let block of page.blocks) {
          block.applyCustomCSS();
        }
      }
    },

    autoSave: function() {
      if (site.pendingChanges) {
        site.save(false);
      }

      window.clearInterval(this.autoSaveTimer);
      this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);
    },

    setPendingChanges: function(value) {
      site.pendingChanges = value;

      if (value === true) {
        //reset timer
        window.clearInterval(this.autoSaveTimer);
        this.autoSaveTimer = setTimeout(site.autoSave, bConfig.autoSaveTimeout);

        $('#savePage .bLabel').text($('#savePage').attr('data-label2'));

        if (site.activePage.status !== 'new') {
          site.activePage.status = 'changed';
        }
      } else {
        $('#savePage .bLabel').text($('#savePage').attr('data-label'));

        site.updatePageStatus('');
      }
    },

    save: function(showConfirmModal) {
      publisher.publish('onBeforeSave');

      //fire custom event
      $('body').trigger('beforeSave');

      //disable button
      $('#savePage').addClass('disabled');
      $('#savePage')
        .find('.bLabel')
        .text($('#savePage').attr('data-loading'));

      //remove old alerts
      $('#errorModal .modal-body > *, #successModal .modal-body > *').each(
        function() {
          $(this).remove();
        }
      );

      site.prepForSave(false);

      var serverData = {};
      serverData.pages = this.sitePagesReadyForServer;
      if (this.pagesToDelete.length > 0) {
        serverData.toDelete = this.pagesToDelete;
      }

      serverData.siteData = this.data;

      //store current responsive mode as well
      serverData.siteData.responsiveMode = builderUI.currentResponsiveMode;

      $.ajax({
        url: appUI.siteUrl + 'sites/save',
        type: 'POST',
        dataType: 'json',
        data: serverData
      })
        .done(function(res, textStatus) {
          //enable button
          $('#savePage').removeClass('disabled');
          $('#savePage')
            .find('.bLabel')
            .text($('#savePage').attr('data-label'));

          if (res.responseCode === 0) {
            if (showConfirmModal) {
              $('#errorModal .modal-body').append($(res.responseHTML));
              $('#errorModal').modal('show');
            }
          } else if (res.responseCode === 1) {
            if (showConfirmModal) {
              $('#successModal .modal-body').append($(res.responseHTML));
              $('#successModal').modal('show');
            }

            //no more pending changes
            site.setPendingChanges(false);

            //update revisions?
            $('body').trigger('changePage');
          }

          publisher.publish('onAfterSave');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          if (
            jqXHR.getResponseHeader('Refresh') !== null &&
            jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
          ) {
            site.setPendingChanges(false);
            location.href =
              appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
          }
        });
    },

    /*
            preps the site data before sending it to the server
        */
    prepForSave: function(template) {
      this.sitePagesReadyForServer = {};

      if (template) {
        //saving template, only the activePage is needed

        this.sitePagesReadyForServer[
          this.activePage.name
        ] = this.activePage.prepForSave();

        this.activePage.fullPage();
      } else {
        //regular save

        //find the pages which need to be send to the server
        for (var i = 0; i < this.sitePages.length; i++) {
          if (this.sitePages[i].status !== '') {
            this.sitePagesReadyForServer[
              this.sitePages[i].name
            ] = this.sitePages[i].prepForSave();
          }
        }
      }
    },

    /*
            sets a page as the active one
        */
    setActive: function(page) {
      //reference to the active page
      this.activePage = page;

      //hide other pages
      for (var i in this.sitePages) {
        this.sitePages[i].parentUL.style.display = 'none';
      }

      //display active one
      this.activePage.parentUL.style.display = 'block';
    },

    /*
            de-active all page menu items
        */
    deActivateAll: function() {
      var pages = this.pagesMenu.querySelectorAll('li');

      for (var i = 0; i < pages.length; i++) {
        pages[i].classList.remove('active');
      }
    },

    /*
            adds a new page to the site
        */
    newPage: function() {
      site.deActivateAll();
	
	  $.ajax({
		url: appUI.siteUrl + 'builder_elements/addPage',
		type: 'post',
		data: {
          pages_number: site.sitePages.length
        },
		dataType: 'json'
	  })
	  .done(function(ret) {
		  if (ret.responseCode === 1) {
			//create the new page instance
			var pageData = [];
			var temp = {
			  pages_id: 0
			};
			pageData[0] = temp;

			var newPageName = 'page' + (site.sitePages.length + 1);

			var newPage = new Page(newPageName, pageData, site.sitePages.length + 1);

			newPage.status = 'new';

			newPage.selectPage();
			//newPage.editPageName();

			newPage.isEmpty();
			
			site.setPendingChanges(true);
			
			
		
			if(ret.pages_left > 0)
				$('#addPage').removeClass('disabled');
			else if(ret.pages_left !== -1)
				$('#addPage').addClass('disabled');	
			  
		  } else {
			let notifyConfig = notify.config;
			notifyConfig.className = 'bummer';
			$.notify(ret.content, notifyConfig);
			//Disable button
			$('#addPage').addClass('disabled');
			
			/*          if (res.responseCode === 0) {
            if (showConfirmModal) {
              $('#errorModal .modal-body').append($(res.responseHTML));
              $('#errorModal').modal('show');
            }
          } else if (res.responseCode === 1) {
            if (showConfirmModal) {
              $('#successModal .modal-body').append($(res.responseHTML));
              $('#successModal').modal('show');
            }*/
			
		  }
		  let pagesAvailable = ret.pages_left === -1 ? '&#8734;' : ret.pages_left + site.sitePages.length;
		  $('#pages_counter').html( site.sitePages.length + " / " + pagesAvailable );
	  })
	  .fail(function(jqXHR, textStatus, errorThrown) {
		  if (
			jqXHR.getResponseHeader('Refresh') !== null &&
			jqXHR.getResponseHeader('Refresh').indexOf('/auth') !== -1
		  ) {
			site.setPendingChanges(false);
			location.href =
			  appUI.siteUrl + 'auth/?url=' + encodeURIComponent(location.href);
		  }
	  });  
	
    },

    /*
            checks if the name of a page is allowed
        */
    checkPageName: function(pageName) {
      //make sure the name is unique
      for (var i in this.sitePages) {
        if (
          this.sitePages[i].name === pageName &&
          this.activePage !== this.sitePages[i]
        ) {
          this.pageNameError = 'The page name must be unique.';
          return false;
        }
      }

      return true;
    },

    /*
            removes unallowed characters from the page name
        */
    prepPageName: function(pageName) {
      pageName = pageName.replace(' ', '');
      pageName = pageName.replace(/[^a-zA-Z0-9_-]/g, '');
      return pageName;
    },

    /*
            save page settings for the current page
        */
    updatePageSettings: function() {
      site.activePage.pageSettings.title = site.inputPageSettingsTitle.value;
      site.activePage.pageSettings.meta_description =
        site.inputPageSettingsMetaDescription.value;
      site.activePage.pageSettings.meta_keywords =
        site.inputPageSettingsMetaKeywords.value;
      site.activePage.pageSettings.header_includes =
        site.inputPageSettingsIncludes.value;
      site.activePage.pageSettings.page_css =
        site.inputPageSettingsPageCss.value;

      // Google fonts
      let usedFonts = $(site.inputPageSettingsGoogleFonts).tagsinput('items');

      site.activePage.pageSettings.google_fonts.forEach(function(font) {
        if (usedFonts.indexOf(font.nice_name) === -1) {
          let index = site.activePage.pageSettings.google_fonts.indexOf(font);
          site.activePage.pageSettings.google_fonts.splice(index, 1);
        }
      });

      site.setPendingChanges(true);

      $(site.modalPageSettings).modal('hide');

      site.applyCustomCSS();
    },

    /*
            update page statuses
        */
    updatePageStatus: function(status) {
      for (var i in this.sitePages) {
        this.sitePages[i].status = status;
      }
    },

    /*
            Checks all the blocks in this site have finished loading
        */
    loaded: function() {
      var i;

      for (i = 0; i < this.sitePages.length; i++) {
        if (!this.sitePages[i].loaded()) return false;
      }

      return true;
    },

    /*
            Turn grid view on/off
        */
    gridView: function(on) {
      var i;
      for (i in this.sitePages) this.sitePages[i].gridView(on);
    }
  };

  builderUI.init();
  site.init();

  //**** EXPORTS
  module.exports.site = site;
  module.exports.builderUI = builderUI;
  module.exports.Block = Block;
})();
