/*
    
    Do not make changes directly to this file, instead provide your custom settings in config_custom.js (this requires
    rebuilding using Webpack)

*/

(function() {
  'use strict';

  var cConfig = require('./config_custom.js');

  module.exports.google_api_url = cConfig.google_api_url
    ? cConfig.google_api_url
    : 'https://fonts.googleapis.com/css?family=';

  module.exports.pageContainer = cConfig.pageContainer
    ? cConfig.pageContainer
    : '#page';

  module.exports.bodyPaddingClass = cConfig.bodyPaddingClass
    ? cConfig.bodyPaddingClass
    : 'bPadding';

  module.exports.editableItems = cConfig.editableItems
    ? cConfig.editableItems
    : {
        'span.fa, i.fa': ['color', 'font-size'],
        '.bg.bg1': ['background-color'],
        'nav a': ['color', 'font-weight', 'text-transform', 'background-color', 'border-color'],
        img: [
          'border-top-left-radius',
          'border-top-right-radius',
          'border-bottom-left-radius',
          'border-bottom-right-radius',
          'border-color',
          'border-style',
          'border-width'
        ],
        'hr.dashed': ['border-color', 'border-width'],
        '.divider > span': ['color', 'font-size'],
        'hr.shadowDown': ['margin-top', 'margin-bottom'],
        'ul > li > a': ['color'],
        '.social a': ['color'],
        '.bg.bg1, .bg.bg2, .header10, .header11': [
          'background-image',
          'background-color'
        ],
        '.frameCover': [],
        '*[data-component="embed"]': [],
        '#pricing_table2 .pricing2 .bottom li': ['content'],

        'a.btn, button.btn': [
          'margin-top',
          'margin-bottom',
          'background-color',
          'border-color',
          'color',
          'btn-size',
          'btn-block'
        ],

        '*[data-container="button"] > .btn': [
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right'
        ],
        '*[data-container="divider"] > hr': [
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right'
        ],
        '*[data-container="form"] > form': [
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right'
        ],
        '*[data-component="heading"] > *': [
          'font-size',
          'color',
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right',
          'text-align'
        ],
        '*[data-component="icon"] > span.fa, *[data-component="icon"] > a > span.fa': [
          'font-size',
          'color',
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right'
        ],
        '*[data-component="image"] > img, *[data-component="image"] > a img': [
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right',
          'image-style'
        ],
        '*[data-component="text"] > *': [
          'font-size',
          'color',
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right',
          'text-align'
        ],
        '*[data-component="video"] > .videoWrapper': [
          'margin-top',
          'margin-bottom',
          'margin-left',
          'margin-right'
        ],
        '*[data-component="list"] > ul': ['margin-top', 'margin-bottom'],
        '*[data-component="list"] > ul > li': ['margin-top', 'margin-bottom'],
        '*[data-component="countdown"] > div': ['margin-top', 'margin-bottom'],
        '.inline-wrapper': ['inline-position'],
        '*[data-component="grid"]': ['margin-top', 'margin-bottom'],
        form: ['margin-top', 'margin-bottom'],
          textarea: ['margin-top', 'margin-bottom'],
          input: ['margin-top', 'margin-bottom'],
        nav: [
          'navbar-style',
          'navbar-alignment',
          'margin-top',
          'margin-bottom'
        ],
        '.bloxby-bg-styler': [
          'bloxby-bg-styler-padding',
          'bloxby-border-radius',
          'bloxby-bg',
          'bloxby-border',
          'background-image',
          'background-color'
        ],

        'ul[class*="social-"]': ['social-list-size', 'list-align'],

        '*[data-component="map"] .mapOverlay': [
          'height',
          'margin-top',
          'margin-bottom'
        ],

        '.block': [
          'background-color',
          'background-color-overlay',
          'background-image',
          'padding-top',
          'padding-bottom',
          'background-position',
          'parallax'
        ],

        '.navbar-brand img': ['margin-left', 'margin-right', 'height'],

        '.bloxby-block-background, .background-cover': [
          'background-color',
          'background-image',
          'padding-top',
          'padding-bottom',
          'background-position'
        ],
        '.bloxby-content-background': ['background-color'],
        '.countdown > div': [
          'background-color',
          'color',
          'font-size',
          'border-radius'
        ],
        '.countdown > div > span': [
          'background-color',
          'color',
          'border-radius'
        ]
      };

  module.exports.inputAppend = cConfig.inputAppend
    ? cConfig.inputAppend
    : [
        'font-size',
        'margin-top',
        'margin-bottom',
        'margin-left',
        'margin-right',
        'padding-top',
        'padding-bottom',
        'padding-left',
        'padding-right',
        'height',
        'width'
      ];

  module.exports.editableItemOptions = cConfig.editableItemOptions
    ? cConfig.editableItemOptions
    : {
        'nav a : font-weight': [
          '100',
          '200',
          '300',
          '400',
          '500',
          '600',
          '700',
          '800',
          '900'
        ],
        'a.btn : border-radius': ['0px', '4px', '10px'],
        'img : border-style': ['none', 'dotted', 'dashed', 'solid'],
        'img : border-width': ['1px', '2px', '3px', '4px'],
        'h1, h2, h3, h4, h5, p : font-family': [
          'default',
          'Lato',
          'Helvetica',
          'Arial',
          'Times New Roman'
        ],
        'h2 : font-family': [
          'default',
          'Lato',
          'Helvetica',
          'Arial',
          'Times New Roman'
        ],
        'h3 : font-family': [
          'default',
          'Lato',
          'Helvetica',
          'Arial',
          'Times New Roman'
        ],
        'p : font-family': [
          'default',
          'Lato',
          'Helvetica',
          'Arial',
          'Times New Roman'
        ],
        '.block : parallax': ['off', 'on']
      };

  module.exports.customStyleDropdowns = cConfig.customStyleDropdowns
    ? cConfig.customStyleDropdowns
    : {
        'btn-style': {
          label: 'Button style',
          values: {
            default: 'btn-default',
            primary: 'btn-primary',
            success: 'btn-success',
            info: 'btn-info',
            warning: 'btn-warning',
            danger: 'btn-danger'
          },
          default: 'btn-default'
        },
        'btn-size': {
          label: 'Button size',
          values: {
            huge: 'btn-hg',
            default: '',
            small: 'btn-sm',
            'extra small': 'btn-xs'
          },
          default: ''
        },
        'btn-block': {
          label: 'Button block',
          values: {
            Block: 'btn-block',
            'No block': ''
          },
          default: ''
        },
        'navbar-style': {
          label: 'Navigation bar style',
          values: {
            Default: 'navbar-default',
            Inverse: 'navbar-inverse',
            Plain: 'navbar-plain'
          }
        },
        'navbar-alignment': {
          label: 'Navigation alignment',
          values: {
            Left: 'bloxby-navbar-left',
            'Left / right': 'bloxby-navbar-left-right',
            Right: 'bloxby-navbar-right',
            Centered: 'bloxby-navbar-centered'
          }
        },
        'background-position': {
          label: 'Background alignment',
          values: {
            'Top left': 'top-left',
            'Top center': 'top-center',
            'Top right': 'top-right',
            'Bottom left': 'bottom-left',
            'Bottom center': 'bottom-center',
            'Bottom right': 'bottom-right',
            'Center center': 'center-center'
          },
          default: 'top-left'
        },
        'inline-position': {
          label: 'Inline positioning',
          values: {
            'Pull left': 'pull-left',
            'Pull center': 'pull-center',
            'Pull right': 'pull-right'
          }
        },
        'bloxby-bg-styler-padding': {
          label: 'Padding',
          values: {
            'No padding': 'padding-no',
            'Medium padding': 'padding-md',
            'Large padding': 'padding-lg'
          },
          default: 'padding-no'
        },
        'bloxby-border-radius': {
          label: 'Border radius',
          values: {
            'No radius': 'border-radius-no',
            'Small radius': 'border-radius-sm',
            'Medium radius': 'border-radius-md',
            'Large radius': 'border-radius-lg'
          },
          default: 'border-radius-no'
        },
        'bloxby-bg': {
          label: 'Background',
          values: {
            Light: 'bg-light',
            Dark: 'bg-dark',
            None: 'bg-none'
          },
          default: 'bg-none'
        },
        'bloxby-border': {
          label: 'Border',
          values: {
            'No border': 'border-none',
            'Light + thin': 'border-light-thin',
            'Light + thick': 'border-light-thick'
          },
          default: 'border-none'
        },
        'image-style': {
          label: 'Image style',
          values: {
            Responsive: 'img-responsive',
            Rounded: 'img-rounded',
            Circle: 'img-circle',
            Thumbnail: 'img-thumbnail'
          },
          default: 'img-responsive'
        },
        'social-list-size': {
          label: 'Icon size',
          values: {
            Small: 'social-basic-sm',
            Medium: 'social-basic-md'
          },
          default: 'social-basic-sm'
        },
        'list-align': {
          label: 'List alignment',
          values: {
            Left: 'list-left',
            Right: 'list-right'
          },
          default: 'list-left'
        },
        'text-align': {
          label: 'Alignment',
          values: {
            Left: 'text-left',
            Right: 'text-right',
            Center: 'text-center'
          },
          default: 'text-left'
        }
      };

  module.exports.responsiveModes = cConfig.responsiveModes
    ? cConfig.responsiveModes
    : {
        desktop: '100%',
        mobile: '480px',
        tablet: '1024px'
      };

  module.exports.autoSaveTimeout = cConfig.autoSaveTimeout
    ? cConfig.autoSaveTimeout
    : 300000;

  module.exports.sourceCodeEditSyntaxDelay = cConfig.sourceCodeEditSyntaxDelay
    ? cConfig.sourceCodeEditSyntaxDelay
    : 10000;

  module.exports.cssUrls = ['../css/blocks.css'];

  module.exports.externalJS = cConfig.externalJS
    ? cConfig.externalJS
    : ['/build/inblock.bundle.js'];

  module.exports.aceTheme = cConfig.externalJS
    ? cConfig.externalJS
    : 'twilight'; //Theme for the Ace editor, more info here: https://github.com/ajaxorg/ace/tree/master/lib/ace/theme

  module.exports.navSelector = cConfig.navSelector
    ? cConfig.navSelector
    : 'nav';
  module.exports.navActiveClass = cConfig.navActiveClass
    ? cConfig.navActiveClass
    : 'active';

  module.exports.sentApiURL = cConfig.sentApiURL
    ? cConfig.sentApiURL
    : '/sent/api/';

  module.exports.imageLightboxWrapper = cConfig.imageLightboxWrapper
    ? cConfig.imageLightboxWrapper
    : '.overlay-wrapper';
  module.exports.imageLightboxAttr = cConfig.imageLightboxAttr
    ? cConfig.imageLightboxAttr
    : 'data-gallery';

  module.exports.sideMenuArrowSVG = cConfig.sideMenuArrowSVG
    ? cConfig.sideMenuArrowSVG
    : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><polygon fill="#bdc3c7" points="4.9,15.7 3.4,14.3 9.7,8 3.4,1.7 4.9,0.3 12.6,8 "></polygon></g></svg>';
	
  module.exports.sideMenuCatBlockedIcon = cConfig.sideMenuCatBlockedIcon
    ? cConfig.sideMenuCatBlockedIcon
    : '<i class="fa fa-lock sideMenuIconCatBlocked" aria-hidden="true"></i>';
	
  module.exports.froalaConfig = cConfig.froalaConfig
    ? cConfig.froalaConfig
    : {
        toolbarInline: true,
		tooltips:false,
        charCounterCount: false,
        toolbarButtons: [
          'bold',
          'italic',
          'underline',
          '|',
          'fontFamily',
          'fontSize',
          'color',
          '|',
          'paragraphFormat',
          'align',
          '|',
          'insertLink',
          '|',
          'clearFormatting',
          '|',
          'undo',
          'redo'
        ],
        linkInsertButtons: ['linkBack'],
        paragraphFormat: {
          N: 'Normal',
          H1: 'Heading 1',
          H2: 'Heading 2',
          H3: 'Heading 3',
          H4: 'Heading 4',
          H5: 'Heading 5'
        },
        nestedIframes: true,
        fontFamily: {
          'Arial,Helvetica,sans-serif': 'Arial',
          'Georgia,serif': 'Georgia',
          'Impact,Charcoal,sans-serif': 'Impact',
          'Tahoma,Geneva,sans-serif': 'Tahoma',
          "'Times New Roman',Times,serif": 'Times New Roman',
          'Verdana,Geneva,sans-serif': 'Verdana'
        }
      };

  module.exports.runInBlocks = cConfig.runInBlocks
    ? cConfig.runInBlocks
    : function() {
        $('.carousel').each(function() {
          $(this).carousel('pause');

          $(this)
            .find('a.right.carousel-control')
            .on(
              'click',
              function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).carousel('next');
                $(this).carousel('pause');
              }.bind(this)
            );

          $(this)
            .find('a.left.carousel-control')
            .on(
              'click',
              function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).carousel('prev');
                $(this).carousel('pause');
              }.bind(this)
            );

          var theCarousel = this;

          $(this)
            .find('.carousel-indicators li')
            .on('click', function(e) {
              e.stopPropagation();
              $(theCarousel).carousel($(this).index());
              setTimeout(function() {
                $(theCarousel).carousel('pause');
              }, 1000);

              $(this)
                .siblings()
                .removeClass('active');
              $(this).addClass('active');
            });

          $(this).on('slid.bs.carousel', function() {
            parent.postMessage('onFrameContentChanged', '*');
          });
        });

        $('*[data-parallax]').each(function() {
          this.style.backgroundImage =
            "url('" + this.getAttribute('data-image-src') + "')";
        });
      };

  module.exports.rebuildSlideshowNavigation = cConfig.rebuildSlideshowNavigation
    ? cConfig.rebuildSlideshowNavigation
    : function(slideshow) {
        var theOL = document.createElement('OL'),
          theLI,
          slide,
          theSlides = slideshow.querySelectorAll('.carousel-inner .item'),
          counter = 0,
          nonActive = true;

        //remove current nav
        slideshow.querySelector('.carousel-indicators').remove();

        //rebuild
        for (slide in theSlides) {
          if (theSlides.hasOwnProperty(slide)) {
            theLI = document.createElement('LI');
            theLI.setAttribute('data-target', slideshow.id);
            theLI.setAttribute('data-slide-to', counter);

            if (theSlides[slide].classList.contains('active')) {
              theLI.classList.add('active');
              nonActive = false;
            }

            theOL.appendChild(theLI);

            counter++;
          }
        }

        theOL.classList.add('carousel-indicators');

        $(slideshow)
          .find('.carousel-indicators-wrapper')
          .prepend(theOL);

        //anything active?
        if (nonActive) {
          $(slideshow)
            .find('.carousel-indicators li:first-child')
            .addClass('active');
          $(slideshow)
            .find('.carousel-inner .item:first-child')
            .addClass('active');
          $(slideshow).carousel(0);
        }

        $(slideshow).carousel();
        $(slideshow).carousel('pause');

        $(slideshow)
          .find('.carousel-indicators li')
          .on('click', function(e) {
            e.stopPropagation();
            $(slideshow).carousel($(this).index());
            $(slideshow).carousel('pause');

            $(this)
              .siblings()
              .removeClass('active');
            $(this).addClass('active');
          });
      };

  module.exports.inBlockBeforeSave = cConfig.inBlockBeforeSave
    ? cConfig.inBlockBeforeSave
    : function(frameDocument) {
        //reset all Bootstrap carousels to the first slide

        $(frameDocument)
          .find('.carousel')
          .each(function() {
            $(this)
              .find('.carousel-indicators li')
              .removeClass('active');
            $(this)
              .find('.carousel-indicators li:first-child')
              .addClass('active');

            $(this)
              .find('.carousel-inner .item')
              .removeClass('active');
            $(this)
              .find('.carousel-inner .item:first-child')
              .addClass('active');

            //$(this).carousel(0);
          });

        $(frameDocument)
          .find('*[data-bloxby-editor]')
          .each(function() {
            $(this).froalaEditor('destroy');
            this.classList.remove('sb_open');
          });
      };
})();
