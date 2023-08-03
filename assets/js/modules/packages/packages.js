(function () {
	"use strict";

	var appUI = require('../shared/ui.js').appUI;
    let notify = require('../shared/notify');

	var packages = {

        buttonCreatePackage: document.getElementById('buttonCreatePackage'),
        wrapperPackages: document.getElementById('packages'),
        packagesTable: document.querySelector('table.packages'),
        editPackageModal: document.getElementById('editPackageModal'),
        editPackageModal_id: document.getElementById('e_id'),
        editPackageModal_name: document.getElementById('e_name'),
        editPackageModal_numerofsites: document.getElementById('e_sites_number'),
		editPackageModal_numberofpages: document.getElementById('e_pages_number'),
        editPackageModal_hosting: document.getElementById('e_hosting_option'),
        editPackageModal_export: document.getElementById('e_export_site'),
        editPackageModal_ftp: document.getElementById('e_ftp_publish'),
		editPackageModal_sourcecode: document.getElementById('e_edit_sourcecode'),
        editPackageModal_diskspace: document.getElementById('e_disk_space'),
        editPackageModal_price: document.getElementById('e_price'),
        editPackageModal_currency: document.getElementById('e_currency'),
        editPackageModal_interval: document.getElementById('e_subscription'),
        editPackageModal_status: document.getElementById('e_status'),
        buttonUpdatePackage: document.getElementById('buttonUpdatePackage'),
        updatePackageForm: document.getElementById('updatePackageForm'),

        init: function() {

            $('select#currency').select2();

            $(this.buttonCreatePackage).on('click', this.createPackage);
            $(this.wrapperPackages).on('click', '.updatePackageButton', this.updatePackage);
            $(this.wrapperPackages).on('click', '.deletePackageButton', this.deletePackage);
            $(this.packagesTable).on('click', '.deletePackageButton', this.deletePackage);

            $(this.editPackageModal).on('show.bs.modal', function (event) {

                var packageID = event.relatedTarget.getAttribute('data-package-id');

                $.ajax({
                    url: appUI.siteUrl + "packages/get_package/" + packageID,
                    method: 'GET',
                    dataType: 'json'
                }).done(function (data) {

                    packages.editPackageModal_id.value = data.id;
                    packages.editPackageModal_name.value = data.name;
					packages.editPackageModal_numerofsites.value = data.sites_number;
					packages.editPackageModal_numberofpages.value = data.pages_number === '0' ? '' : data.pages_number;
								
                    packages.SetMultiSelect(packages.editPackageModal_hosting, JSON.parse(data.hosting_option));
                    $(packages.editPackageModal_hosting).trigger('change');
                    if (data.export_site === 'yes') {
                        $(packages.editPackageModal_export).bootstrapSwitch('state', true);
                    } else {
                        $(packages.editPackageModal_export).bootstrapSwitch('state', false);
                    }
                    if (data.ftp_publish === 'yes') {
                        $(packages.editPackageModal_ftp).bootstrapSwitch('state', true);
                    } else {
                        $(packages.editPackageModal_ftp).bootstrapSwitch('state', false);
                    }	
					if(data.edit_sourcecode === 'yes') {
                        $(packages.editPackageModal_sourcecode).bootstrapSwitch('state', true);
                    } else {
                        $(packages.editPackageModal_sourcecode).bootstrapSwitch('state', false);
                    }						
                    packages.editPackageModal_diskspace.value = data.disk_space;
                    packages.editPackageModal_price.value = data.price;
                    packages.editPackageModal_currency.value = data.currency;
                    $(packages.editPackageModal_currency).trigger('change');
                    packages.editPackageModal_interval.value = data.subscription;
                    $(packages.editPackageModal_interval).trigger('change');
                    packages.editPackageModal_status.value = data.status;
                    $(packages.editPackageModal_status).trigger('change');

                    //reset all
                    $('.packageTemplateSelection input[type="checkbox"]').each(function () {
                        $(this).radiocheck('uncheck');
                    });

                    if ( data.templates !== 'null' ) {

                        var templateIDs = JSON.parse(data.templates);

                        for ( var templateID of templateIDs ) {
                            $('#e_templates_' + templateID).radiocheck('check');
                        }

                    }

                    if ( data.blocks === null ) { // Block restrictions turned off;

                        // flip switch to "off"
                        $('#e_limit_blocks').bootstrapSwitch('state', false);

                        // Uncheck the block checkboxes
                        $('input.blockcats').each(function () {

                            $(this).radiocheck('uncheck');

                        });

                    } else if ( data.blocks === '[]' ) { // Block restrictions turned on, no categories selected

                        // flip switch to "on"
                        $('#e_limit_blocks').bootstrapSwitch('state', true);

                        // Uncheck the block checkboxes
                        $('input.blockcats').each(function () {

                            $(this).radiocheck('uncheck');

                        });

                    } else { // Block restriction turned on, with specific categories selected

                        // flip switch to "on"
                        $('#e_limit_blocks').bootstrapSwitch('state', true);

                         // Uncheck the block checkboxes
                        $('input.blockcats').each(function () {

                            $(this).radiocheck('uncheck');

                        });

                        // Check the selected categories

                        let blocks = JSON.parse(data.blocks);

                        for ( let catID of blocks ) {

                            $('input#e_blockcat_' + catID).radiocheck('check');

                        }

                    }


                }.bind(this));

            });

            this.buttonUpdatePackage.addEventListener('click', function () {

                this.buttonUpdatePackage.setAttribute('disabled', true);

                $.ajax({
                    url: this.updatePackageForm.getAttribute('action'),
                    method: 'post',
                    dataType: 'json',
                    data: $(this.updatePackageForm).serialize()
                }).done(function (data) {

                    packages.buttonUpdatePackage.removeAttribute('disabled');

                    let className,
                        notifyConfig = notify.config;

                    if ( data.responseCode === 1 ) {
                        notifyConfig.className = "joy";
                    } else {
                        notifyConfig.className = "bummer";
                    }

                    $.notify(data.responseHTML, notifyConfig);

                    // update package table
                    $('#packages > *').remove();
                    $('#packages').append( $(data.packages) );

                });

            }.bind(this));


            $('input#limit_blocks, input#e_limit_blocks').on('switchChange.bootstrapSwitch', function (e, data) {

                if ( this.checked ) {

                    $(this).closest('.col-md-9').find(':checkbox').removeAttr('disabled');

                } else {

                    $(this).closest('.col-md-9').find(':checkbox').attr('disabled', true);

                }

            });

        },

        SetMultiSelect: function(multiSltCtrl, values) {

            //here, the 1th param multiSltCtrl is a html select control or its jquery object, and the 2th param values is an array
            var $sltObj = $(multiSltCtrl) || multiSltCtrl;
            var opts = $sltObj[0].options; //
            for (var i = 0; i < opts.length; i++)
            {
                opts[i].selected = false;//don't miss this sentence
                if ( values !== null ) {
                    for (var j = 0; j < values.length; j++)
                    {
                        if (opts[i].value === values[j])
                        {
                            opts[i].selected = true;
                            break;
                        }
                    }
                }
            }
            //$sltObj.multiselect("refresh");//don't forget to refresh!
        },

        /**
         * Creates a new package
         */
        createPackage: function() {

            //all items present?
            var allGood = 1;

            if( $('#newPackageModal form input#name').val() === '' ) {
                $('#newPackageModal form input#name').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newPackageModal form input#name').parent().removeClass('has-error');
            }

            if( $('#newPackageModal form input#sites_number').val() === '' ) {
                $('#newPackageModal form input#sites_number').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newPackageModal form input#sites_number').parent().removeClass('has-error');
            }

            if( $('#newPackageModal form input#price').val() === '' ) {
                $('#newPackageModal form input#price').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newPackageModal form input#price').parent().removeClass('has-error');
            }

            if( allGood === 1 ) {

                //remove old alerts
                $('#newPackageModal .modal-alerts > *').hide();

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#newPackageModal .loader').fadeIn();

                $.ajax({
                    url: $('#newPackageModal form').attr('action'),
                    type: 'post',
                    dataType: 'json',
                    data:  $('#newPackageModal form').serialize()
                }).done(function(ret){

                    //enable button
                    $('button#buttonCreatePackage').removeClass('disabled');

                    //hide loader
                    $('#newPackageModal .loader').hide();

                    if( ret.responseCode === 0 ) {//error

                        $('#newPackageModal .modal-alerts').append( $(ret.responseHTML) );

                    } else {//all good

                        $('#newPackageModal .modal-alerts').append( $(ret.responseHTML) );

                        //self destruct
                        setTimeout(function () {
                            $('#newPackageModal .alert').fadeOut(function () {
                                this.remove();
                            });
                        }, 4000);

                        $('#empty_package').remove();
                        $('#packages > *').remove();
                        $('#packages').append( $(ret.packages) );

                        //reset the new package form
                        document.getElementById('name').value = '';
                        document.getElementById('sites_number').value = '';
                        document.getElementById('hosting_option').value = '';
                        $(document.getElementById('hosting_option')).trigger('change');
                        document.getElementById('export_site').checked = false;
                        document.getElementById('ftp_publish').checked = false;
                        document.getElementById('disk_space').value = '';
                        document.getElementById('price').value = '';
                        $('#currency').val($('#currency option:first-child').val()).trigger('change');
                        $('#subscription').val($('#subscription option:first-child').val()).trigger('change');
                        $('#status').val($('#status option:first-child').val()).trigger('change');

                    }

                });

            }

        },

        /**
         * Updates a package
         */
        updatePackage: function() {

            //disable button
            var theButton = $(this);
            $(this).addClass('disabled');

            //show loader
            $(this).closest('.bottom').find('.loader').fadeIn(500);

            $.ajax({
                url: $(this).closest('form').attr('action'),
                type: 'post',
                dataType: 'json',
                data: $(this).closest('form').serialize()
            }).done(function(ret){

                //enable button
                theButton.removeClass('disabled');

                //hide loader
                theButton.closest('.bottom').find('.loader').hide();

                if( ret.responseCode === 0 ) {//error

                    theButton.closest('.bottom').find('.alerts').append( $(ret.responseHTML) );

                } else if(ret.responseCode === 1) {//all good

                    theButton.closest('.bottom').find('.alerts').append( $(ret.responseHTML) );

                    //append package detail form
                    var theContent = theButton.closest('.tab-content');

                    setTimeout(function(){
                        theContent.closest('.bottom').find('.alert-success').fadeOut(500, function(){$(this.remove());});
                    }, 3000);

                    theButton.closest('form').remove();

                    theContent.prepend( $(ret.packageDetailForm) );
                    //theContent.find('form input[type="checkbox"]').checkbox();

                }

            });

        },

        /**
         * Delete a package
         */
        deletePackage: function(e) {

            e.preventDefault();

            //setup delete link
            $('#deletePackageModal a#deletePackageButton').attr('href', $(this).attr('href'));

            //modal
            $('#deletePackageModal').modal('show');
        }

    };

    packages.init();

}());