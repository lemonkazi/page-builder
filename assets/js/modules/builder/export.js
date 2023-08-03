(function () {
	"use strict";

	var bConfig = require('../config.js');
    var siteBuilder = require('./builder.js');
    var utils = require('../shared/utils.js');

	var bexport = {
        
        modalExport: document.getElementById('exportModal'),
        buttonExport: document.getElementById('exportPage'),
        
        init: function() {
            
            $(this.modalExport).on('show.bs.modal', this.doExportModal);
            $(this.modalExport).on('shown.bs.modal', this.prepExport);
            $(this.modalExport).find('form').on('submit', this.exportFormSubmit);
            
            //reveal export button
            $(this.buttonExport).show();
        
        },
        
        doExportModal: function() {
                        
            $('#exportModal > form #exportSubmit').show('');
            //$('#exportModal > form #exportCancel').text('Cancel & Close');
            
        },
        
        
        /*
            prepares the export data
        */
        prepExport: function(e) {

            //delete older hidden fields
            $('#exportModal form input[type="hidden"].pages').remove();

            siteBuilder.site.sitePages.forEach( (page) => {
                
                var newInput = $('<input type="hidden" name="pages['+page.name+']" class="pages" value="">');
                $('#exportModal form').prepend( newInput );

            });
            
        },
        
        
        /*
            event handler for the export from submit
        */
        exportFormSubmit: function() {
                        
            $('#exportModal > form #exportSubmit').hide('');
            $('#exportModal > form #exportCancel').text('Close Window');
        
        }
    
    };
        
    bexport.init();

}());