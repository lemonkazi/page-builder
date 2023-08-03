(function () {
	"use strict";

	var appUI = require('./ui.js').appUI;
    var publisher = require('../../vendor/publisher');

	var siteSettings = {

        //buttonSiteSettings: document.getElementById('siteSettingsButton'),
		buttonSiteSettings2: $('.siteSettingsModalButton'),
        buttonSaveSiteSettings: document.getElementById('saveSiteSettingsButton'),
        selectHostingOptions: {},
        modalSiteSettings: document.getElementById('siteSettings'),
        selectHostingOptionsId: '#select_hostingOptions',

        init: function() {

            //$(this.buttonSiteSettings).on('click', this.siteSettingsModal);
            $(document).on('click','.siteSettingsModalButton', this.siteSettingsModal);
            $(this.buttonSaveSiteSettings).on('click', this.saveSiteSettings);

            $(this.modalSiteSettings).on('change', this.selectHostingOptionsId, function (e) {
                this.switchHostingOption(e);
            }.bind(this));

        },

        /*
            loads the site settings data
        */
        siteSettingsModal: function(e) {

            e.preventDefault();

    		$('#siteSettings').modal('show');

    		//destroy all alerts
    		$('#siteSettings .alert').fadeOut(500, function(){

    			$(this).remove();

    		});

    		//set the siteID
    		$('input#siteID').val( $(this).attr('data-siteid') );

    		//destroy current forms
    		$('#siteSettings .modal-body-content > *').each(function(){
    			$(this).remove();
    		});

            //show loader, hide rest
    		$('#siteSettingsWrapper .loader').show();
    		$('#siteSettingsWrapper > *:not(.loader)').hide();

    		//load site data using ajax
    		$.ajax({
                url: appUI.siteUrl+"sites/siteAjax/"+$(this).attr('data-siteid'),
    			type: 'post',
    			dataType: 'json'
    		}).done(function(ret){

    			if( ret.responseCode === 0 ) {//error

    				//hide loader, show error message
    				$('#siteSettings .loader').fadeOut(500, function(){

    					$('#siteSettings .modal-alerts').append( $(ret.responseHTML) );

    				});

    				//disable submit button
    				$('#saveSiteSettingsButton').addClass('disabled');


    			} else if( ret.responseCode === 1 ) {//all well :)

    				//hide loader, show data

    				$('#siteSettings .loader').fadeOut(500, function(){

    					$('#siteSettings .modal-body-content').append( $(ret.responseHTML) );

                        $('select', this.modalSiteSettings).select2({
                            dropdownCssClass: 'dropdown-inverse',
                            minimumResultsForSearch: -1
                        });
                        $('#home_page').radiocheck();
                        $('body').trigger('siteSettingsLoad');
						$('#inputFaviconFile').on('change', function () {
							$('#currentFaviconFile').val('');
							if(this.files[0])
							{
								$('#favicon_preview').attr('src', window.URL.createObjectURL(this.files[0]));
								$('#favicon_preview').switchClass('favicon_hide', 'favicon_show');
							}
						});
						$('#removeFavicon').on('click',function () {
							$('#favicon_preview').attr('src', '//');
							$('#favicon_preview').switchClass('favicon_show', 'favicon_hide');
						});
						
						$('#favicon_tooltip').tooltip();
						

    				});

    				//enable submit button
    				$('#saveSiteSettingsButton').removeClass('disabled');

    			}

    		});

        },


        /*
            saves the site settings
        */
        saveSiteSettings: function() {

            // destroy all alerts
    		$('#siteSettings .alert').fadeOut(500, function(){

    			$(this).remove();

    		});

    		// disable button
    		$('#saveSiteSettingsButton').addClass('disabled');

    		// hide form data
    		$('#siteSettings .modal-body-content > *').hide();

    		// show loader
    		$('#siteSettings .loader').show();
			var fd = new FormData();
			var file = $('#inputFaviconFile')[0].files[0];
            let theData = $('form#siteSettingsForm').serializeArray();
			$.each(theData, function(i, val) {
				fd.append(val.name, val.value);
			});
			fd.append('favicon_file',file);
						
    		$.ajax({
                url: appUI.siteUrl+"sites/siteAjaxUpdate",
    			type: 'post',
    			dataType: 'json',
				contentType: false,
				processData: false,
    			data: fd
    		}).done(function(ret){
				
    			if( ret.responseCode === 0 ) { // error

    				$('#siteSettings .loader').fadeOut(500, function(){

    					$('#siteSettings .modal-alerts').append( ret.responseHTML );

    					// show form data
    					$('#siteSettings .modal-body-content > *').show();

    					// enable button
    					$('#saveSiteSettingsButton').removeClass('disabled');

                        $('#siteSettings .modal-body-content select#select_ftp_type').select2({
                            minimumResultsForSearch: -1
                        });

    				});


    			} else if( ret.responseCode === 1 ) { // all is well

                    publisher.publish("onSiteDetailsSaved", theData); // needed to update the siteData object in the builder.js module

    				$('#siteSettings .loader').fadeOut(500, function(){


    					// update site name in top menu
    					$('#siteTitle').text( ret.siteName );

    					$('#siteSettings .modal-alerts').append( ret.responseHTML );
    					// hide form data
    					$('#siteSettings .modal-body-content > *').remove();
    					$('#siteSettings .modal-body-content').append( ret.responseHTML2 );
    					// enable button
    					$('#saveSiteSettingsButton').removeClass('disabled');

                        $(siteSettings.selectHostingOptionsId).select2({
                            dropdownCssClass: 'dropdown-inverse',
                            minimumResultsForSearch: -1
                        });

                        $('#siteSettings .modal-body-content select#select_ftp_type').select2({
                            minimumResultsForSearch: -1
                        });

                        // Radio button
                        $('#home_page').radiocheck();

    					// is the FTP stuff all good?

    					if( ret.ftpOK === 1 ) { // yes, all good
							$('#publish-tooltip-wrapper').removeAttr('data-toggle');
							$('#publishPage').removeAttr('disabled');
    						$('#publishPage span.text-danger').hide();

    						$('#publish-tooltip-wrapper').tooltip('destroy');

    					} else { // nope, can't use FTP

    						$('#publish-tooltip-wrapper').attr('data-toggle', 'tooltip');
							$('#publishPage').prop("disabled", true);
    						$('#publishPage span.text-danger').show();

    						$('#publish-tooltip-wrapper').tooltip();

    					}


    					// update the site name in the small window
    					$('#site_'+ret.siteID+' .window .top b').text( ret.siteName );

					});



    			}
				$('#siteSettings .loader').fadeOut(500, function(){
					$('#favicon_tooltip').tooltip();
					$('#inputFaviconFile').on('change', function () {
						$('#currentFaviconFile').val('');
						if(this.files[0])
						{
							$('#favicon_preview').attr('src', window.URL.createObjectURL(this.files[0]));
							$('#favicon_preview').switchClass('favicon_hide', 'favicon_show');
						}
					});
					$('#removeFavicon').on('click',function () {
						$('#favicon_preview').attr('src', '//');
						$('#favicon_preview').switchClass('favicon_show', 'favicon_hide');
						
					}); 					
				});

				
    		});
			

        },


        /*
            Switch between hosting options
        */
        switchHostingOption: function (e) {

            //hide all hosting panels
            $('.hosting_option').slideUp();

            switch (e.currentTarget.value) {

                case "Sub Folder":

                    $('#section_subfolder').slideDown();
                    break;

                case "Sub Domain":

                    $('#section_subdomain').slideDown();
                    break;

                case "Custom Domain":

                    $('#section_customdomain').slideDown();
                    break;

            }

        }


    };

    siteSettings.init();

}());