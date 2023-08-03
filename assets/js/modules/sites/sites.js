/* globals _: false */
(function () {
	"use strict";

	let appUI = require('../shared/ui.js').appUI;
	let notify = require('../shared/notify');
    var pageCount = 1;
    var count = 1;

	var sites = {
        
        wrapperSites: document.getElementById('sites'),
        selectUser: document.getElementById('userDropDown'),
        selectSort: document.getElementById('sortDropDown'),
        buttonDeleteSite: document.getElementById('deleteSiteButton'),
		buttonsDeleteSite: document.querySelectorAll('.deleteSiteButton'),
		divSites: document.getElementById('sites'),
		buttonSearchSites: document.getElementById('buttonSearchSites'),
		inputSearchSites: document.getElementById('inputSearchSites'),
		ulCatList: document.getElementById('ulCatList'),
		ulTemplateList: document.getElementById('ulTemplateList'),
		divEmptyCanvas: document.getElementById('divEmptyCanvas'),
		linkNewSite: document.getElementById('linkNewSite'),
        
        init: function() {
            loadResults();
            $(this.selectUser).on('change', this.filterUser);
            $(this.selectSort).on('change', this.changeSorting);
            $(this.buttonDeleteSite).on('click', this.deleteSite);

			$('img', this.divSites).lazyload();

			$(this.buttonSearchSites).on('click', this.filterSiteName);

			$(this.inputSearchSites).on('keyup', (e) => {

		        let code = (e.keyCode ? e.keyCode : e.which);

		        if ( code === 13 ) this.filterSiteName();

		        return false;

		    });

            $(document).on('click','a.deleteSiteButton', this.deleteSite);

		    $(sites.ulCatList).on('click', 'button:not(.active)', function () {

		    	let catID = this.getAttribute('data-cat-id');

		    	// button
		    	$(sites.ulCatList).find('button').removeClass('active');
		    	this.classList.add('active');

		    	sites.deselectTemplate();

		    	// templates
		    	if ( catID === 'canvas' ) {
		    		$(sites.linkNewSite).removeClass('disabled');
		    		$('.templateList').hide();
		    		$(sites.divEmptyCanvas).fadeIn();
		    	} else {
		    		$(sites.linkNewSite).addClass('disabled');
		    		$(sites.divEmptyCanvas).hide();
		    		$('.templateList').show();
		    		$('.templateList li').hide();
		    		$('.templateList li[data-cat-id="' + catID + '"]').fadeIn();
		    	}

		    });

		    $(sites.ulTemplateList).on('click', 'a', function () {

		    	let active = this.classList.contains('active');

		    	$(sites.linkNewSite).removeClass('disabled');

		    	sites.deselectTemplate();

		    	if ( !active ) {
		    		this.classList.add('active');
		    		sites.activateTemplate(this.getAttribute('data-template-id'));
		    	}

		    	return false;

		    });

		    // Site owner select event
            $(document).on("change","select.selectSiteOwner", function () {
                var site_id = this.getAttribute('data-site-id');
                var user_id = this.value
		    	$.ajax({
		    		url: appUI.siteUrl + "sites/changeOwner/" + this.getAttribute('data-site-id') + "/" + this.value,
                    type: 'post',
                    dataType: 'json'
		    	}).done(function (ret) {

		    		let notifyConfig = notify.config;

		            if ( ret.responseCode === 1 ) {
		            	notifyConfig.className = "joy";
		            	$('#site_' + site_id).attr('data-user-id', user_id);
                        sites.filterUser()
		            } else {
		            	notifyConfig.className = "bummer";
		            }

		            $.notify(ret.responseHTML, notifyConfig);

		    	});

		    });

        },

        /*
			Un-selects the template
        */
        deselectTemplate: function () {

        	$(sites.linkNewSite).removeClass('disabled');

        	sites.activateTemplate(0);
        	$(sites.ulTemplateList).find('a').removeClass('active');

        },


        /*
			Selects a template
        */
        activateTemplate: function (templateID) {

        	if ( templateID === 0 ) sites.linkNewSite.href = appUI.siteUrl + "sites/create/";
        	else sites.linkNewSite.href = appUI.siteUrl + "sites/create/" + templateID;

        },


        /*
			Search sites by name site name
        */
        filterSiteName: function () {
        	if (sites.inputSearchSites.value !== '') {
				// filter by site name
                $('#sites .site:not(.empty)').hide();
                $('div.site[data-site-name*="'+ sites.inputSearchSites.value + '" i]').fadeIn(500);
			} else {
				// show all
                $('#sites .site:not(.empty)').hide().fadeIn(500);
			}

			$('img', sites.divSites).lazyload();

        },
        
        
        /*
            filters the site list by selected user
        */
        filterUser: function() {

            if( $('#userDropDown').val() === 'All' || $('#userDropDown').val() === '' ) {
                $('#sites .site:not(.empty)').hide().fadeIn(500);
            } else {
                $('#sites .site:not(.empty)').hide();
                $('#sites').find('[data-user-id="'+$('#userDropDown').val()+'"]').fadeIn(500);
            }

            $('img', sites.divSites).lazyload();
            
        },
        
        
        /*
            chnages the sorting on the site list
        */
        changeSorting: function() {

            var sites;
            
            if( $(this).val() === 'NoOfPages' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
                    
                    var an = a.getAttribute('data-pages');
					var bn = b.getAttribute('data-pages');
				
					if(an > bn) {
						return -1;
					}
				
					if(an < bn) {
						return 1;
					}
				
					return 0;
				
				} );
			
				sites.detach().appendTo( $('#sites') );
		
			} else if( $(this).val() === 'CreationDate' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
					if (a.getAttribute('data-created')) {

						var an = a.getAttribute('data-created').replace(/-/g, "");
						var bn = b.getAttribute('data-created').replace(/-/g, "");

						if(an > bn) {
							return -1;
						}

						if(an < bn) {
							return 1;
						}

						return 0;
                    }
				
				} );
			
				sites.detach().appendTo( $('#sites') );
		
			} else if( $(this).val() === 'LastUpdate' ) {
		
				sites = $('#sites .site');
			
				sites.sort( function(a,b){
                    if (a.getAttribute('data-update')){
                        var an = a.getAttribute('data-update').replace(/-/g, "");
                        var bn = b.getAttribute('data-update').replace(/-/g, "");

                        if(an > bn){
                            return -1;
                        }

                        if(an < bn){
                            return 1;
                        }

                        return 0;
                    }
				});

				sites.detach().appendTo( $('#sites') );
		
			}

			$('img', sites.divSites).lazyload();
            
        },
        
        
        /*
            deletes a site
        */
        deleteSite: function(e) {

            e.preventDefault();
            
            $('#deleteSiteModal .modal-content p').show();
            
            //remove old alerts
            $('#deleteSiteModal .modal-alerts > *').remove();
            $('#deleteSiteModal .loader').hide();
		
            var toDel = $(this).closest('.site');
            var delButton = $(this);
           
            $('#deleteSiteModal button#deleteSiteButton').show();
            $('#deleteSiteModal').modal('show');
           
            $('#deleteSiteModal button#deleteSiteButton').unbind('click').click(function(){

                $(this).addClass('disabled');
                $('#deleteSiteModal .loader').fadeIn(500);
               
                $.ajax({
                    url: appUI.siteUrl+"sites/trash/"+delButton.attr('data-siteid'),
                    type: 'post',
                    dataType: 'json'
                }).done(function(ret){
                    
                    $('#deleteSiteModal .loader').hide();
                    $('#deleteSiteModal button#deleteSiteButton').removeClass('disabled');
                   
                    if( ret.responseCode === 0 ) {//error
                       
                        $('#deleteSiteModal .modal-content p').hide();
                        $('#deleteSiteModal .modal-alerts').append( $(ret.responseHTML) );
                   
                    } else if( ret.responseCode === 1 ) {//all good
                       
                        $('#deleteSiteModal .modal-content p').hide();
                        $('#deleteSiteModal .modal-alerts').append( $(ret.responseHTML) );
                        $('#deleteSiteModal button#deleteSiteButton').hide();
                       
                        toDel.fadeOut(800, function(){
                            $(this).remove();
                        });
                    }
               
                })
            });
            
        }
        
    };

    sites.init();

    $(window).scroll(function () {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {

            if(pageCount > count){
                count++;
                loadResults();
            }
        }
    });

    function loadResults() {
        $(".loading").show();
        $.ajax({
            url: appUI.baseUrl + "sites/getLimitSites",
            type: "POST",
            data: {page: count, delay: 3},
            dataType: "json",
            success: function (data) {
                if(data.sites.length == 0){
            		$('.no-sites').removeClass('hidden');
				}else{
                    $('.sites-list').removeClass('hidden');
				}

                pageCount = data.sites.pageCount;
                delete data.sites.pageCount;

                _.templateSettings = {
                    interpolate: /\{\[\{=(.+?)\}\]\}/g,
                    evaluate: /\{\[\{(.+?)\}\]\}/g
                };

                var sitesTemplate = _.template($('#sitesData').html().replace(/&amp;/g, '&').replace(/&lt;/g, '<'));

                if ($('#sites div[data-name]').length == 0) {
                    $('#sites').prepend(sitesTemplate({
                        sites: data.sites,
                        users: data.users
                    }));
				} else {
                    $(sitesTemplate({
                        sites: data.sites,
                        users: data.users
                    })).insertAfter('#sites div[data-name]:last');
				}
                $(".loading").hide();

                $('#sites').find('select').select2({
                    minimumResultsForSearch: -1
                });

            }
        });
    }

}());