(function () {
	"use strict";

	var bConfig = require('../config.js');
    var ui = require('../shared/ui.js');
    var builder = require('./builder.js');
    var publisher = require('../../vendor/publisher');

	var grid = {

        gridViewSwitch: document.getElementById('gridViewSwitch'),
        reactivateGridMode: false,

        init: function () {

            $(this.gridViewSwitch).on('switchChange.bootstrapSwitch', function (event, state) {
                if (state) this.activateGridView();
                else this.deactivateGridView();
            }.bind(this));

            publisher.subscribe('onBeforeSave', function () {
                if ( ui.gridViewMode ) {
                    $(grid.gridViewSwitch).bootstrapSwitch('state', false);
                    grid.reactivateGridMode = true;
                }
            });

            publisher.subscribe('onAfterSave', function () {
                if ( grid.reactivateGridMode ) {
                    $(grid.gridViewSwitch).bootstrapSwitch('state', true);
                    grid.reactivateGridMode = false;
                }
            });

            publisher.subscribe('onComponentDragStart', function (draggedElement) {
                if ( draggedElement.hasAttribute('data-component') && draggedElement.getAttribute('data-component') === 'grid' && !ui.gridViewMode ) {
                    $(grid.gridViewSwitch).bootstrapSwitch('state', true);
                }
            });

            publisher.subscribe('onBlockLoaded', function (block) {
                if ( ui.gridViewMode ) block.gridView(true);
            });

        },

        activateGridView: function () {

            ui.gridViewMode = true;

            builder.site.gridView(true);

        },

        deactivateGridView: function () {

            ui.gridViewMode = false;

            builder.site.gridView(false);

        }

    };

    grid.init();

}());