/* jshint ignore:start */
(function () {
    'use strict';

    var builder = require('./builder');
    var publisher = require('../../vendor/publisher')

    // Polyfill for the .matches() function
    if (!Element.prototype.matches) {
        Element.prototype.matches = 
            Element.prototype.matchesSelector || 
            Element.prototype.mozMatchesSelector ||
            Element.prototype.msMatchesSelector || 
            Element.prototype.oMatchesSelector || 
            Element.prototype.webkitMatchesSelector ||
            function(s) {
                var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                    i = matches.length;
                while (--i >= 0 && matches.item(i) !== this) {}
                return i > -1;            
            };
    }

    var Dnd = function (options) {

        // Configurable options
        this.draggables = options.draggables || null;                 //draggable elements in the parent frame
        this.inframeCssUrl = options.inFrameCssUrl || null;           //css loaded in the iframes
        this.handle = options.handle || null;                         //the drag handle 
        this.accepts = options.accepts || [                           //determines which elements can be dropped into which containers
            {draggable: '*', droppable: '*'}
        ],

        this.currentElement;
        this.currentElementChangeFlag;
        this.elementRectangle;
        this.countdown;
        this.dragoverqueue_processtimer;
        this.currentlyDragged;
        this.oldParent;
        this.sourceBlock;

        this.divScrollTopID = '#top_scroll_page',
        this.divScrollBottomID = '#bottom_scroll_page';

        //* console.log(this);

    };

    Dnd.prototype.start = function () {

        $(this.draggables).on('dragstart', this.dragStart.bind(this));
        $(this.draggables).on('dragend', this.dragStop.bind(this));
        $(this.draggables).on('mousedown', this.beforeDrag.bind(this));

    };

    Dnd.prototype.beforeDrag = function (e) {

        e.stopPropagation();

        this.target = e.target;

        for ( var x = 0; x < this.accepts.length; x++ ) {
            if ( $(e.currentTarget).is( this.accepts[x].draggable ) ) {
                this.oldParent = $(this.target).closest( this.accepts[x].droppable );
            }
        }

        //console.log(this.target);

    };

    Dnd.prototype.makeid = function () {

        var text = "",
            possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i = 0; i < 5; i++ ) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;

    };

    Dnd.prototype.isAllowed = function (dragged, dropped) {

        // console.log('dragged :', dragged);
        // console.log('dropped :', dropped);

        if (DragDropFunctions.isGridNested(dragged, dropped)) {
            return false;
        }

        if ( dragged.contains(dropped) ) return false;

        if ( this.accepts === null ) return true;

        for ( var x = 0; x < this.accepts.length; x++ ) {

            if ( $(dragged).is( this.accepts[x].draggable ) ) {

                if ( $(dropped).is ( this.accepts[x].droppable ) ) return true;
                else continue;

            }

        }

        return false;

    },

    Dnd.prototype.setCurrentElement = function (element) {

        if ( !this.isAllowed(this.currentlyDragged, element) ) {

            for ( var x = 0; x < this.accepts.length; x++ ) {

                if ( $(element).is( this.accepts[x].draggable ) ) {

                    //console.log(this.accepts[x].droppable);

                    this.currentElement = $(element).closest( this.accepts[x].droppable );

                }

            }

        } else {

            this.currentElement = $(element);

        }

        //console.log(this.currentElement);

    },

    Dnd.prototype.dragStart = function (event, block) {

        //make sure the scroll assistants are visible
        document.querySelector(this.divScrollTopID).style.display = 'block';
        document.querySelector(this.divScrollBottomID).style.display = 'block';

        this.sourceBlock = block;

        //if the dragged element is on the canvas, it can only be dragged if we're in component mode
        if ( window.canvasMode === 'grid' && event.currentTarget.hasAttribute('data-component') && event.currentTarget.getAttribute('data-component') !== 'grid' ) {
            return false;
        }

        event.stopPropagation();

        if( this.handle !== null && this.target && this.target.querySelector(this.handle) ) {//check if the draggable contains a handle

            var handle = this.target.querySelector(this.handle);
            if (!handle.contains(this.target)) event.preventDefault();
        
        }

        var insertingHTML;

        event.currentTarget.id = this.makeid();

        //* console.log("Drag Started");
        this.dragoverqueue_processtimer = setInterval (function() {
            DragDropFunctions.ProcessDragOverQueue();
        }, 100);

        if( event.currentTarget.hasAttribute('data-insert-html') ) {
            insertingHTML = $(event.currentTarget).attr('data-insert-html');
            this.currentlyDragged = $(insertingHTML).get(0);
        } else {
            insertingHTML = '#' + event.currentTarget.id;
            this.currentlyDragged = $(event.currentTarget).closest('body').find(insertingHTML).get(0);
        }

        event.originalEvent.dataTransfer.setData("Text", insertingHTML);

        //var dt = event.originalEvent.dataTransfer;
        //if (dt.setDragImage) dt.setDragImage(event.target, 0, 0);

        publisher.publish('onComponentDragStart', this.currentlyDragged);
			
		$('.sideSecond.open').attr('class', 'sideSecond open_');
		//$("button[class*='active']","#builder").css("position","absolute");

    };

    Dnd.prototype.dragStop = function () {

        //hide the scroll assistants
        document.querySelector(this.divScrollTopID).style.display = 'block';
        document.querySelector(this.divScrollBottomID).style.display = 'block';

        //* console.log("Drag End");
        clearInterval(this.dragoverqueue_processtimer);
        DragDropFunctions.removePlaceholder();
        DragDropFunctions.ClearContainerContext();

        this.target.removeAttribute('id');

        if (this.oldParent && this.oldParent.find('*').size() === 0) this.oldParent.text('');
		
		setTimeout( () => $('.sideSecond.open_').attr('class', 'sideSecond open'), 1500);
		//setTimeout( () => $("button[class*='active']","#builder").css("position","fixed"), 2000);

    };

    Dnd.prototype.addFrame = function (options) {

        var clientFrameWindow = options.target.frame,
            elements,
            droppableString = '';

        var htmlBody = options.target.frameDocument.querySelectorAll('body, html');

        for ( var x = 0; x < this.accepts.length; x++ ) { 
            droppableString += this.accepts[x].droppable;
            if ( x < this.accepts.length-1 ) droppableString += ', ';
        }

        $(htmlBody).on('dragenter', '*', function (event) {

            event.stopPropagation();

            //this.setCurrentElement( event.currentTarget );

            this.currentElement = $(event.currentTarget);

            this.currentElementChangeFlag = true;
            this.elementRectangle = event.target.getBoundingClientRect();
            this.countdown = 1;

        }.bind(this)).on('dragover', '*', function (event) {

            //make sure the dragged element is allowed inside this droppable
            //if ( !this.isAllowed(this.currentlyDragged, event.currentTarget) ) return false;

            event.preventDefault();
            event.stopPropagation();

            if( this.countdown%15 !== 0 && this.currentElementChangeFlag === false) {

                this.countdown = this.countdown+1;
                return;
            
            }

            event = event || window.event;

            var x = event.originalEvent.clientX;
            var y = event.originalEvent.clientY;

            this.countdown = this.countdown+1;
            this.currentElementChangeFlag = false;
            var mousePosition = {x:x, y:y};
            DragDropFunctions.AddEntryToDragOverQueue(this.currentElement, this.elementRectangle, mousePosition, this.accepts, this.currentlyDragged, this, droppableString);

        }.bind(this));


        $(options.target.frameDocument).find('body,html').on('drop', function(event) {

            event.preventDefault();
            event.stopPropagation();
            
            var e;

            if (event.isTrigger)e = triggerEvent.originalEvent;
            else var e = event.originalEvent;

            try {

                var textData = e.dataTransfer.getData('text');
                var insertionPoint = $(event.currentTarget).find('.drop-marker');

                var parent = insertionPoint.get(0).parentNode;

                var checkDiv = ( textData[0] === '#' )? $('iframe', this.frameContainer).contents().find(textData) : $(textData);

                //make sure we can drop this element
                if ( !this.isAllowed(checkDiv.get(0), parent) ) return false;

                checkDiv.get(0).setAttribute('draggable', true);

                insertionPoint.after(checkDiv);
                insertionPoint.remove();

                //special attention for when a button is dragged from the sidebar into a navbar
                if ( checkDiv[0].hasAttribute('data-component') && checkDiv[0].getAttribute('data-component') === 'button' ) {

                    if ( checkDiv[0].parentNode.classList.contains('navbar-buttons') ) {

                        checkDiv[0].querySelector('.btn').classList.add('navbar-btn');

                    }

                }

            }
            catch(e) {
                console.log(e);
            }

            setTimeout(function () {
                options.target.heightAdjustment();
            }, 100);

            // options.target.heightAdjustment();
            if ( this.sourceBlock !== undefined ) this.sourceBlock.heightAdjustment();

            publisher.publish('onComponentDrop', options.target);

            builder.site.setPendingChanges(true);

            DragDropFunctions.ClearContainerContext();

        }.bind(this));

        //prep draggable elements
        $(options.target.frameDocument).find( options.selector ).each(function () {
            this.setAttribute('draggable', true);
        });

        //$(htmlBody).on('dragstart', options.selector, this.dragStart.bind(this));
        $(htmlBody).on('dragstart', options.selector, function (e) {
            this.dragStart(e, options.target);
        }.bind(this));
        $(htmlBody).on('dragend', options.selector, this.dragStop.bind(this));
        $(htmlBody).on('mousedown', options.selector, this.beforeDrag.bind(this));

    }





    var DragDropFunctions = {

        dragoverqueue : [],
        accepts: [],

        GetMouseBearingsPercentage : function($element, elementRect, mousePos) {
            
            if(!elementRect) elementRect = $element.get(0).getBoundingClientRect();

            var mousePosPercent_X = ((mousePos.x - elementRect.left) / (elementRect.right - elementRect.left)) * 100;
            var mousePosPercent_Y = ((mousePos.y - elementRect.top) / (elementRect.bottom - elementRect.top)) * 100;

            return {x:mousePosPercent_X, y:mousePosPercent_Y};

        },

        OrchestrateDragDrop : function($element, elementRect, mousePos) {

            var theDoc = $element.get(0).ownerDocument;

            //If no element is hovered or element hovered is the placeholder -> not valid -> return false;
            if(!$element || $element.length === 0 || !elementRect || !mousePos) return false;

            if($element.is('html')) $element = $element.find('body');

            //Top and Bottom Area Percentage to trigger different case. [5% of top and bottom area gets reserved for this]
            var breakPointNumber = {x:5, y:5};

            var mousePercents = this.GetMouseBearingsPercentage($element, elementRect, mousePos);

            if((mousePercents.x > breakPointNumber.x && mousePercents.x < 100 - breakPointNumber.x) && (mousePercents.y > breakPointNumber.y && mousePercents.y < 100 - breakPointNumber.y)) {

                //console.log('Case 1');

                if ( $element[0].classList.contains('item') ) {

                    $element = $element.find('.container').first();

                } else {

                    if ( $element[0].hasAttribute('data-parent') && $element[0].getAttribute('data-parent') === '1' ) {

                        $element = $element.parent();

                    } else {

                        $element = $element.closest(this.droppableString);

                    }

                }

                //Case 1 -
                var $tempelement = $element.clone();
                $tempelement.find(".drop-marker").remove();

                if( $tempelement.html() === "" && !this.checkVoidElement($tempelement) ) {

                    //console.log('Case 1.1');

                    if(mousePercents.y < 90) return this.PlaceInside($element);
                
                } else if( $tempelement.children().length === 0 || $element[0].tagName === 'LI' ) {

                    //console.log('Case 1.2');

                    //text element detected
                    //console.log("Text Element");
                    //this.DecideBeforeAfter($element,mousePercents);
                    this.PlaceInside($element);

                } else if( $tempelement.children().length === 1 ) {

                    //console.log('Case 1.3');

                    //only 1 child element detected
                    //console.log("1 Child Element");
                    this.DecideBeforeAfter($element.children(":not(.drop-marker,[data-dragcontext-marker])").first(), mousePercents);
                
                } else {

                    //console.log('Case 1.4');

                    var positionAndElement = this.findNearestElement($element, mousePos.x, mousePos.y);
                    this.DecideBeforeAfter(positionAndElement.el, mousePercents, mousePos);
                    //more than 1 child element present
                    //console.log("More than 1 child detected");

                }
            
            } else if((mousePercents.x <= breakPointNumber.x) || (mousePercents.y <= breakPointNumber.y)) {

                var validElement = null

                if(mousePercents.y <= mousePercents.x) validElement = this.FindValidParent($element, 'top');
                
                else validElement = this.FindValidParent($element, 'left');

                if(validElement.is("body,html")) {
                    validElement = $(theDoc).find("body").children(":not(.drop-marker,[data-dragcontext-marker])").first();
                }

                this.DecideBeforeAfter(validElement, mousePercents, mousePos);

                //console.log('Case 2', validElement);

            } else if((mousePercents.x >= 100-breakPointNumber.x) || (mousePercents.y >= 100-breakPointNumber.y)) {

                var validElement = null

                if(mousePercents.y >= mousePercents.x) validElement = this.FindValidParent($element, 'bottom');
                
                else validElement = this.FindValidParent($element, 'right');

                if(validElement.is("body,html")) {
                    validElement = $(theDoc).find("body").children(":not(.drop-marker,[data-dragcontext-marker])").last();
                }

                this.DecideBeforeAfter(validElement,mousePercents,mousePos);

                //console.log('Case 3', validElement);
            
            }
        
        },

        DecideBeforeAfter : function($targetElement, mousePercents, mousePos) {

            if(mousePos) {
                mousePercents = this.GetMouseBearingsPercentage($targetElement, null, mousePos);
            }

            /*if(!mousePercents)
             {
             mousePercents = this.GetMouseBearingsPercentage($targetElement, $targetElement.get(0).getBoundingClientRect(), mousePos);
             } */

            var $orientation = ($targetElement.css('display') === "inline" || $targetElement.css('display') === "inline-block");

            if($targetElement.is("br")) $orientation = false;

            if($orientation) {

                if(mousePercents.x < 50) {

                    return this.PlaceBefore($targetElement);

                } else {

                    return this.PlaceAfter($targetElement);

                }
            
            } else {
                
                if(mousePercents.y < 50) {

                    return this.PlaceBefore($targetElement);
                
                } else {

                    return this.PlaceAfter($targetElement);
                
                }
            
            }
        
        },

        checkVoidElement : function($element) {

            var voidelements = ['i','area','base','br','col','command','embed','hr','img','input','keygen','link','meta','param','video','iframe','source','track','wbr'];
            var selector = voidelements.join(",");

            if($element.is(selector)) return true;
            
            else return false;
        
        },

        calculateDistance : function(elementData, mouseX, mouseY) {

            return Math.sqrt(Math.pow(elementData.x - mouseX, 2) + Math.pow(elementData.y - mouseY, 2));
        
        },

        FindValidParent : function($element, direction) {

            switch(direction) {

                case "left":
                    
                    while(true) {

                        var elementRect = $element.get(0).getBoundingClientRect();
                        var $tempElement = $element.parent();
                        var tempelementRect = $tempElement.get(0).getBoundingClientRect();

                        if($element.is("body")) return $element;

                        if(Math.abs(tempelementRect.left - elementRect.left) === 0) $element = $element.parent();
                        
                        else return $element;

                    }
                    
                    break;
                
                case "right":
                    
                    while(true) {

                        //console.clear();
                        //console.log( this.dragged, $element[0], this.dnd.isAllowed(this.dragged, $element[0]) );

                        var elementRect = $element.get(0).getBoundingClientRect();
                        var $tempElement = $element.parent();
                        var tempelementRect = $tempElement.get(0).getBoundingClientRect();

                        if($element.is("body")) return $element;

                        if( Math.abs(tempelementRect.right - elementRect.right) === 0 ) {

                            $element = $element.parent();

                        } else {

                            return $element;

                        }

                    }
                    break;

                case "top":

                    while(true) {

                        var elementRect = $element.get(0).getBoundingClientRect();
                        var $tempElement = $element.parent();
                        var tempelementRect = $tempElement.get(0).getBoundingClientRect();

                        if($element.is("body")) return $element;

                        if ( !this.dnd.isAllowed(this.dragged, $element[0].parentNode) ) $element = $element.parent();

                        //if(Math.abs(tempelementRect.top - elementRect.top) === 0) $element = $element.parent();
                        
                        else return $element;
                    
                    }
                    
                    break;
                
                case "bottom":

                    while(true) {

                        var elementRect = $element.get(0).getBoundingClientRect();
                        var $tempElement = $element.parent();
                        var tempelementRect = $tempElement.get(0).getBoundingClientRect();

                        if($element.is("body")) return $element;

                        if ( !this.dnd.isAllowed(this.dragged, $element[0].parentNode) ) $element = $element.parent();

                        //if(Math.abs(tempelementRect.bottom - elementRect.bottom) === 0) $element = $element.parent();
                        
                        else return $element;
                    
                    }
                    
                    break;
            }

        },
        
        addPlaceHolder : function($element, position, placeholder) {

            if ( $element[0] === undefined ) return false;

            if(!placeholder) placeholder = this.getPlaceHolder();

            this.removePlaceholder();

            //console.log($element[0], position);

            /*console.log(placeholder, $element);*/

            switch(position) {

                case "before":
                    
                    placeholder.find(".message").html($element.parent().data('sh-dnd-error'));

                    if ( this.dnd.isAllowed(this.dragged, $element[0].parentNode) ) $element.before(placeholder);

                    //if ( $element.parent().attr('class').indexOf('col-') !== -1 || $element.parent().attr('class').indexOf('kartra_list') !== -1 ) $element.before(placeholder);
                    //* console.log($element);
                    //* console.log("BEFORE");
                    this.AddContainerContext($element,'sibling');
                    break;

                case "after":

                    placeholder.find(".message").html($element.parent().data('sh-dnd-error'));

                    if ( this.dnd.isAllowed(this.dragged, $element[0].parentNode) ) $element.after(placeholder);

                    //if ( $element.parent().attr('class').indexOf('col-') !== -1 || $element.parent().attr('class').indexOf('kartra_list') !== -1 ) $element.after(placeholder);
                    //* console.log($element);
                    //* console.log("AFTER");
                    this.AddContainerContext($element,'sibling');

                    break;

                case "inside-prepend":

                    if(this.isGridNested(this.dragged, this.dropZone[0])) {
                        //return;
                    }


                    placeholder.find(".message").html($element.data('sh-dnd-error'));
                    $element.prepend(placeholder);
                    this.AddContainerContext($element,'inside');
                    //* console.log($element);
                    //* console.log("PREPEND");
                    break;

                case "inside-append":

                    if(this.isGridNested(this.dragged, this.dropZone[0])) {
                        //return;
                    }


                    placeholder.find(".message").html($element.data('sh-dnd-error'));
                    $element.append(placeholder);
                    this.AddContainerContext($element,'inside');
                    //* console.log($element);
                    //* console.log("APPEND");
                    break;

            }

        },
        
        removePlaceholder : function() {

            $("iframe", document.getElementById('clientframe-container')).contents().find(".drop-marker").remove();
        
        },
        
        getPlaceHolder : function() {

            return $("<li class='drop-marker'></li>");
        
        },
        
        PlaceInside : function($element) {

            var placeholder = this.getPlaceHolder();
            placeholder.addClass('horizontal').css('width', $element.width()+"px");
            this.addPlaceHolder($element,"inside-append", placeholder);
        
        },
        
        PlaceBefore : function($element) {

            var placeholder = this.getPlaceHolder();
            var inlinePlaceholder = ($element.css('display') === "inline" || $element.css('display') === "inline-block");

            if($element.is("br")) {

                inlinePlaceholder = false;
            
            } else if($element.is("td,th")) {

                placeholder.addClass('horizontal').css('width', $element.width()+"px");
                return this.addPlaceHolder($element, "inside-prepend", placeholder);
            
            }
            
            if(inlinePlaceholder) placeholder.addClass("vertical").css('height', $element.innerHeight() + "px");
            
            else placeholder.addClass("horizontal").css('width', $element.parent().width() + "px");

            this.addPlaceHolder($element, "before", placeholder);
        
        },

        PlaceAfter : function($element) {

            var placeholder = this.getPlaceHolder();
            var inlinePlaceholder = ($element.css('display') === "inline" || $element.css('display') === "inline-block");

            if($element.is("br")) {

                inlinePlaceholder = false;
            
            } else if($element.is("td,th")) {

                placeholder.addClass('horizontal').css('width', $element.width() + "px");
                return this.addPlaceHolder($element, "inside-append", placeholder);
            
            }
            
            if(inlinePlaceholder) placeholder.addClass("vertical").css('height', $element.innerHeight() + "px");
            
            else placeholder.addClass("horizontal").css('width', $element.parent().width() + "px");

            this.addPlaceHolder($element, "after", placeholder);
        
        },
        
        findNearestElement : function($container, clientX, clientY) {

            var _this = this;
            var previousElData = null;
            var childElement = $container.children(":not(.drop-marker,[data-dragcontext-marker],.background-item,.kartra_element_bg)");

            //console.clear();

            if(childElement.length > 0) {

                childElement.each(function() {

                    if($(this).is(".drop-marker")) return;

                    var offset = $(this).get(0).getBoundingClientRect();
                    var distance = 0;
                    var distance1,distance2 = null;
                    var position = '';
                    var xPosition1 = offset.left;
                    var xPosition2 = offset.right;
                    var yPosition1 = offset.top;
                    var yPosition2 = offset.bottom;
                    var corner1 = null;
                    var corner2 = null;

                    //Parellel to Yaxis and intersecting with x axis
                    if(clientY > yPosition1 && clientY <  yPosition2 ) {

                        if(clientX < xPosition1 && clientY < xPosition2) {

                            //corner1 = {x:xPosition1, y:clientY,'position':'before'};
                            corner1 = {x:clientX, y:clientY,'position':'before'};
                        
                        } else {

                            //corner1 = {x:xPosition2, y:clientY,'position':'after'};
                            corner1 = {x:clientX, y:clientY,'position':'after'};
                        
                        }

                    } else if(clientX > xPosition1 && clientX < xPosition2) {//Parellel to xAxis and intersecting with Y axis

                        if(clientY < yPosition1 && clientY < yPosition2) {

                            corner1 = {x:clientX, y:yPosition1,'position':'before'};
                        
                        } else {

                            corner1 = {x:clientX, y:yPosition2,'position':'after'};
                        
                        }

                    } else {

                        //runs if no element found!
                        if(clientX < xPosition1 && clientX < xPosition2) {

                            corner1 = {x:xPosition1, y:yPosition1, 'position':'before'};          //left top
                            corner2 = {x:xPosition1, y :yPosition2, 'position':'after'};       //left bottom
                        
                        } else if(clientX > xPosition1 && clientX > xPosition2) {

                            //console.log('I m on the right of the element');
                            corner1 = {x:xPosition2, y:yPosition1, 'position':'before'};   //Right top
                            corner2 = {x:xPosition2, y :yPosition2, 'position':'after'}; //Right Bottom
                        
                        } else if(clientY < yPosition1 && clientY < yPosition2) {

                            // console.log('I m on the top of the element');
                            corner1 = {x :xPosition1, y:yPosition1, 'position':'before'}; //Top Left
                            corner2 = {x :xPosition2, y:yPosition1, 'position':'after'}; //Top Right
                        
                        } else if(clientY > yPosition1 && clientY > yPosition2) {

                            // console.log('I m on the bottom of the element');
                            corner1 = {x :xPosition1, y:yPosition2, 'position':'before'}; //Left bottom
                            corner2 = {x :xPosition2, y:yPosition2, 'position':'after'} //Right Bottom
                        
                        }
                    }

                    //console.log(this, corner1);

                    distance1 = _this.calculateDistance(corner1, clientX, clientY);

                    //console.log(this, distance1);

                    if(corner2 !== null) distance2 = _this.calculateDistance(corner2, clientX, clientY);

                    if(distance1 < distance2 || distance2 === null) {

                        distance = distance1;
                        position = corner1.position;
                    
                    } else {

                        distance = distance2;
                        position = corner2.position;
                    
                    }

                    if(previousElData !== null) {

                        if(previousElData.distance < distance) {

                            return true; //continue statement
                        
                        }
                    }

                    previousElData =  {'el':this, 'distance':distance, 'xPosition1':xPosition1, 'xPosition2':xPosition2, 'yPosition1':yPosition1, 'yPosition2':yPosition2, 'position':position}
                
                });
                
                if(previousElData !== null) {

                    var position = previousElData.position;
                    return {'el':$(previousElData.el), 'position':position};
                
                } else {

                    return false;
                
                }
            
            }
        
        },

        AddEntryToDragOverQueue : function($element, elementRect, mousePos, accepts, dragged, dnd, droppableString) {

            var newEvent = [$element, elementRect, mousePos];
            this.dragoverqueue.push(newEvent);
            this.accepts = accepts;
            this.dragged = dragged;
            this.dropZone = $element;
            this.dnd = dnd;
            this.droppableString = droppableString;

        },

        ProcessDragOverQueue : function($element, elementRect, mousePos) {

            var processing = this.dragoverqueue.pop();
            this.dragoverqueue = [];
            
            if(processing && processing.length === 3) {

                var $el = processing[0];
                var $elRect = processing[1];
                var mousePos = processing[2];
                this.OrchestrateDragDrop($el, $elRect, mousePos);
            
            }

        },

        GetContextMarker : function() {

            var $contextMarker = $("<div data-dragcontext-marker><span data-dragcontext-marker-text></span></div>");
            return $contextMarker;
        
        },

        AddContainerContext : function($element, position) {

            var theDoc = $element.get(0).ownerDocument;

            var $contextMarker = this.GetContextMarker();
            this.ClearContainerContext();

            if ( $element[0].hasAttribute('class') && ($element[0].classList.contains('container') || $element[0].classList.contains('container-fluid')) ) {
                $element[0].classList.add('container-hover');
            }

            if($element.is('html,body')) {

                position = 'inside';
                $element =  $(theDoc).contents().find("body");
            
            }
            
            switch(position) {

                case "inside":

                    this.PositionContextMarker($contextMarker,$element);

                    if($element.hasClass('stackhive-nodrop-zone')) $contextMarker.addClass('invalid');

                    if ( !this.dnd.isAllowed(this.dragged, $element[0]) ) return false;

                    var name = this.getElementName($element);
                    $contextMarker.find('[data-dragcontext-marker-text]').html(name);
                    
                    if($(theDoc).contents().find("body [data-sh-parent-marker]").length !== 0) $(theDoc).contents().find("body [data-sh-parent-marker]").first().before($contextMarker);
                    else $(theDoc).contents().find("body").append($contextMarker);

                    break;

                case "sibling":

                    this.PositionContextMarker($contextMarker,$element.parent());

                    if($element.parent().hasClass('stackhive-nodrop-zone')) $contextMarker.addClass('invalid');

                    if ( !this.dnd.isAllowed(this.dragged, $element.parent()[0]) ) return false;

                    var name = this.getElementName($element.parent());

                    $contextMarker.find('[data-dragcontext-marker-text]').html(name);
                    $contextMarker.attr("data-dragcontext-marker",name.toLowerCase());

                    if($(theDoc).contents().find("body [data-sh-parent-marker]").length !== 0) $(theDoc).contents().find("body [data-sh-parent-marker]").first().before($contextMarker);
                    else $(theDoc).contents().find("body").append($contextMarker);

                    break;
            }

        },

        PositionContextMarker : function($contextMarker, $element) {

            var theDoc = $element.get(0).ownerDocument;
            var theWindow = theDoc.defaultView;

            //console.log($(theWindow));
            //console.log($($("#clientframe").get(0).contentWindow));

            var rect = $element.get(0).getBoundingClientRect();

            $contextMarker.css({
                height: (rect.height + 4) + "px",
                width: (rect.width + 4) + "px",
                top: (rect.top+$(theWindow).scrollTop() - 2) + "px",
                left: (rect.left+$(theWindow).scrollLeft() - 2) + "px"
            });

            if(rect.top+$(theDoc).contents().find("body").scrollTop() < 24) $contextMarker.find("[data-dragcontext-marker-text]").css('top', '0px');

        },

        ClearContainerContext : function() {

            $("iframe", document.getElementById('clientframe-container')).contents().find('[data-dragcontext-marker]').remove();

            $("iframe", document.getElementById('clientframe-container')).contents().find('.container-hover').removeClass('container-hover');

        },

        getElementName : function($element) {

            //some conditionals here to determine whhat kind of element $element is
            if ( $element[0].hasAttribute('data-container') ) {
                return "Component container, " + $element[0].tagName;
            }

            if ( $element[0].tagName === 'UL' || $element[0].tagName === 'OL' ) {
                return "List";
            }

            return $element[0].tagName + "(" + $element[0].className + ")";

        },

        //check if dragged is a grid component nested more than two levels deep
        isGridNested: function(dragged, dropped) {
            var $dragged = $(dragged),
                $dropped = $(dropped);

            /*if (($dragged.is('[data-component="grid"') || $dragged.hasClass('drop-marker')) &&
                $dropped.closest('[data-component="grid"]').length &&
                $dropped.closest('[data-component="grid"]').parent().closest('[data-component="grid"]').length) {
                return true;
            }*/

            return false;
        }

    };

    module.exports = Dnd;

}());
/* jshint ignore:end */