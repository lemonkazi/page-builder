/* globals siteUrl: false */
(function () {
	"use strict";

    const manageBlockModal = document.getElementById('manageBlockModal');
    const addBlockModal = document.getElementById('addBlockModal');
    const divAddBlockCatSelectWrapper = document.getElementById('divAddBlockCatSelectWrapper');
    const fileinputNewBlock = document.getElementById('fileinputNewBlock');
    const selectTemplateFile = document.getElementById('selectTemplateFile');

    const confirmDeleteBlock = document.getElementById('confirmDeleteBlock');

    const divNewBlockModalBody = document.getElementById('divNewBlockModalBody');
    const formAddBlock = document.getElementById('formAddBlock');
    const buttonCreateBlock = document.getElementById('buttonCreateBlock');

    const templateBlock = document.getElementById('templateBlock');
    const divAllBlocks = document.getElementById('allBlocks');
    const divBlockModalBody = document.getElementById('divBlockModalBody');
    const blockLoaderAnimation = document.getElementById('divBlockLoading');
    const modalManageBlock = document.getElementById('manageBlockModal');
    const buttonUpdateBlock = document.getElementById('buttonUpdateBlock');
    const formBlockDetails = document.getElementById('formBlockDetails');
    const buttonDeleteBlock = document.getElementById('buttonDeleteBlock');
    const blockDeleteNo = document.getElementById('blockDeleteNo');
    const blockDeleteYes = document.getElementById('blockDeleteYes');

    const selectBlockCategory = document.getElementById('selectBlockCategory');
    let nowShowingCat = '0';
    let updatingBlock = undefined;

    let allBlocks = []; // keeps track of the all the blocks on the page

    loadAllBlocks();

    $('.modal').on('change', 'select.selectTemplateFile', function () {

        let theButton = $(this).closest('.modal').find('button[type="submit"]');
        let theInput = $(this).closest('form').find('input[name="blockHeight"]');

        if ( $(this).val() === '' ) {

            $(theInput).val('0');
            return false;

        }
        

        // disable the Save Changes button
        theButton.addClass('disabled');
        theButton.find('.tlabel').text( theButton.attr('data-calc-height') );

        let newFrame = document.createElement('IFRAME');
        newFrame.style.position = 'absolute';
        newFrame.style.top = '0px';
        newFrame.style.left = '-1200px';
        newFrame.style.width = '1200px';

        document.querySelector('body').appendChild(newFrame);

        newFrame.src = siteUrl + this.value;

        $(newFrame).on('load', function () {

            var iframeDocument = newFrame.contentDocument || newFrame.contentWindow.document;

            $(theInput).val(iframeDocument.querySelector('body').offsetHeight);

            theButton.removeClass('disabled');
            theButton.find('.tlabel').text( theButton.attr('data-text') );

        });

    });

    $(addBlockModal).on('show.bs.modal', function () {

        // load categories off the server
        $.get(siteUrl + "builder_elements/catDropdown", function (ret) {

            $(divAddBlockCatSelectWrapper).find('select').select2('destroy').remove();

            $(divAddBlockCatSelectWrapper).append($(ret));

            $(divAddBlockCatSelectWrapper).find('select').select2();

        });

        // hide alerts
        $(addBlockModal).find('.alert').alert('close');

        // template dropdown
        $(selectTemplateFile).val('');
        $(selectTemplateFile).trigger('change');

    });


    function loadAllBlocks () {

    	// hide whatever blocks are there now
    	hideAllBlocks();
        allBlocks = [];

    	// load all
	    $.ajax({
	    	url: siteUrl + "builder_elements/loadAll",
	        type: 'get',
	        dataType: 'json'
	    }).done(function (ret) {

	    	let allBlocksCat = Object.keys(ret.elements)[1]; // this gets us the first key

	    	for ( const block of ret.elements[allBlocksCat] ) {
	    		
	    		let newBlock = new Block(block);
	    		newBlock.render();

	    		allBlocks.push(newBlock);

                if ( updatingBlock && updatingBlock.blocks_id === block.blocks_id ) {
                    newBlock.forceImageReload();
                }

	    	}

            applyCatFilter();

	    });

    }

    function hideAllBlocks() {

    	for ( let block of allBlocks ) {
    		block.delete();
    	}

    }

    function applyCatFilter() {

    	for( let block of allBlocks ) {

    		if (block.blocks_category === nowShowingCat || nowShowingCat === '1' || nowShowingCat === '0') {

                if ( nowShowingCat !== '1' && nowShowingCat !== '0' ) {
                    block.blockHTML.querySelector('img').src = block.blockHTML.querySelector('img').getAttribute('data-original');
                }

    			block.show();

    		} else {

    			block.hide();

    		}

    	}

        if ( nowShowingCat === '1' || nowShowingCat === '0' ) {
            $('img.lazyload').lazyload({
                failure_limit : 999
            });
        }

    }

    function Block (blockData) {

    	this.blocks_id = blockData.blocks_id;
    	this.blocks_category = blockData.blocks_category;
    	this.blocks_url = blockData.blocks_url;
    	this.blocks_thumb = blockData.blocks_thumb;
    	this.blockHTML = undefined;

    	this.render = function () {
    		
    		let newBlockHTML = document.importNode(templateBlock.content, true);

    		newBlockHTML.querySelector('.block').setAttribute('data-block-id', this.blocks_id);
    		newBlockHTML.querySelector('.block').setAttribute('data-block-cat', this.blocks_category);
    		newBlockHTML.querySelector('.block').addEventListener('click', this, false);
    		newBlockHTML.querySelector('img').setAttribute('data-original', siteUrl + this.blocks_thumb);
            newBlockHTML.querySelector('img').classList.add('lazyload');

    		this.blockHTML = newBlockHTML.querySelector('.block');

    		divAllBlocks.appendChild(newBlockHTML);

    	};

        this.forceImageReload = function () {

            let newUrl = $(this.blockHTML).find('img').attr('data-original') + "?" + new Date().getTime();

            $(this.blockHTML).find('img').attr('src', newUrl);
            $(this.blockHTML).find('img').attr('data-original', newUrl);

        };

    	this.loadEditModal = function () {

    		divBlockModalBody.innerHTML = '';
        	$(blockLoaderAnimation).fadeIn();

    		$(modalManageBlock).modal('show');

	        $.ajax({
	            url: siteUrl + "builder_elements/loadBlock/" + this.blocks_id,
	            type: 'post',
	            dataType: 'json'
	        }).done(function (ret) {

	            $(blockLoaderAnimation).fadeOut(function () {

                    modalManageBlock.querySelector('input[name="blockHeight"]').value = 0;

	                divBlockModalBody.appendChild($(ret.markup)[0]);

	                $(divBlockModalBody).find('select').select2({
	                    minimumResultsForSearch: -1
	                });

	                $(divBlockModalBody).find('.js_popoutLink, .heightHelp').tooltip();
	                $(divBlockModalBody).find(':checkbox').radiocheck();
	                $(divBlockModalBody).find('select').select2({
	                    placeholder: "URl to HTML template"
	                });

	                $(formBlockDetails).off('submit').on('submit', () => {
	                	this.updateBlock();
	                	return false;
	                });

	                $(buttonDeleteBlock).off('click').on('click', (e) => {
	                	this.confirmDeleteBlock(e);
	                	return false;
	                });

	                $(blockDeleteNo).off('click').on('click', (e) => {
	                	this.cancelDeleteBlock();
	                	return false;
	                });

	                $(blockDeleteYes).off('click').on('click', (e) => {
	                	this.deleteBlock();
	                	return false;
	                });

	            }.bind(this));

        	}.bind(this));

    	};

    	this.updateBlock = function () {

    		buttonUpdateBlock.setAttribute('disabled', true);
	        buttonUpdateBlock.querySelector('.tlabel').innerText = buttonUpdateBlock.getAttribute('data-loading');

            updatingBlock = this;

	        let form = $(formBlockDetails);
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

	            buttonUpdateBlock.removeAttribute('disabled');
	            buttonUpdateBlock.querySelector('.tlabel').innerText = buttonUpdateBlock.getAttribute('data-text');

	            if ( ret.responseCode === 1 ) {

	                $(divBlockModalBody).find('.divBlockDetailsWrapper').fadeOut(function () {

	                    this.remove();

	                    divBlockModalBody.appendChild($(ret.responseHTML)[0]);

	                    $(divBlockModalBody).find('select').select2({
	                        minimumResultsForSearch: -1
	                    });

	                    $(divBlockModalBody).find('.js_popoutLink, .heightHelp').tooltip();
	                    $(divBlockModalBody).find(':checkbox').radiocheck();

                        // reload the image
                        $(divBlockModalBody).find('.blockThumbnail img').attr('src', $(divBlockModalBody).find('.blockThumbnail img').attr('src') + "?" + new Date().getTime());

	                });

                    // self destruct for the confirmation alert
                    setTimeout(function () {

                        $(divBlockModalBody).find('.alert').alert('close');

                    }, 5000);

                    loadAllBlocks();

	            } else {

	                $(divBlockModalBody).prepend($(ret.responseHTML));

	            }

	        });

    	};

    	this.confirmDeleteBlock = function (e) {

    		$(e.target).fadeOut( () => { $(confirmDeleteBlock).fadeIn();});

    	};

    	this.cancelDeleteBlock = function (e) {

    		$(confirmDeleteBlock).fadeOut( () => { $(buttonDeleteBlock).fadeIn();});

    	};

    	this.deleteBlock = function (e) {

    		$.ajax({
	            url: siteUrl + "builder_elements/deleteBlock/" + this.blocks_id,
	            type: 'post',
	            dataType: 'json'
	        }).done(function(ret){

	            $(confirmDeleteBlock).fadeOut( () => { $(buttonDeleteBlock).fadeIn();});

	            if( ret.responseCode === 0 ) {

	                $(divBlockModalBody).prepend($(ret.responseHTML));

	            } else if ( ret.responseCode === 1 ) {

	                $(manageBlockModal).modal('hide');

	                this.delete();

                    loadAllBlocks();

	            }

	        }.bind(this));

    	};

    	this.delete = function () {

    		$(this.blockHTML).fadeOut(() => {
    			this.blockHTML.remove();
    		});

    	};

    	this.hide = function () {

    		$(this.blockHTML).fadeOut();

    	};

    	this.show = function () {

    		$(this.blockHTML).fadeIn();

    	};

    }

    Block.prototype.handleEvent = function (event) {

    	switch (event.type) {
    		case "click":

    			this.loadEditModal();
    			break;

    	}

    }


    /*
        Create new block
    */
    $(formAddBlock).on('submit', function () {

        buttonCreateBlock.setAttribute('disabled', true);
        buttonCreateBlock.querySelector('.tlabel').innerText = buttonCreateBlock.getAttribute('data-loading');

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

            buttonCreateBlock.removeAttribute('disabled');
            buttonCreateBlock.querySelector('.tlabel').innerText = buttonCreateBlock.getAttribute('data-text');

            if ( ret.responseCode === 1 ) {

                $(divNewBlockModalBody).prepend(ret.responseHTML);

                // reload blocks off the server
                loadAllBlocks();

                // self destruct for the confirmation alert
                setTimeout(function () {

                    $(divNewBlockModalBody).find('.alert').alert('close');

                }, 3000);

            } else {

                $(divNewBlockModalBody).prepend($(ret.responseHTML));

            }

        });

        return false;

    });


    /*
		Block filter
    */
    $(selectBlockCategory).on('change', (e) => {

    	nowShowingCat = e.target.value;

    	applyCatFilter();

    });


}());