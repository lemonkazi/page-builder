/* globals siteUrl: false */
(function () {
	"use strict";

	var ace = require('brace');
	var bConfig = require('../config.js');
	require('brace/mode/html');
    require('brace/theme/twilight');

	const allComponents = []; // keeps track of the all the components on the page
	const templateComponent = document.getElementById('templateComponent');
	const divAllComponents = document.getElementById('allComponents');
	const selectComponentCategory = document.getElementById('selectComponentCategory');
	const componentLoaderAnimation = document.getElementById('divComponentLoading');
	const divComponentModalBody = document.getElementById('divComponentModalBody');
	const modalManageComponent = document.getElementById('manageComponentModal');
	const formComponentDetails = document.getElementById('formComponentDetails');

	const buttonDeleteComponent = document.getElementById('buttonDeleteComponent');
	const componentDeleteNo = document.getElementById('componentDeleteNo');
    const componentDeleteYes = document.getElementById('componentDeleteYes');
    const buttonUpdateComponent = document.getElementById('buttonUpdateComponent');
    const confirmDeleteComponent = document.getElementById('confirmDeleteComponent');
    
    const buttonCreateComponent = document.getElementById('buttonCreateComponent');
    const divNewComponentModalBody = document.getElementById('divNewComponentModalBody');
    const formAddComponent = document.getElementById('formAddComponent');


	let nowShowingCat = '0';

	loadAllComponents();

	function loadAllComponents () {

		hideAllComponents();

		// load all
	    $.ajax({
	    	url: siteUrl + "builder_elements/loadAll",
	        type: 'get',
	        dataType: 'json'
	    }).done(function (ret) {
	    	
	    	let cats = Object.keys(ret.components);

	    	for( let cat of cats ) {

	    		Object.keys(ret.components[cat]).forEach(function (k, i) {

	    			let component = new Component(ret.components[cat][k]);

	    			component.render();

	    			allComponents.push(component);

	    		});

	    	}

	    	applyCatFilter();

	    });

	}

	function hideAllComponents () {

		for ( let component of allComponents ) {
    		component.delete();
    	}

	}

	function applyCatFilter() {

    	for( let component of allComponents ) {

    		if (component.components_category === nowShowingCat || nowShowingCat === '0') {

    			component.show();

    		} else {

    			component.hide();

    		}

    	}

    }

    /*
		Component filter
    */
    $(selectComponentCategory).on('change', (e) => {

    	nowShowingCat = e.target.value;

    	applyCatFilter();

    });


	function Component (componentData) {

		this.components_id = componentData.components_id;
		this.components_category = componentData.components_category;
		this.components_height = componentData.components_height;
		this.components_thumb = componentData.components_thumb;
		this.components_markup = componentData.components_markup;
		this.componentHTML = undefined;

		this.render = function () {

			let newComponentHTML = document.importNode(templateComponent.content, true);

			newComponentHTML.querySelector('.component').setAttribute('data-component-id', this.components_id);
    		newComponentHTML.querySelector('.component').setAttribute('data-component-cat', this.components_category);
    		newComponentHTML.querySelector('.component').addEventListener('click', this, false);
    		newComponentHTML.querySelector('img').setAttribute('src', siteUrl + this.components_thumb);

            this.componentHTML = newComponentHTML.querySelector('.component');

    		divAllComponents.appendChild(newComponentHTML);

		};

		this.loadEditModal = function () {

			divComponentModalBody.innerHTML = '';
        	$(componentLoaderAnimation).fadeIn();

        	$(modalManageComponent).modal('show');

        	$.ajax({
	            url: siteUrl + "builder_elements/loadComponent/" + this.components_id,
	            type: 'post',
	            dataType: 'json'
	        }).done(function (ret) {

	            $(componentLoaderAnimation).fadeOut(function () {

	                divComponentModalBody.appendChild($(ret.markup)[0]);

	                $(divComponentModalBody).find('select').select2({
	                    minimumResultsForSearch: -1
	                });

	                $(divComponentModalBody).find('select').select2({
	                    placeholder: "URl to HTML template"
	                });

	                // Source editor
	                let editor = ace.edit( 'aceEditComponent' );
            		editor.setTheme("ace/theme/" + bConfig.aceTheme);
            		editor.getSession().setUseWrapMode(true);
            		editor.getSession().setUseWorker(false);
            		editor.getSession().setMode("ace/mode/html");
            		editor.getSession().setValue($('#textareaComponentMarkup').val());
					editor.getSession().on('change', function(){
					  $('#textareaComponentMarkup').val(editor.getSession().getValue());
					});
            		editor.setOptions({
					    maxLines: Infinity
					});

	                $(formComponentDetails).off('submit').on('submit', () => {
	                	this.updateComponent();
	                	return false;
	                });

	                $(buttonDeleteComponent).off('click').on('click', (e) => {
	                	this.confirmDeleteComponent(e);
	                	return false;
	                });

	                $(componentDeleteNo).off('click').on('click', (e) => {
	                	this.cancelDeleteComponent();
	                	return false;
	                });

	                $(componentDeleteYes).off('click').on('click', (e) => {
	                	this.deleteComponent();
	                	return false;
	                });

	            }.bind(this));

        	}.bind(this));

		};

		this.updateComponent = function () {

			buttonUpdateComponent.setAttribute('disabled', true);
	        buttonUpdateComponent.innerText = buttonUpdateComponent.getAttribute('data-loading');

	        let form = $(formComponentDetails);
	        let formdata = false;

	        if (window.FormData){
	            formdata = new FormData(form[0]);
	        }

	        let formAction = form.attr('action');

	        $.ajax({
	            url : formAction,
	            data : formdata ? formdata : form.serialize(),
	            cache : false,
	            contentType : false,
	            processData : false,
	            dataType: "json",
	            type : 'POST'
	        }).done(function(ret) {

	            buttonUpdateComponent.removeAttribute('disabled');
	            buttonUpdateComponent.innerText = buttonUpdateComponent.getAttribute('data-text');

	            if ( ret.responseCode === 1 ) {

	                $(divComponentModalBody).find('.divBlockDetailsWrapper').fadeOut(function () {

	                    this.remove();

	                    divComponentModalBody.appendChild($(ret.responseHTML)[0]);

	                    $(divComponentModalBody).find('select').select2({
	                        minimumResultsForSearch: -1
	                    });

	                    // Source editor
		                let editor = ace.edit( 'aceEditComponent' );
	            		editor.setTheme("ace/theme/" + bConfig.aceTheme);
	            		editor.getSession().setUseWrapMode(true);
            			editor.getSession().setUseWorker(false);
	            		editor.getSession().setMode("ace/mode/html");
	            		editor.getSession().setValue($('#textareaComponentMarkup').val());
						editor.getSession().on('change', function(){
						  $('#textareaComponentMarkup').val(editor.getSession().getValue());
						});
	            		editor.setOptions({
						    maxLines: Infinity
						});

						setTimeout(function () {

		                    $(divComponentModalBody).find('.alert').fadeOut(function () {
		                        this.remove();
		                    });

		                }, 5000);

	                });

                    loadAllComponents();

	            } else {

	                $(divComponentModalBody).prepend($(ret.responseHTML));

	            }

	        });

		};

		this.confirmDeleteComponent = function (e) {

			$(e.target).fadeOut( () => { $(confirmDeleteComponent).fadeIn();});

		};

		this.cancelDeleteComponent = function (e) {

    		$(confirmDeleteComponent).fadeOut( () => { $(buttonDeleteComponent).fadeIn();});

    	};

    	this.deleteComponent = function (e) {

    		$.ajax({
	            url: siteUrl + "builder_elements/deleteComponent/" + this.components_id,
	            type: 'post',
	            dataType: 'json'
	        }).done(function(ret){

	            $(confirmDeleteComponent).fadeOut( () => { $(buttonDeleteComponent).fadeIn();});

	            if( ret.responseCode === 0 ) {

	                $(divComponentModalBody).prepend($(ret.responseHTML));

	            } else if ( ret.responseCode === 1 ) {

	                $(modalManageComponent).modal('hide');

	                this.delete();

                    loadAllComponents();

	            }

	        }.bind(this));

    	};

		this.delete = function () {

			$(this.componentHTML).fadeOut(() => {
    			this.componentHTML.remove();
    		});

    	};

    	this.hide = function () {

    		$(this.componentHTML).fadeOut();

    	};

    	this.show = function () {

    		$(this.componentHTML).fadeIn();

    	};

	}

	Component.prototype.handleEvent = function (event) {

    	switch (event.type) {
    		case "click":

    			this.loadEditModal();
    			break;

    	}

    }


    // create a new component
    $(formAddComponent).on('submit', function () {

        buttonCreateComponent.setAttribute('disabled', true);
        buttonCreateComponent.innerText = buttonCreateComponent.getAttribute('data-loading');

        let form = $(this);
        let formdata = false;

        if (window.FormData){
            formdata = new FormData(form[0]);
        }

        let formAction = form.attr('action');

        $.ajax({
            url : formAction,
            data : formdata ? formdata : form.serialize(),
            cache : false,
            contentType : false,
            processData : false,
            dataType: "json",
            type : 'POST'
        }).done(function (ret) {

            buttonCreateComponent.removeAttribute('disabled');
            buttonCreateComponent.innerText = buttonCreateComponent.getAttribute('data-text');

            if ( ret.responseCode === 1 ) {

                $(divNewComponentModalBody).prepend(ret.responseHTML);

                // reload blocks off the server
                loadAllComponents();

                // self destruct for the confirmation alert
                setTimeout(function () {

                    $(divNewComponentModalBody).find('.alert').alert('close');

                }, 3000);

            } else {

                $(divNewComponentModalBody).prepend($(ret.responseHTML));

            }

        });

        return false;

    });


}());