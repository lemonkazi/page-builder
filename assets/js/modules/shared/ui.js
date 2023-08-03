/* globals siteUrl:false, baseUrl:false; */
(function () {
    "use strict";

    var publisher = require('../../vendor/publisher');

    var appUI = {

        firstMenuWidth: 190,
        secondMenuWidth: 300,
        loaderAnimation: document.getElementById('loader'),
        siteUrl: siteUrl,
        baseUrl: baseUrl,
        gridViewMode: false,

        fixedSidebarButtons: document.querySelectorAll('#fixedSidebar button'),
        buttonCloseSideSecond: document.querySelectorAll('*[data-js="closeSideSecond"]'),

        theBody: document.querySelector('body'),

        sideSecondBlocks: document.querySelector('*[data-sidesecond="blocks"]'),
        sideSecondComponents: document.querySelector('*[data-sidesecond="components"]'),
        sideSecondTemplates: document.querySelector('*[data-sidesecond="templates"]'),

        setup: function(){

            publisher.subscribe('onStyleEditorOpen', function () {
                appUI.closeOpenSide();
            });
			publisher.subscribe('onStartDrag', function(mode) {
                appUI.closeOpenSide();
            });
            // Fade the loader animation
            $(appUI.loaderAnimation).fadeOut();

            // Tabs
            $(".nav-tabs a").on('click', function (e) {
                e.preventDefault();
                $(this).tab("show");
            });

            $("select.select:not([data-with-search])").select2({
                minimumResultsForSearch: -1
            });

            $("select.select[data-with-search]").select2();

            $(':radio, :checkbox').radiocheck();

            // Tooltips
            $("[data-toggle=tooltip]").tooltip("hide");

            // Table: Toggle all checkboxes
            $('.table .toggle-all :checkbox').on('click', function () {
                var $this = $(this);
                var ch = $this.prop('checked');
                $this.closest('.table').find('tbody :checkbox').radiocheck(!ch ? 'uncheck' : 'check');
            });

            // Add style class name to a tooltips
            $(".tooltip").addClass(function() {
                if ($(this).prev().attr("data-tooltip-style")) {
                    return "tooltip-" + $(this).prev().attr("data-tooltip-style");
                }
            });

            $(".btn-group").on('click', "a", function() {
                $(this).siblings().removeClass("active").end().addClass("active");
            });

            // Focus state for append/prepend inputs
            $('.input-group').on('focus', '.form-control', function () {
                $(this).closest('.input-group, .form-group').addClass('focus');
            }).on('blur', '.form-control', function () {
                $(this).closest('.input-group, .form-group').removeClass('focus');
            });

            // Table: Toggle all checkboxes
            $('.table .toggle-all').on('click', function() {
                var ch = $(this).find(':checkbox').prop('checked');
                if ( typeof($(this).closest('.table').find('tbody :checkbox').checkbox) === 'function' ) {
                    $(this).closest('.table').find('tbody :checkbox').checkbox(!ch ? 'check' : 'uncheck');
                }
            });

            // Table: Add class row selected
            $('.table tbody :checkbox').on('check uncheck toggle', function (e) {
                var $this = $(this)
                , check = $this.prop('checked')
                , toggle = e.type === 'toggle'
                , checkboxes = $('.table tbody :checkbox')
                , checkAll = checkboxes.length === checkboxes.filter(':checked').length;

                $this.closest('tr')[check ? 'addClass' : 'removeClass']('selected-row');
                if (toggle) $this.closest('.table').find('.toggle-all :checkbox').checkbox(checkAll ? 'check' : 'uncheck');
            });

            // Switch
            //$("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
            $('[data-toggle="switch"]').bootstrapSwitch();

            //modal links which open a tab inside that modal
            $('*[data-toggle="modal"][data-open-tab]').on('click', function () {
                $(this.getAttribute('href') + ' .nav-tabs li:nth-child(' + Number(this.getAttribute('data-open-tab')) + ') a').tab('show');
            });

            publisher.subscribe('onSideClose', function () {
                appUI.closeOpenSide();
            });

        },

        setupNew: function () {

            //fixed sidebar buttons
            for (var button of Array.from(this.fixedSidebarButtons)) {

                button.addEventListener('click', (event) => this.loadSide(event.currentTarget));

            }

            //close second side button
            for ( var closeButton of Array.from(this.buttonCloseSideSecond) ) {

                closeButton.addEventListener('click', () => this.closeOpenSide());

            }

            //block category buttons
            $(this.sideSecondBlocks).on('click', 'nav button:not(.active)', (event) => this.openCategory(event.currentTarget)
            ).on('click', 'nav button.active', (event) => this.closeCategory(event.currentTarget));

            $(this.sideSecondComponents).on('click', 'nav button:not(.active)', (event) => this.openCategory(event.currentTarget)
            ).on('click', 'nav button.active', (event) => this.closeCategory(event.currentTarget));

            $(this.sideSecondTemplates).on('click', 'nav button:not(.active)', (event) => this.openCategory(event.currentTarget)
            ).on('click', 'nav button.active', (event) => this.closeCategory(event.currentTarget));

        },

        loadSide: function (clickedButton) {

            var button,
                toHide,
                toShow,
                delay;

            //this.theBody.classList.add('sideSecondOpen');
			
			//$("button[class*='active']","#builder").css("position","fixed");
            //button state
            for ( button of Array.from(this.fixedSidebarButtons) ) {

                if ( button === clickedButton && button.classList.contains('active') ) {

                    this.closeOpenSide();

                    return false;

                } else {
                    
                    button.classList.remove('active');
                }

            }
			
            clickedButton.classList.add('active');
			
            //hide opens first
            toHide = document.querySelector('*[data-sidesecond].open');

            if ( toHide ) toHide.classList.remove('open');
			
			//$("nav>button[class*='active']").css("width", "250px");
			//$("#builder > div.builderLayout > div.sideSecond.open > div > nav > button.active").css("position","fixed");

            toShow = document.querySelector('*[data-sidesecond="'+clickedButton.getAttribute('data-side')+'"]');

			delay = 0;
            if ( toHide ) {
                
                setTimeout( () => toShow.classList.add('open'), 0);
				
                //delay = 1100;
            
            } else {
                
                toShow.classList.add('open');
                //delay = 1000;
            
            }

            setTimeout( () => {
                //publisher.publish('canvasWidthChanged');
                publisher.publish('onSideOpen', clickedButton.getAttribute('data-side'));
            }, delay);
			//setTimeout( () => $("button[class*='active']","#builder").css("position","fixed"), 500);

        },

        closeOpenSide: function () {

            var button,
                toHide;
							
            for ( button of Array.from(this.fixedSidebarButtons) ) {
                button.classList.remove('active');
            }
			
            toHide = document.querySelector('*[data-sidesecond].open');

            if ( toHide ) {
                toHide.classList.remove('open');
                setTimeout( () => {
                    //publisher.publish('canvasWidthChanged');
                    publisher.publish('onSideClose');
                }, 0);
            }
			//$("button[class*='active']","#builder").css("position","absolute");
			if ($(".canvasWrapper").css("margin-left") == "236px")
				$(".canvasWrapper").css("margin-left","0");

        },

        openCategory: function (categoryButton) {

            var allButtons,
                button,
                image,
                images;

            if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'blocks' ) allButtons = this.sideSecondBlocks.querySelectorAll('nav button');
            else if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'components' ) allButtons = this.sideSecondComponents.querySelectorAll('nav button');
            else if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'templates' ) allButtons = this.sideSecondTemplates.querySelectorAll('nav button');

            //hide others
            for ( button of Array.from(allButtons) ) {
                if ( button !== categoryButton ) {
                    button.classList.add('invisible');
                    button.classList.add('noshow');
                }
            }

            categoryButton.classList.add('active');
			
            categoryButton.nextSibling.classList.add('unhide');

            //lazy load images
            images = categoryButton.nextSibling.querySelectorAll('img[data-original-src]');

            for ( image of Array.from(images) ) {
                image.setAttribute('src', image.getAttribute('data-original-src'));
                image.removeAttribute('data-original-src');
            }

        },

        closeCategory: function (categoryButton) {

            var allButtons,
                button;

            if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'blocks' ) allButtons = this.sideSecondBlocks.querySelectorAll('nav button.noshow');
            else if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'components' ) allButtons = this.sideSecondComponents.querySelectorAll('nav button.noshow');
            else if ( $(categoryButton).closest('*[data-sidesecond]').attr('data-sidesecond') === 'templates' ) allButtons = this.sideSecondTemplates.querySelectorAll('nav button.noshow');

            categoryButton.classList.remove('active');
            categoryButton.nextSibling.classList.remove('unhide');
			categoryButton.removeAttribute('style');
            for ( button of Array.from(allButtons) ) {
                button.classList.remove('noshow');
                button.classList.remove('invisible');
            }

        }

    };

    //initiate the UI
    appUI.setup();
    appUI.setupNew();


    //**** EXPORTS
    module.exports.appUI = appUI;

}());