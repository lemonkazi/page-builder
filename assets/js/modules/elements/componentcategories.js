/* globals siteUrl: false */
(function () {
	"use strict";
		
	const inputNewCategory = document.querySelector('#inputNewCategory');
    const buttonAddNewCategory = document.querySelector('#buttonAddNewCategory');
    const tableComponentCategories = document.querySelector('#tableComponentCategories');
    const manageCategoriesModal = document.querySelector('#manageCategoriesModal');

    $(inputNewCategory).on('keyup', function () {

        // empty input field?
        if ( this.value !== '' ) buttonAddNewCategory.removeAttribute('disabled');
        else buttonAddNewCategory.setAttribute('disabled', true);

        // unique input field value?
        if ( this.value !== '' ) {

            for ( var tdCatName of tableComponentCategories.querySelectorAll('.tdCatName') ) {
                if (tdCatName.innerText === this.value) buttonAddNewCategory.setAttribute('disabled', true);
            }

        }

    });

    $(buttonAddNewCategory).on('click', () => { addCategory();});
    $(inputNewCategory).on('keyup', function (e) {

        let code = (e.keyCode ? e.keyCode : e.which);

        if ( code === 13 ) addCategory();

        return false;

    });


    function addCategory () {

        buttonAddNewCategory.innerText = buttonAddNewCategory.getAttribute('data-loading');
        buttonAddNewCategory.setAttribute('disabled', true);

        $.ajax({
            url: siteUrl + "builder_elements/addComponentCategory",
            type: 'post',
            dataType: 'json',
            data: {catname: inputNewCategory.value}
        }).done(function (ret) {

            buttonAddNewCategory.innerText = buttonAddNewCategory.getAttribute('data-text');
            buttonAddNewCategory.removeAttribute('disabled');

            if ( ret.responseCode === 1 ) {// all good

                $(tableComponentCategories).find('tbody').replaceWith(ret.response);

                inputNewCategory.value = "";
                buttonAddNewCategory.disabled = true;

            } else if ( ret.responseCode === 0 ) {// not so good

                $(tableComponentCategories).before($(ret.response));

                setTimeout(function () {

                    $(manageCategoriesModal).find('.alert').fadeOut(function () {
                        this.remove();
                    });

                }, 5000);

            }

        });

    }


    /*
        Event handler edit category name
    */
    $(tableComponentCategories).on('click', '.linkComponentcatEdit', function () {

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

    function removeCatUpdate(catID) {

        var catName = $(tableComponentCategories).find('.input-group[data-cat-id="' + catID + '"]').find('input').val(),
            theTR = $(tableComponentCategories).find('.input-group[data-cat-id="' + catID + '"]').closest('tr');        

        // remove INPUT
        $(tableComponentCategories).find('.input-group[data-cat-id="' + catID + '"]').fadeOut(function () {

            theTR.find('.tdCatName').text(catName).removeAttr('colspan');
            theTR.find('.actions').css('display', 'table-cell');

        });

    }


    /*
        Event handler cancel changing category name
    */
    $(tableComponentCategories).on('click', '.js_buttonEditCategoryCancel', function () {

        var catID = $(this).closest('.input-group').attr('data-cat-id');

        removeCatUpdate(catID);
        
        return false;

    });


    /*
        Event handler save changed category name
    */
    $(tableComponentCategories).on('click', '.js_buttonEditCategorySave', function () {

        var category = $(this).closest('tr').find('.input-group input').val();
        var catID = $(this).closest('tr').find('.input-group').attr('data-cat-id');

        this.setAttribute('disabled', true);
        this.innerText = "Saving...";

        // send category ID to the server
        $.ajax({
            url: siteUrl + "builder_elements/updateComCategory",
            type: 'post',
            dataType: 'json',
            data: {catname: category, catid: catID}
        }).done(function (ret) {

            this.removeAttribute('disabled');
            this.innerText = "Save";

            if ( ret.responseCode === 1 ) {
                removeCatUpdate(catID);
            }

        }.bind(this));

        // update 

        return false;

    });


    /*
        Event handler delete category
    */
    $(tableComponentCategories).on('click', '.linkComponentcatDel', function () {

        var catID = this.getAttribute('data-cat-id'),
            catname = $(this).closest('tr').find('.tdCatName').text();

        // load current categories from server
        $.ajax({
            url: siteUrl + "builder_elements/loadDeleteComCatModal",
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
        Cancel deleting the component category
    */
    $(tableComponentCategories).on('click', '.buttonCancelComCatDel', function () {

        let tdCatName = $(this).closest('tr').find('.tdCatName');

        tdCatName.text(tdCatName.attr('data-oldcatname'));
        tdCatName.removeAttr('data-oldcatname');
        tdCatName.removeAttr('colspan');

        tdCatName.next().css('display', 'table-cell');

        return false;

    });


    /*
        Confirm deleting the component category
    */
    $(tableComponentCategories).on('click', '.buttonConfirmComCatDel', function () {

        let catID = this.getAttribute('data-catid');
        let replaceWith = $(this).closest('.alert').find('select').val();

        $.ajax({
            url: siteUrl + "builder_elements/removeComCategory",
            type: 'post',
            dataType: 'json',
            data: {catID: catID, replaceWith: replaceWith}
        }).done(function (ret) {

            $(this).closest('tr').fadeOut();

        }.bind(this));

        return false;

    });

}());