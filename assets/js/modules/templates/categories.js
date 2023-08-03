/* globals siteUrl: false */
(function () {
	"use strict";

	const inputNewCategory = document.querySelector('#inputNewCategory');
	const buttonAddNewCategory = document.querySelector('#buttonAddNewCategory');
	const tableTemplateCategories = document.querySelector('#tableTemplateCategories');
    const selectBlockCategory = document.getElementById('selectBlockCategory');
    const templates = require('./templates');

	let notify = require('../shared/notify');


	$(inputNewCategory).on('keyup', function () {

        // empty input field?
        if ( this.value !== '' ) buttonAddNewCategory.removeAttribute('disabled');
        else buttonAddNewCategory.setAttribute('disabled', true);

        // unique input field value?
        if ( this.value !== '' ) {

            for ( var tdCatName of tableTemplateCategories.querySelectorAll('.tdCatName') ) {
                if (tdCatName.innerText === this.value) buttonAddNewCategory.setAttribute('disabled', true);
            }

        }

    });

    $(selectBlockCategory).on('change', function () {

        let select = this;

        $('#sites').fadeOut(function () {

            $(this).find('.site:not(.empty)').hide();

            if ( select.value === '0' ) {
                $(this).find('.site:not(.empty)').show();
            } else {
                $(this).find('.site[data-cat="' + select.value + '"]:not(.empty)').show();
            }

            $(this).fadeIn();

        });

    });

    $(buttonAddNewCategory).on('click', () => { addCategory();});

    $(inputNewCategory).on('keyup', function (e) {

        let code = (e.keyCode ? e.keyCode : e.which);

        if ( code === 13 ) addCategory();

        return false;

    });

    /*
        Event handler delete category
    */
    $(tableTemplateCategories).on('click', '.linkCatDel', function () {

        let catID = this.getAttribute('data-cat-id'),
            catname = $(this).closest('tr').find('.tdCatName').text();

        // load current categories from server
        $.ajax({
            url: siteUrl + "templates/loadDeleteCatModal",
            type: 'get',
            data: {catID: catID},
            dataType: 'json'
        }).done(function (ret) {

            let tdCatName = $(this).closest('tr').find('.tdCatName');
            tdCatName.attr('data-oldcatname', tdCatName.text());

            // remove catname
            tdCatName.text('');

            // modify table layout
            $(this).closest('td').css('display', 'none');
            tdCatName.attr('colspan', 2);

            tdCatName.append(ret.markup);

            $("select", tdCatName).select2({
                minimumResultsForSearch: -1
            });

        }.bind(this));

        return false;

    });


    /*
        Cancel deleting the template category
    */
    $(tableTemplateCategories).on('click', '.buttonCancelCatDel', function () {

        let tdCatName = $(this).closest('tr').find('.tdCatName');

        tdCatName.text(tdCatName.attr('data-oldcatname'));
        tdCatName.removeAttr('data-oldcatname');
        tdCatName.removeAttr('colspan');

        tdCatName.next().css('display', 'table-cell');

        return false;

    });


    /*
        Confirm deleting the template category
    */
    $(tableTemplateCategories).on('click', '.buttonConfirmCatDel', function () {

        let catID = this.getAttribute('data-catid');
        let replaceWith = $(this).closest('.alert').find('select').val();

        $.ajax({
            url: siteUrl + "templates/removeCategory",
            type: 'post',
            dataType: 'json',
            data: {catID: catID, replaceWith: replaceWith}
        }).done(function (ret) {

        	let className,
                notifyConfig = notify.config;

            if ( ret.responseCode === 1 ) {

            	notifyConfig.className = "joy";

            	$(this).closest('tr').fadeOut();

                templates.refreshTemplates();

            } else {
            	notifyConfig.className = "bummer";
            }

            $.notify(ret.responseHTML, notifyConfig);

        }.bind(this));

        return false;

    });

    /*
        Event handler edit category name
    */
    $(tableTemplateCategories).on('click', '.linkCatEdit', function () {

        var catID = this.getAttribute('data-cat-id');

        // create INPUT
        var input = $(`
            <div class="input-group" style="display: none; width: 100%" data-cat-id="${catID}">
                <input type="text" class="form-control" name="inputNewCategory" id="inputNewCategory" placeholder="">
                <span class="input-group-btn">
                    <button class="btn btn-primary js_buttonEditCategorySave">Save</button>
                    <button class="btn btn-default js_buttonEditCategoryCancel">Cancel</button>
                </span>
            </div>`);

        input.find('input').val($(this).closest('tr').find('.tdCatName').text());
        $(this).closest('tr').find('.tdCatName').text('');

        // place INPUT in TD
        $(this).closest('tr').find('.tdCatName').append(input);

        // focus on INPUT
        input.find('input').focus();

        // modify table layout
        $(this).closest('td').css('display', 'none');
        $(this).closest('tr').find('.tdCatName').attr('colspan', 2);

        input.fadeIn();

        return false;

    });


    /*
        Event handler cancel changing category name
    */
    $(tableTemplateCategories).on('click', '.js_buttonEditCategoryCancel', function () {

        var catID = $(this).closest('.input-group').attr('data-cat-id');

        removeCatUpdate(catID);
        
        return false;

    });


    /*
        Event handler save changed category name
    */
    $(tableTemplateCategories).on('click', '.js_buttonEditCategorySave', function () {

        updateCategory(this);

        return false;

    });

    $(tableTemplateCategories).on('keyup', 'input#inputNewCategory', function (e) {

    	let code = (e.keyCode ? e.keyCode : e.which);

        if ( code === 13 ) updateCategory( $(e.currentTarget).next().find('button.js_buttonEditCategorySave')[0] );

        return false;

    });


    function updateCategory(el) {

    	let category = $(el).closest('tr').find('.input-group input').val();
        let catID = $(el).closest('tr').find('.input-group').attr('data-cat-id');

        el.setAttribute('disabled', true);
        el.innerText = "Saving...";

        // send category ID to the server
        $.ajax({
            url: siteUrl + "templates/updateCategory",
            type: 'post',
            dataType: 'json',
            data: {catname: category, catid: catID}
        }).done(function (ret) {

        	let className,
                notifyConfig = notify.config;

            el.removeAttribute('disabled');
            el.innerText = "Save";

            if ( ret.responseCode === 1 ) {
            	notifyConfig.className = "joy";
                removeCatUpdate(catID);
                templates.refreshTemplates();
            } else {
            	notifyConfig.className = "bummer";
            }

            $.notify(ret.responseHTML, notifyConfig);

        });

    }


    function removeCatUpdate(catID) {

        var catName = $(tableTemplateCategories).find('.input-group[data-cat-id="' + catID + '"]').find('input').val(),
            theTR = $(tableTemplateCategories).find('.input-group[data-cat-id="' + catID + '"]').closest('tr');        

        // remove INPUT
        $(tableTemplateCategories).find('.input-group[data-cat-id="' + catID + '"]').fadeOut(function () {

            theTR.find('.tdCatName').text(catName).removeAttr('colspan');
            theTR.find('.actions').css('display', 'table-cell');

        });

    }


    function addCategory () {

    	buttonAddNewCategory.innerText = buttonAddNewCategory.getAttribute('data-loading');
        buttonAddNewCategory.setAttribute('disabled', true);

        $.ajax({
            url: siteUrl + "templates/addCategory",
            type: 'post',
            dataType: 'json',
            data: {catname: inputNewCategory.value}
        }).done(function (ret) {

        	buttonAddNewCategory.innerText = buttonAddNewCategory.getAttribute('data-text');
            buttonAddNewCategory.removeAttribute('disabled');

        	let className,
                notifyConfig = notify.config;

            if ( ret.responseCode === 1 ) {

            	notifyConfig.className = "joy";

            	$(tableTemplateCategories).find('tbody').replaceWith(ret.categories);
            	inputNewCategory.value = "";
                buttonAddNewCategory.disabled = true;

                templates.refreshTemplates();

            } else {
            	notifyConfig.className = "bummer";
            }

            $.notify(ret.responseHTML, notifyConfig);

        });

    }

}());