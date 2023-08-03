(function () {
    "use strict";
    let appUI = require('../shared/ui.js').appUI;

    let buttonsConfigureIntegration = document.querySelectorAll('.buttonConfigureIntegration');

    buttonsConfigureIntegration.forEach((button) => {

        button.addEventListener('click', () => {

            document.getElementById('spanIntegrationName').innerText = event.target.getAttribute('data-integration');

            document.getElementById('divIntegrationFormInner').innerHTML = '';

            if ( document.getElementById(event.target.getAttribute('data-template')) !== null ) {

                let formContents = document.importNode(document.getElementById(event.target.getAttribute('data-template')).content, true);

                document.getElementById('divIntegrationFormInner').appendChild(formContents);

            }

        });

    });

    let integrationForm = document.getElementById('formIntegration');

    document.getElementById('buttonSaveIntegration').addEventListener('click', () => {

        let allGood = true,
            inputs = integrationForm.querySelectorAll('input');
        inputs.forEach((input) => {
            if ( input.value === '' ) {
                input.parentNode.classList.add('has-error');
                allGood = false;
            } else {
                input.parentNode.classList.remove('has-error');
            }
        });

        if ( allGood ) {
            var data = $('#formIntegration').serialize();
            var integration_id = $('.api_name').data('id');
            console.log(data);
            $.ajax({
                url: appUI.siteUrl + "integrations/saveConfigs",
                type: 'post',
                dataType: 'json',
                data:{
                    data:data,
                    integration_id:integration_id,
                }
            }).done(function (ret) {
                console.log(ret);
                if( ret.responseCode === 0 ) {//error
                    $('#modalConfigIntegration .modal-alerts').html(' ');
                    $('#modalConfigIntegration .modal-alerts').append( $(ret.responseHTML) );

                } else{//all good
                    if(ret.url){
                            window.open(ret.url, ' ', 'width=500, height=500');
                            $('#divIntegrationFormInner').append('<div class="form-group aweber_verifier">' +
                                '<input type="text" value="" placeholder="Verifier Code" name="verifier" class="form-control">' +
                                '</div>')
                            $('#modalConfigIntegration .modal-alerts').html(' ');
                            $('#modalConfigIntegration .modal-alerts').append($(ret.responseHTML));

                        if(ret.api_name){
                            $('.' + ret.api_name + '_label').html('<span class="label label-success">' + ret.label_configured + '</span>')
                        }
                    }else if((ret.lists && ret.api_name == 'mailchimp') || (ret.lists && ret.api_name == 'getresponse')){
                        $('#divIntegrationFormInner').append('<div class="form-group"><select class="form-control selectpicker" id="api_list" name="list_id"><option value=" ">Select List</option></select></div>');
                        $.each(ret.lists, function( index, value ) {
                            if(ret.api_name == 'getresponse'){
                                $('#api_list').append('<option value="'+index+'">'+value.name+'</option>')
                            }else{
                                $('#api_list').append('<option value="'+value.id+'">'+value.name+'</option>')
                            }
                        });
                        $('#modalConfigIntegration .modal-alerts').html(' ');
                        $('#modalConfigIntegration .modal-alerts').append($(ret.responseHTML));
                        if(ret.api_name){
                            $('.' + ret.api_name + '_label').html('<span class="label label-success">' + ret.label_configured + '</span>')
                        }
                    }else{
                        $('#modalConfigIntegration .modal-alerts').html(' ');
                        $('#modalConfigIntegration .modal-alerts').append($(ret.responseHTML));
                        if(ret.api_name){
                            $('.' + ret.api_name + '_label').html('<span class="label label-success">' + ret.label_configured + '</span>')
                        }
                        setTimeout(function(){
                            $('#modalConfigIntegration').modal('toggle');
                            $('.modal-alerts').html(' ');
                            location.reload();
                        }, 2500)
                    }
                }

                });
        }


    });

}());

/* this attempts to load custom JS code to include in the settings page */
try {
    require('../../custom/integrations.js');
} catch (e) {

}