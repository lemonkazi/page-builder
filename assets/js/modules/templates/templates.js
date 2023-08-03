(function () {
	"use strict";

	const appUI = require('../shared/ui.js').appUI;
	const notify = require('../shared/notify');

	const templates = {

		init: function () {

			$('img', this.divSites).lazyload();

			$('[data-toggle=confirmation]').confirmation({
  				rootSelector: '[data-toggle=confirmation]',
			});

			$('a.linkDelTemplate').on('confirmed.bs.confirmation', function (e) {

				let button = e.currentTarget;

				button.setAttribute('disabled', true);

				$.ajax({
					url: appUI.baseUrl + "templates/del",
					method: 'post',
					dataType: 'json',
					data: {templateID: button.getAttribute('data-template-id')}
				}).done(function (ret) {

					button.removeAttribute('disabled');

					// Remove from UI
					$(button).closest('.site').fadeOut(function () {
						this.remove();
					});

					let className,
		                notifyConfig = notify.config;

		            if ( ret.responseCode === 1 ) {
		            	notifyConfig.className = "joy";
		            } else {
		            	notifyConfig.className = "bummer";
		            }

		            $.notify(ret.responseHTML, notifyConfig);

				});

			});

			$('#sites').on('change', 'select.jsCatSelect', function () {

				let divSite = $(this).closest('.site'),
					temp = divSite.attr('id').split("_"),
					templateID = temp[1];

				$.ajax({
					url: appUI.baseUrl + 'templates/setCatForTemplate',
            		type: 'POST',
            		data: {
            			templateID: templateID,
            			categoryID: this.value
            		},
            		dataType: "json"
				}).done(function (ret) {

					let className,
		                notifyConfig = notify.config;

		            if ( ret.responseCode === 1 ) {
		            	notifyConfig.className = "joy";
		            	divSite.attr('data-cat', this.value);
		            } else {
		            	notifyConfig.className = "bummer";
		            }

		            $.notify(ret.responseHTML, notifyConfig);

				}.bind(this));

			});

		}
        
    };

    module.exports.refreshTemplates = function () {

        $('#sites').load( appUI.baseUrl + "templates/loadTemplatesPartial", function () {

        	$("#sites select").select2({
	        	minimumResultsForSearch: -1
	        });

	        $('#sites img', this.divSites).lazyload();

        });

    }
    
    templates.init();

}());