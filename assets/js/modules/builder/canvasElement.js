(function() {
  'use strict';

  var siteBuilder = require('./builder.js');
  var publisher = require('../../vendor/publisher');

  /*
        constructor function for Element
    */
  module.exports.Element = function(el) {
    this.element = el;
    this.sandbox = false;
    this.parentFrame = {};
    this.parentBlock = {}; //reference to the parent block element
    this.editableAttributes = [];
    this.toolbar = {};

    //make current element active/open (being worked on)
    this.setOpen = function() {
      $(this.element).off('mouseenter mouseleave');

      //$(this.element).css({'outline': '2px solid rgba(233,94,94,0.5)', 'outline-offset':'-2px', 'cursor': 'pointer'});

      this.element.classList.add('sb_open');
      this.element.classList.remove('sb_hover');
    };

    //sets up hover and click events, making the element active on the canvas
    this.activate = function() {
      var element = this,
        DELAY = 700,
        clicks = 0,
        timer = null;

      //data attributes for color
      if (this.element.tagName === 'A')
        $(this.element).data('color', getComputedStyle(this.element).color);

      $(this.element).css({ outline: '', cursor: '' });

      $(this.element)
        .off('click')
        .hoverIntent({
          over: function(e) {
            e.stopPropagation();

            if (
              !this.element.hasAttribute('data-nointent') &&
              !this.element.hasAttribute('data-bloxby-editor') &&
              !this.element.classList.contains('sb_hover')
            ) {
              $(this.element)
                .parents('.sb_hover')
                .removeClass('sb_hover');

              this.element.classList.add('sb_hover');

              this.buildToolBar();
            }
          }.bind(this),
          out: function(e) {
            e.stopPropagation();

            setTimeout(
              function() {
                if (
                  Object.keys(this.toolbar).length === 0 ||
                  !this.toolbar.hasAttribute('data-hover')
                ) {
                  this.element.classList.remove('sb_hover');
                  this.destroyToolBar();
                }
              }.bind(this),
              100
            );
          }.bind(this),
          timeout: 0
        })
        .on('mouseenter', function() {
          this.setAttribute('data-hover', true);

          if ($(this).parents('*[data-selector]')[0])
            $(this)
              .parents('*[data-selector]')[0]
              .setAttribute('data-nointent', true);
        })
        .on('mouseleave', function() {
          this.removeAttribute('data-hover');

          if ($(this).parents('*[data-selector]')[0])
            $(this)
              .parents('*[data-selector]')[0]
              .removeAttribute('data-nointent');
        })
        .on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
        })
        .on(
          'dragstart',
          function() {
            //remove all existing toolbars
            $(this.element)
              .closest('body')
              .find('.canvasElToolbar')
              .remove();
          }.bind(this)
        );
    };

    this.deactivate = function() {
      $(this.element).off('mouseenter mouseleave click');
      //$(this.element).css({'outline': '', 'cursor': ''});
      this.element.classList.remove('sb_open');
      this.element.classList.remove('sb_hover');
    };

    //removes the elements outline
    this.removeOutline = function() {
      //$(this.element).css({'outline': '', 'cursor': ''});

      this.element.classList.remove('sb_open');
      this.element.classList.remove('sb_hover');
    };

    this.unsetNoIntent = function() {
      this.element.removeAttribute('data-nointent');
    };

    //sets the parent iframe
    this.setParentFrame = function() {
      var doc = this.element.ownerDocument;
      var w = doc.defaultView || doc.parentWindow;
      var frames = w.parent.document.getElementsByTagName('iframe');

      for (var i = frames.length; i-- > 0; ) {
        var frame = frames[i];

        try {
          var d = frame.contentDocument || frame.contentWindow.document;
          if (d === doc) this.parentFrame = frame;
        } catch (e) {}
      }
    };

    //sets this element's parent block reference
    this.setParentBlock = function() {
      //loop through all the blocks on the canvas
      for (var i = 0; i < siteBuilder.site.sitePages.length; i++) {
        // For blocks
        for (var x = 0; x < siteBuilder.site.sitePages[i].blocks.length; x++) {
          //if the block's frame matches this element's parent frame
          if (
            siteBuilder.site.sitePages[i].blocks[x].frame === this.parentFrame
          ) {
            //create a reference to that block and store it in this.parentBlock
            this.parentBlock = siteBuilder.site.sitePages[i].blocks[x];
          }
        }

        // For popups
        for (var y = 0; y < siteBuilder.site.sitePages[i].popups.length; y++) {
          //if the popup's frame matches this element's parent frame
          if (
            siteBuilder.site.sitePages[i].popups[y].frame === this.parentFrame
          ) {
            //create a reference to that block and store it in this.parentBlock
            this.parentBlock = siteBuilder.site.sitePages[i].popups[y];
          }
        }
      }
    };

    //build the toolbar
    this.buildToolBar = function() {
      if (Object.keys(this.toolbar).length !== 0) return false;

      //remove all existing toolbars
      $(this.element)
        .closest('body')
        .find('.canvasElToolbar')
        .remove();

      var toolbar = document.createElement('div'),
        buttonEdit = document.createElement('button'),
        buttonContent,
		buttonClone = document.createElement('button'),
		buttonDelete =  document.createElement('button'),
        elOffset = $(this.element).offset(),
        spaceBelowElement;

      //the content button
      if (this.element.parentNode.hasAttribute('data-content')) {
        buttonContent = document.createElement('button');
        buttonContent.classList.add('content');
		buttonContent.setAttribute('title','Edit text');
        buttonContent.innerHTML =
          '<img src="/img/icons/design-24px-glyph_text@2x.png">';
        buttonContent.addEventListener(
          'click',
          function(e) {
            publisher.publish('onContentClick', this);
          }.bind(this)
        );
        toolbar.appendChild(buttonContent);
      }
		  
      //the edit button
      buttonEdit.classList.add('edit');
      toolbar.appendChild(buttonEdit);
      if (this.element.getAttribute('data-component') === 'embed') {
        buttonEdit.innerHTML = '<i class="fa fa-code edit-source-btn"></i>';
		buttonEdit.setAttribute('title','Edit source code');
        buttonEdit.addEventListener(
          'click',
          function() {
            publisher.publish('deSelectAllCanvasElements');
            this.clickHandler(this, true);
            this.destroyToolBar();
          }.bind(this)
        )
      } else
	  {
        buttonEdit.innerHTML =
		  '<i class="fa fa-cog edit-source-btn"></i>';
		buttonEdit.setAttribute('title','Edit in Style Editor');

		buttonEdit.addEventListener(
		'click',
		function() {
		  publisher.publish('deSelectAllCanvasElements');
		  this.clickHandler(this);
		  this.destroyToolBar();
		}.bind(this)
		);
	  }
	 //the Clone element button
	  if (this.element.getAttribute('data-component') !== 'embed' &&
		  !this.element.classList.contains('block') &&
		  !this.element.hasAttribute('data-container')
		  ) {
		  buttonClone.classList.add('clone');
		  buttonClone.setAttribute('title','Clone this element');
		  toolbar.appendChild(buttonClone);
		  buttonClone.addEventListener(
			'click',
			function() {
			  
			  
			  this.clickHandler(this);	  
			  this.destroyToolBar();
			  publisher.publish('closeStyleEditor');
			  publisher.publish('cloneElement');
			  publisher.publish('deSelectAllCanvasElements');
			  
			}.bind(this)
		  );
		  buttonClone.innerHTML =
		  '<i class="fa fa-clone edit-source-btn"></i>';
	  };
	 //the delete element button
	  if (//this.element.getAttribute('data-component') !== 'embed' && 
	      !this.element.classList.contains('block') &&
		  !this.element.hasAttribute('data-container')
	      ) {
		  buttonDelete.classList.add('delete');
		  buttonDelete.setAttribute('title','Delete this element');

		  toolbar.appendChild(buttonDelete);
		  buttonDelete.addEventListener(
			'click',
			function() {
			/*	
			  publisher.publish('deleteElement');
			  this.clickHandler(this);
			  this.destroyToolBar();*/
			  //publisher.publish('deSelectAllCanvasElements');
			  //publisher.publish('closeStyleEditor');
			  this.clickHandler(this);
			  this.destroyToolBar();
			  publisher.publish('closeStyleEditor');       
			  $("#deleteElement").modal('show');
			}.bind(this)
		  );
		  buttonDelete.innerHTML =
		  '<i class="fa fa-trash edit-source-btn"></i>';
	  };
      this.toolbar = toolbar;

      //toolbar hover events
      $(toolbar)
        .on(
          'mouseenter',
          function() {
            this.toolbar.setAttribute('data-hover', 'true');
            $(this.element)
              .parents('*[data-selector]')
              .attr('data-nointent', true);
          }.bind(this)
        )
        .on(
          'mouseleave',
          function() {
            this.toolbar.removeAttribute('data-hover');

            setTimeout(
              function() {
                if (!this.element.hasAttribute('data-hover')) {
                  this.element.classList.remove('sb_hover');
                  this.destroyToolBar();
                }
              }.bind(this),
              100
            );
          }.bind(this)
        );

      toolbar.classList.add('canvasElToolbar');

      //determine positioning

      spaceBelowElement =
        this.parentFrame.offsetHeight -
        this.element.offsetHeight -
        elOffset.top;

      if (
        elOffset.top === 0 &&
        $(this.element)[0].offsetHeight === this.element.offsetHeight
      ) {
        //full height, show inside top left corner
        toolbar.style.top = '0px';
        toolbar.classList.add('inside');
      } else if (elOffset.top < 40 && spaceBelowElement < 50) {
        //full height, show inside top left corner
        toolbar.style.top = elOffset.top + 'px';
        toolbar.classList.add('inside');
      } else if (elOffset.top < 40) {
        //close to the top
        toolbar.style.top = elOffset.top + this.element.offsetHeight + 'px';
        toolbar.classList.add('bottom');
      } else {
        //not close to top, display above to the left
        toolbar.style.top = elOffset.top - 30 + 'px';
        toolbar.classList.add('top');
      }

      //carousel?
      if (
        $(this.element)
          .closest('div[data-component="carousel"]')
          .size() > 0
      ) {
        toolbar.style.left =
          this.element.offsetWidth / 2 + elOffset.left + 'px';
      } else {
        toolbar.style.left = elOffset.left + 'px';
      }

      $(this.element)
        .closest('body')
        .append(toolbar);
    };

    //destroy the toolbar
    this.destroyToolBar = function() {
      if (Object.keys(this.toolbar).length !== 0) {
        this.toolbar.remove();
        this.toolbar = {};
      }
    };

    this.setParentFrame();

    /*
            is this block sandboxed?
        */

    if (this.parentFrame.getAttribute('data-sandbox')) {
      this.sandbox = this.parentFrame.getAttribute('data-sandbox');
    }
  };
})();
