(function() {
	"use strict";

	var bConfig = require('../config.js');
	var publisher = require('../../vendor/publisher');
	var dnd = require('./xframednd');

	var components = {

		init: function () {

            publisher.subscribe('onSidebarDataReady', function () {

                this.dragDrop = new dnd({
                    draggables: '[data-sidesecond="components"] li img',
                    handle: '.dragdrop',
                    accepts: [
                        {draggable: '*[data-component]', droppable: '*[data-container]'},
                        {draggable: '*[data-component="grid"]', droppable: '.container, .container-fluid'},
                        {draggable: '*[data-component="navbar"]', droppable: '.container, .container-fluid'},
                        {draggable: 'ul[data-dnd-list] li', droppable: 'ul[data-dnd-list]'}
                    ]
                });

                this.dragDrop.start();

            }.bind(this));

			publisher.subscribe('onBlockLoaded', function (block) {

                this.dragDrop.addFrame({
                    target: block, 
                    selector: '*[data-component]:not([data-component="form-group"]), ul[data-dnd-list] li',
                });

            }.bind(this));

		}

	};

	components.init();

    module.exports = components;

}());