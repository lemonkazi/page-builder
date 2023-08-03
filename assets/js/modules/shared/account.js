(function () {
	"use strict";

	var appUI = require('./ui.js').appUI;

	var account = {

        buttonUpdateAccountDetails: document.getElementById('accountDetailsSubmit'),
        buttonUpdateLoginDetails: document.getElementById('accountLoginSubmit'),
        buttonAccountPackageUpdate: document.getElementById('accountPackageUpdate'),
        buttonAccountPackageCancel: document.getElementById('accountPackageCancel'),

        init: function() {
            $(this.buttonUpdateAccountDetails).on('click', this.updateAccountDetails);
            $(this.buttonUpdateLoginDetails).on('click', this.updateLoginDetails);
            $(this.buttonAccountPackageUpdate).on('click', this.fnPackageUpdate);
            $(this.buttonAccountPackageCancel).on('click', this.fnPackageCancel);
        },

        /*
            updates account details
        */
        updateAccountDetails: function() {

            //all fields filled in?
            var allGood = 1;

            if( $('#account_details input#first_name').val() === '' ) {
                $('#account_details input#first_name').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_details input#first_name').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( $('#account_details input#last_name').val() === '' ) {
                $('#account_details input#last_name').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_details input#last_name').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( allGood === 1 ) {

                var theButton = $(this);

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#account_details .loader').fadeIn(500);

                //remove alerts
                $('#account_details .alerts > *').remove();

                $.ajax({
                    url: appUI.siteUrl+"user/details_update",
                    type: 'post',
                    dataType: 'json',
                    data: $('#account_details').serialize()
                }).done(function(ret){

                    //enable button
                    theButton.removeClass('disabled');

                    //hide loader
                    $('#account_details .loader').hide();
                    $('#account_details .alerts').append( $(ret.responseHTML) );

                    if( ret.responseCode === 1 ) {//success
                        setTimeout(function () {
                            $('#account_details .alerts > *').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }
                });

            }

        },


        /*
            updates account login details
        */
        updateLoginDetails: function() {

			console.log(appUI);

            var allGood = 1;

            if( $('#account_login input#email').val() === '' ) {
                $('#account_login input#email').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_login input#email').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( $('#account_login input#password').val() === '' ) {
                $('#account_login input#password').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#account_login input#password').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( allGood === 1 ) {

                var theButton = $(this);

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#account_login .loader').fadeIn(500);

                //remove alerts
                $('#account_login .alerts > *').remove();

                $.ajax({
                    url: appUI.siteUrl+"user/login_update",
                    type: 'post',
                    dataType: 'json',
                    data: $('#account_login').serialize()
                }).done(function(ret){

                    //enable button
                    theButton.removeClass('disabled');

                    //hide loader
                    $('#account_login .loader').hide();
                    $('#account_login .alerts').append( $(ret.responseHTML) );

                    if( ret.responseCode === 1 ) {//success
                        setTimeout(function () {
                            $('#account_login .alerts > *').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }

                });

            }

        },

        /*
            update account package
        */
        fnPackageUpdate: function() {

            console.log(appUI);

            var allGood = 1;

            if( $('#package_update input#user_id').val() === '' ) {
                $('#package_update input#user_id').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#package_update input#user_id').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( allGood === 1 ) {

                var theButton = $(this);

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#package_update .loader').fadeIn(500);

                //remove alerts
                $('#package_update .alerts > *').remove();

                $.ajax({
                    url: appUI.siteUrl+"user/package_update",
                    type: 'post',
                    dataType: 'json',
                    data: $('#package_update').serialize()
                }).done(function(ret){

                    //enable button
                    theButton.removeClass('disabled');

                    //hide loader
                    $('#package_update .loader').hide();
                    $('#package_update .alerts').append( $(ret.responseHTML) );

                    if( ret.responseCode === 1 ) { //success
                        setTimeout(function () {
                            $('#package_update .alerts > *').fadeOut(500, function () { $(this).remove(); });
                            if( typeof ret.redirect !== 'undefined' ) {
                                window.location.href = ret.redirect;
                            }
                        }, 3000);
                    }

                });

            }

        },

        /*
            cancel user subscription
        */
        fnPackageCancel: function() {

            console.log(appUI);

            var allGood = 1;

            if( $('#package_cancel input#user_id').val() === '' ) {
                $('#package_cancel input#user_id').closest('.form-group').addClass('has-error');
                allGood = 0;
            } else {
                $('#package_cancel input#user_id').closest('.form-group').removeClass('has-error');
                allGood = 1;
            }

            if( allGood === 1 ) {

                var theButton = $(this);

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#package_cancel .loader').fadeIn(500);

                //remove alerts
                $('#package_cancel .alerts > *').remove();

                $.ajax({
                    url: appUI.siteUrl+"user/package_cancel",
                    type: 'post',
                    dataType: 'json',
                    data: $('#package_cancel').serialize()
                }).done(function(ret){

                    //enable button
                    theButton.removeClass('disabled');

                    //hide loader
                    $('#package_cancel .loader').hide();
                    $('#package_cancel .alerts').append( $(ret.responseHTML) );

                    if( ret.responseCode === 1 ) {//success
                        setTimeout(function () {
                            $('#package_cancel .alerts > *').fadeOut(500, function () { $(this).remove(); });
                        }, 3000);
                    }

                });

            }

        }

    };

    account.init();

}());