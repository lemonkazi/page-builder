(function () {
	"use strict";

	var appUI = require('../shared/ui.js').appUI;
    var utils = require('../shared/utils');
    var notify = require('../shared/notify');

	var users = {

        buttonCreateAccount: document.getElementById('buttonCreateAccount'),
        wrapperUsers: document.getElementById('users'),
        buttonsRegenerateToken: document.querySelectorAll('.buttonRegenerateToken'),

        init: function() {

            $(this.buttonCreateAccount).on('click', this.createAccount);
            $(this.wrapperUsers).on('click', '.updateUserButton', this.updateUser);
            $(this.wrapperUsers).on('click', '.deleteUserButton', this.deleteUser);
            $(document).on('click', '.passwordReset', this.passwordReset);

            $(this.buttonsRegenerateToken).on('click', function () {

                let target = this.getAttribute('data-target');

                document.querySelector(target).value = utils.randomString(12);

                console.log(utils.randomString(12));

                return false;

            });

            $(document).ready(function() {
                var packages = [];

                if( packages.length == 0 ) {
                    $.ajax({
                        url: appUI.siteUrl + 'user/getPackages',
                        success: function(data){
                            packages = $.parseJSON(data);
                        }
                    })
                }


                var datatable = $('#datatable').DataTable({
                    "serverSide": true,
                    "ajax": {
                        url: appUI.siteUrl + 'user/search',
                        type: 'POST',
                    },
                    "columnDefs": [
                        { "targets": 0,
                            "data": "id"
                        },
                        { "targets": 1, "data": "email" } ,
                        { "targets": 2,
                            "data": {
                                first_name: "first_name",
                                last_name: "last_name",
                            },
                            "render": function(data, type, row, meta){
                                return data.first_name + " " + data.last_name;
                            }
                        } ,
                        { "targets": 3,
                            "data": {
                                type: "type",
                            },
                            "render": function(data, type, row, meta){
                                var is_admin =  data.type == "Admin" ? "checked" : '';

                                return  '<label class="checkbox" for="checkbox-admin-' + data.id + '">' +
                                    '<a href="user/change_type/' + data.id + '"' + ' data-toggle="checkbox" >' +
                                    '<input type="checkbox" value="yes"' + is_admin + ' name="is_admin" data-toggle="checkbox" id="checkbox-admin-' + data.id + '"><span class="checkmark"></span>' +
                                    '</a>' +
                                    '</label>';
                            }
                        } ,
                        { "targets": 4,
                            "data": {
                                package_id : "package_id"
                            },
                            "render": function(data, type, row, meta){
                                var select = '<select class="user-package" data-user="'+data.id+'">';

                                var notselected = '';
                                if(!data.package_id){
                                    notselected = 'selected';
                                }

                                select += '<option '+notselected+' value="0">Choose a Package</option>';

                                $.each(packages, function(index,value){
                                    var selected = '';
                                    if(value.id == data.package_id) selected = 'selected';
                                    select += '<option '+selected+' value="'+ value.id +'">' + value.name + '</option>';

                                })

                                select += '</select>';

                                return select;
                            }
                        } ,
                        { "targets": 5,
                            "data": {
                                status: "status"
                            },
                            "render": function(data, type, row, meta){
                                var active =  data.status == "Active" ? "checked" : '';

                                var activeHtml = '<a href="user/toggle_status/'+ data.id +'" class="" data-toggle="tooltip"></a>\n' +
                                    '                        <label class="checkbox" for="checkbox-admin-'+ data.id +'" style="padding-top: 0px;">\n' +
                                    '                            <a href="user/toggle_status/'+data.id+'" class="" data-toggle="tooltip">\n' +
                                    '                                <input type="checkbox" value="yes" '+active+' name="is_admin" data-toggle="checkbox" id="checkbox-admin-'+ data.id +'"><span class="checkmark"></span>' +
                                    '                            </a>\n' +
                                    '                        </label>';

                                return activeHtml;
                            }
                        },
                        { "targets": 6,
                            "data": "auto_login_token"
                        },
                        { "targets": 7,
                            "data": {
                                id: "id"
                            },
                            "render": function(data, type, row, meta){
                                var actionsHtml = '<a href="#" class="passwordReset" data-userid="'+data.id+'">Send Password Reset Email</a> | '+
                                    '<a href="user/delete/' + data.id + '" class="deleteUserButton" data-toggle="confirmation" data-title="Are you sure?" data-content="Deleting this file can not be undone.">Delete Account</a>';

                                $('[data-toggle=confirmation]').confirmation({
                                    rootSelector: '[data-toggle=confirmation]',
                                });

                                return actionsHtml;
                            }
                        }

                    ]
                });

                $(document).on('change','select.user-package',function(){
                    var user_id = $(this).attr('data-user');
                    var selected_package = $(this).val();
                    window.location.href = appUI.siteUrl + 'user/setPackage/' + user_id + '/' + selected_package;
                })
            } );

        },


        /*
            creates a new user account
        */
        createAccount: function() {

            //all items present?

            var allGood = 1;

            if( $('#newUserModal form input#firstname').val() === '' ) {
                $('#newUserModal form input#firstname').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newUserModal form input#firstname').parent().removeClass('has-error');
            }

            if( $('#newUserModal form input#lastname').val() === '' ) {
                $('#newUserModal form input#lastname').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newUserModal form input#lastname').parent().removeClass('has-error');
            }

            if( $('#newUserModal form input#email').val() === '' ) {
                $('#newUserModal form input#email').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newUserModal form input#email').parent().removeClass('has-error');
            }

            if( $('#newUserModal form input#password').val() === '' ) {
                $('#newUserModal form input#password').parent().addClass('has-error');
                allGood = 0;
            } else {
                $('#newUserModal form input#password').parent().removeClass('has-error');
            }

            if( allGood === 1 ) {

                //remove old alerts
                $('#newUserModal .modal-alerts > *').hide();

                //disable button
                $(this).addClass('disabled');

                //show loader
                $('#newUserModal .loader').fadeIn();

                $.ajax({
                    url: $('#newUserModal form').attr('action'),
                    type: 'post',
                    dataType: 'json',
                    data:  $('#newUserModal form').serialize()
                }).done(function(ret){

                    //enable button
                    $('button#buttonCreateAccount').removeClass('disabled');

                    //hide loader
                    $('#newUserModal .loader').hide();

                    if( ret.responseCode === 0 ) {//error

                        $('#newUserModal .modal-alerts').append( $(ret.responseHTML) );

                    } else {//all good

                        $('#newUserModal .modal-alerts').append( $(ret.responseHTML) );
                        $('#users > *').remove();
                        $('#users').append( $(ret.users) );
                        $('.userSites .site iframe').each(function(){

                            var theHeight = $(this).attr('data-height')*0.25;

                            $(this).width(  );

                            $(this).zoomer({
                                zoom: 0.25,
                                height: theHeight,
                                width: $(this).closest('.tab-pane').width(),
                                message: "",
                                messageURL: appUI.siteUrl+"sites/"+$(this).attr('data-siteid')
                            });

                            $(this).closest('.site').find('.zoomer-cover > a').attr('target', '');
                        });
                        $('#datatable').DataTable().ajax.reload();
                        setTimeout(function(){
                            $('#newUserModal').modal('toggle');
                            $(".form-horizontal")[0].reset();
                            $('.modal-alerts').html(' ');
                        }, 2500)

                    }

                });

            }

        },


        /*
            updates a user
        */
        updateUser: function() {

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

                    //append user detail form
                    var thePane = theButton.closest('.tab-pane');

                    setTimeout(function(){
                        thePane.closest('.bottom').find('.alert-success').fadeOut(500, function(){$(this.remove());});
                    }, 3000);

                    theButton.closest('form').remove();

                    thePane.prepend( $(ret.userDetailForm) );
                    thePane.find('form input[type="checkbox"]').radiocheck();
                    thePane.find('select').select2({
                        minimumResultsForSearch: -1
                    });

                }

            });

        },


        /*
            password reset
        */
        passwordReset: function(e) {

            e.preventDefault();

            var theButton = $(this);

            //disable buttons
            $(this).addClass('disabled');
            $(this).closest('.bottom').find('.updateUserButton').addClass('disabled');

            //show loader
            $(this).closest('.bottom').find('.loader').fadeIn();

            $.ajax({
                url: appUI.siteUrl+"user/send_password_reset/"+$(this).attr('data-userid'),
                type: 'post',
                dataType: 'json'
            }).done(function(ret){
                //enable buttons
                theButton.removeClass('disabled');
                theButton.closest('.bottom').find('.updateUserButton').removeClass('disabled');

                //hide loader
                theButton.closest('.bottom').find('.loader').hide();
                $(theButton).closest('.bottom').find('.alerts').append( $(ret.responseHTML) );

                let notifyConfig = notify.config;

                if ( ret.responseCode === 1 ) {
                    notifyConfig.className = "joy";
                } else {
                    notifyConfig.className = "bummer";
                }

                $.notify(ret.responseHTML, notifyConfig);

            });

        },


        /*
            deletes a user account
        */
        deleteUser: function(e) {

            e.preventDefault();

            //setup delete link
            $('#deleteUserModal a#deleteUserButton').attr('href', $(this).attr('href'));

            //modal
            $('#deleteUserModal').modal('show');

        }

    };

    users.init();

}());