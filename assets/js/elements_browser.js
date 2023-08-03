/* globals siteUrl: false */

/*
    CSS
*/
require('../css/load-main.css');
require('../sass/browser.scss');
require('../sass/popover_confirmation.scss');

/*
    scripts (as conventional globals)
*/
require("script-loader!./vendor/jquery.min.js");
require("script-loader!./vendor/jquery-ui.min.js");
require("script-loader!./vendor/flat-ui-pro.min.js");
require("script-loader!./vendor/lazyload.min.js");
require("script-loader!./vendor/notify.min.js");
require("script-loader!./vendor/bootstrap-confirmation.min.js");


/*
    application modules
*/
let appUI = require('./modules/shared/ui.js').appUI;
let notify = require('./modules/shared/notify');


$(function(){
	"use strict";

	var filemanager = $('.filemanager'),
		breadcrumbs = $('.breadcrumbs'),
		fileList = filemanager.find('.data'),
		inputBrowserFile = document.getElementById('inputBrowserFile'),
		formUploadFile = document.getElementById('formUploadFile'),
		spanPath = document.getElementById('spanPath'),
		buttonFileUpload = document.getElementById('buttonFileUpload'),
		inputPath = document.getElementById('inputPath'),
		templateAdditionalItems = document.getElementById('additionalItems');

	// Start by fetching the file data from scan.php with an AJAX request

	function init () {

		$.getJSON(appUI.baseUrl + 'builder_elements/scan', function(data) {

			var response = [data],
				currentPath = '',
				breadcrumbsUrls = [];

			var folders = [],
				files = [];

			// This event listener monitors changes on the URL. We use it to
			// capture back/forward navigation in the browser.

			$(window).on('hashchange', function(){

				goto(window.location.hash);

				// We are triggering the event. This will execute 
				// this function on page load, so that we show the correct folder:

			}).trigger('hashchange');


			// Hiding and showing the search box

			filemanager.find('.search').click(function(){

				/*var search = $(this);

				search.find('span').hide();
				search.find('input[type=search]').show().focus();*/

			});


			// Listening for keyboard input on the search field.
			// We are using the "input" event which detects cut and paste
			// in addition to keyboard input.

			filemanager.find('input#inputSearchSites').on('input', function(e){

				folders = [];
				files = [];

				var value = this.value.trim();

				if(value.length) {

					filemanager.addClass('searching');

					// Update the hash on every key stroke
					window.location.hash = 'search=' + value.trim();

				}

				else {

					filemanager.removeClass('searching');
					window.location.hash = encodeURIComponent(currentPath);

				}

			}).on('keyup', function(e){

				// Clicking 'ESC' button triggers focusout and cancels the search

				var search = $(this);

				if(e.keyCode === 27) {

					search.trigger('focusout');

				}

			}).focusout(function(e){

				// Cancel the search

				var search = $(this);

				if(!search.val().trim().length) {

					window.location.hash = encodeURIComponent(currentPath);
					//search.hide();
					//search.parent().find('span').show();

				}

			});


			// Clicking on folders

			fileList.on('click', 'li.folders', function(e){
				e.preventDefault();

				var nextDir = $(this).find('a.folders').attr('href');

				if(filemanager.hasClass('searching')) {

					// Building the breadcrumbs

					breadcrumbsUrls = generateBreadcrumbs(nextDir);

					filemanager.removeClass('searching');
					filemanager.find('input[type=search]').val('').hide();
					filemanager.find('span').show();
				}
				else {
					breadcrumbsUrls.push(nextDir);
				}

				window.location.hash = encodeURIComponent(nextDir);
				currentPath = nextDir;
			});


			// Clicking on breadcrumbs

			breadcrumbs.on('click', 'a', function(e){
				e.preventDefault();

				var index = breadcrumbs.find('a').index($(this)),
					nextDir = breadcrumbsUrls[index];

				breadcrumbsUrls.length = Number(index);

				window.location.hash = encodeURIComponent(nextDir);

			});


			// Navigates to the given hash (path)

			function goto(hash) {

				hash = decodeURIComponent(hash).slice(1).split('=');

				if (hash.length) {
					var rendered = '';

					// if hash has search in it

					if (hash[0] === 'search') {

						filemanager.addClass('searching');
						rendered = searchData(response, hash[1].toLowerCase());

						if (rendered.length) {
							currentPath = hash[0];
							render(rendered);
						}
						else {
							render(rendered);
						}

					}

					// if hash is some path

					else if (hash[0].trim().length) {

						rendered = searchByPath(hash[0]);

						if (rendered.length) {

							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);

						}
						else {
							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);
						}

					}

					// if there is no hash

					else {
						currentPath = data.path;
						breadcrumbsUrls.push(data.path);
						render(searchByPath(data.path));
					}
				}
			}

			// Splits a file path and turns it into clickable breadcrumbs

			function generateBreadcrumbs(nextDir){
				var path = nextDir.split('/').slice(0);
				for(var i=1;i<path.length;i++){
					path[i] = path[i-1]+ '/' +path[i];
				}
				return path;
			}


			// Locates a file by path

			function searchByPath(dir) {
				var path = dir.split('/'),
					demo = response,
					flag = 0;

				for(var i=0;i<path.length;i++){
					for(var j=0;j<demo.length;j++){
						if(demo[j].name === path[i]){
							flag = 1;
							demo = demo[j].items;
							break;
						}
					}
				}

				demo = flag ? demo : [];
				return demo;
			}


			// Recursively search through the file tree

			function searchData(data, searchTerms) {

				data.forEach(function(d){
					if(d.type === 'folder') {

						searchData(d.items,searchTerms);

						if(d.name.toLowerCase().match(searchTerms)) {
							folders.push(d);
						}
					}
					else if(d.type === 'file') {
						if(d.name.toLowerCase().match(searchTerms)) {
							files.push(d);
						}
					}
				});
				return {folders: folders, files: files};
			}


			// Render the HTML for the file manager

			function render(data) {

				var scannedFolders = [],
					scannedFiles = [];

				if(Array.isArray(data)) {

					data.forEach(function (d) {

						if (d.type === 'folder') {
							scannedFolders.push(d);
						}
						else if (d.type === 'file') {
							scannedFiles.push(d);
						}

					});

				}
				else if(typeof data === 'object') {

					scannedFolders = data.folders;
					scannedFiles = data.files;

				}


				// Empty the old result and make the new one

				fileList.empty().hide();

				if(!scannedFolders.length && !scannedFiles.length) {
					filemanager.find('.nothingfound').show();
				}
				else {
					filemanager.find('.nothingfound').hide();
				}

				if(scannedFolders.length) {

					scannedFolders.forEach(function(f) {

						var itemsLength = f.items.length,
							name = escapeHTML(f.name),
							icon = '<span class="icon folder"></span>';

						if(itemsLength) {
							icon = '<span class="icon folder full"></span>';
						}

						if(itemsLength === 1) {
							itemsLength += ' item';
						}
						else if(itemsLength > 1) {
							itemsLength += ' items';
						}
						else {
							itemsLength = 'Empty';
						}

						var folder = $('<li class="folders"><a href="'+ f.path +'" title="'+ f.path +'" class="folders">'+icon+'<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
						folder.append($(delLink()));
						folder.appendTo(fileList);
					});

				}

				if(scannedFiles.length) {

					scannedFiles.forEach(function(f) {

						var fileSize = bytesToSize(f.size),
							name = escapeHTML(f.name),
							fileType = name.split('.'),
							icon = '<span class="icon file"></span>';

						fileType = fileType[fileType.length-1];

						icon = '<span class="icon file f-'+fileType+'">.'+fileType+'</span>';

						var file = $('<li class="files"><a href="' + appUI.baseUrl + "file_editor/open?file=" + encodeURIComponent(f.path) + '" title="'+ f.path +'" target="_blank" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');

						if ( fileType !== 'html' && fileType !== 'css' && fileType !== 'json' && fileType !== 'js' ) {

							file.find('a').attr('href', '#').attr('target', '').on('click', () => {return false;});
							file.addClass('disabled');
						
						}

						file.append($(delLink()));

						file.appendTo(fileList);

					});

				}


				// Generate the breadcrumbs

				var url = '';

				if(filemanager.hasClass('searching')){

					url = '<span>Search results: </span>';
					fileList.removeClass('animated');

				}
				else {

					fileList.addClass('animated');

					breadcrumbsUrls.forEach(function (u, i) {

						var name = u.split('/');

						if (i !== breadcrumbsUrls.length - 1) {
							url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a><span class="arrow">/</span>';
						}
						else {
							url += '<span class="folderName">' + name[name.length-1] + '</span>';
						}

					});

				}

				breadcrumbs.find('span').text('').append(url);


				// Show the generated elements
				let additionalBlocks = document.importNode(templateAdditionalItems.content, true);

				fileList.append($(additionalBlocks));

				//fileList.animate({'display':'inline-block'});
				fileList.fadeIn();

				$('[data-toggle=confirmation]').confirmation({
  					rootSelector: '[data-toggle=confirmation]',
				});

			}


			// This function escapes special html characters in names

			function escapeHTML(text) {
				return text.replace(/\&/g,'&amp;').replace(/</g,'&lt;').replace(/\>/g,'&gt;');
			}


			// Convert file sizes from bytes to human readable units

			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes === 0) return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}

		});

		function delLink () {

			let delLink = document.createElement('A');
			delLink.innerHTML = '<span class="fui-trash"></span>';
			delLink.classList.add('jsDelFile');
			delLink.classList.add('delFile');
			delLink.setAttribute('data-toggle', 'confirmation');
			delLink.setAttribute('data-title', 'Are you sure?');
			delLink.setAttribute('data-content', 'Deleting this file can not be undone.');
			delLink.setAttribute('data-popout', true);

			$(delLink).on('confirmed.bs.confirmation', function (e) {

				let theFile = e.currentTarget.previousSibling.title;
				
				$.ajax({
					url: appUI.baseUrl + "builder_elements/delFile",
					type: 'post',
        			dataType: 'json',
        			data: {url: theFile}
				}).done(function (ret) {

					let className,
		                notifyConfig = notify.config;

		            if ( ret.responseCode === 1 ) {
		            	notifyConfig.className = "joy";
		            	init();
		            } else {
		            	notifyConfig.className = "bummer";
		            }

		            $.notify(ret.responseHTML, notifyConfig);

				});

			});

			$(delLink).on('click', function (e) {
				e.stopPropagation();
			});

			return delLink;

		}	

	}

	init();
	
	$(inputBrowserFile).on('change', function () {

		if ( this.value !== '' ) {
			$(this).closest('form').find('button[type="submit"]').removeClass('disabled');
		} else {
			$(this).closest('form').find('button[type="submit"]').addClass('disabled');
		}

	});

	$(formUploadFile).on('submit', function () {

		inputPath.value = spanPath.innerText;

		buttonFileUpload.setAttribute('disabled', true);

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

        	buttonFileUpload.removeAttribute('disabled');

        	let className,
                notifyConfig = notify.config;

            if ( ret.responseCode === 1 ) {
            	notifyConfig.className = "joy";
            	init();
            } else {
            	notifyConfig.className = "bummer";
            }

            $.notify(ret.responseHTML, notifyConfig);

        });

		return false;

	});

	$('#data').on('keyup', 'input#inputNewFolder', function () {

		let button = this.nextSibling,
			value = this.value.trim();

		if ( value === '' || value === ' ' || !value.match(/^[a-z0-9\.]+$/i) ) {
			button.classList.add('disabled');

			if ( !value.match(/^[a-z0-9\.]+$/i) ) {
				let notifyConfig = notify.config;
				notifyConfig.className = "bummer";

				$.notify(this.getAttribute('data-warning'), notifyConfig);
			}

		} else {
			button.classList.remove('disabled');
		}

	});

	$('#data').on('submit', 'form#formAddFolder', function () {

		let folderName = document.getElementById('inputNewFolder').value.trim(),
			button = document.getElementById('buttonAddFolder');

		if ( folderName === '' || !folderName.match(/^[a-z0-9\.]+$/i)  ) {

			let notifyConfig = notify.config;
				notifyConfig.className = "bummer";

			$.notify(this.getAttribute('data-warning'), notifyConfig);

			return false;

		}

		button.setAttribute('disabled', true);

		$.ajax({
			url: this.action,
			type: 'post',
			dataType: 'json',
			data: {
				folder: folderName,
				path: spanPath.innerText
			}
		}).done(function (ret) {

			button.removeAttribute('disabled');

        	let className,
                notifyConfig = notify.config;

            if ( ret.responseCode === 1 ) {
            	notifyConfig.className = "joy";
            	init();
            } else {
            	notifyConfig.className = "bummer";
            }

            $.notify(ret.responseHTML, notifyConfig);

		});

		return false;

	});

});



/* this attempts to load custom JS code to include in the elements_components page */
try {
	require('./custom/elements_browser.js');
} catch (e) {
	
}