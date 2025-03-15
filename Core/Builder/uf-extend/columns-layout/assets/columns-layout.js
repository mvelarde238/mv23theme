(function( $ ) {

	var columns_layout = window.ufColumns_Layout = {};

	/**
	 * Contains helpers for mouse position.
	 */
	columns_layout.mousePosition = {
	    /**
	     * Retrieves mouse mosition for a specific event.
	     */
	    getMousePosition: function( event ) {
			return {
				x: event.pageX,
				y: event.pageY
			};
	    },

		/**
		 * Disables the selection of the root element.
		 *
		 * Otherwise while dragging, text will be selected.
		 */
		disableSelection: function() {
	        this.$el
	        	.attr( 'unselectable', 'on' )
	        	.css( 'user-select', 'none' )
	        	.on( 'selectstart', false )
				.on( 'dragstart', false );
	    },

		/**
		* Disableds the selection of the whole body.
		*/
		disableBodySelection: function() {
			$( 'body' )
				.attr( 'unselectable', 'on' )
				.css( 'user-select', 'none' )
				.on( 'selectstart.uf-columns-layout', false );
		},

		/**
		* Enables the selection of the whole body.
		*/
		enableBodySelection: function() {
			$( 'body' )
				.attr( 'unselectable', false )
				.css( 'user-select', false )
				.off( 'selectstart.uf-columns-layout' );
		}
	}

	/**
	 * Create a basic events object, which can e added to the prototype of any other class.
	 */
	columns_layout.Eventful = {
		on: function( name, callback ) {
			this.events         = this.events || {};
			this.events[ name ] = this.events[ name ] || [];
			this.events[ name ].push( callback );
		},

		off: function( name ) {
			if( name in this.events ) {
				delete this.events[ name ];
			}
		},

		trigger: function( name ) {
			var args;

			if( ! this.events || ! this.events[ name ] )
				return;

			args = [];
			$.each( arguments, function( index, arg ) {
				args.push( arg );
			});

			args.shift(); // Remove the name

			$.each( this.events[ name ], function( i, callback ){
				callback.apply( null, args );
			});
		},

		bind: function( method, element ) {
			return function() {
				method.apply( element, arguments );
			};
		}
	};

	/**
	 * This is a class, which represents elements/blocks/group.
	 */
	columns_layout.Element = function( args ) {
		if( 'string' == typeof args.el ) {
			this.$el = $( '<div />' )
				.addClass( args.el )
				.data( 'width', args.width )
				.data( 'type', args.type.id );
		} else {
			this.$el = args.el;
		}

		// Save a reverse handle
		this.$el.data( 'element', this );

		// Save the basic properties
		this.type  = args.type;

		// Make sure external plugins know what happened
		this.$el.trigger( 'uf-columns-layout-added', this );

		// Add final elements if possible
		this.addFinalElements();
	}

	// Add methods to elements
	$.extend( columns_layout.Element.prototype, columns_layout.mousePosition, columns_layout.Eventful, {
		/**
		 * When the group is finally in place, add all elements.
		 */
		addFinalElements: function() {
			if( this.elementsAdded ) {
				return;
			}

			// Check if the group header is already in place
			if( ! this.$el.find( '.uf-group-header' ).length ) {
				return;
			}

			// Initialize the dragging script
			this.initializeDragging();

			this.elementsAdded = true;
		},

		/**
		 * Adjust the elements size
		 */
		adjustToColumns: function() {
			this.$el.css( { width: '100%'} );
		},

		/**
		 * Initializes the dragging of the element.
		 */
		initializeDragging: function() {
			var that = this;

			this.$el.on( 'mousedown', function( e ) {
				that.startDragging( e );
			});

			this.disableSelection();
		},

		/**
		 * Starts the process of resizing.
		 */
		startDragging: function( e ) {
			// Bubble the event to up layout
			this.trigger( 'draggingStarted', e );
			return;
		},

		/**
		 * Converts to JSON, allowing easier debugging.
		 */
		toJSON: function() {
			return {
				type:  this.type
			};
		},

		/**
		 * Propagates the size and location of the element.
		 */
		setAttributes: function( attributes ) {
			this.trigger( 'update-attributes', attributes );
		}
	});

	/**
	 * This is a class, which represents columns.
	 */
	columns_layout.Column = function( args ) {
		var that = this;

		this.$el = 'string' == typeof args.el
			? $( '<div />' ).addClass( args.el )
			: args.el;
		this.$groups = this.$el.find( '.uf-columns-layout-column-groups' );

		this.elements = [];
		this.columns = 99999; // big NUMBER, default was args.columns

		this.$el.on( 'click', '.uf-columns-layout-column-remove', function( e ) {
			e.preventDefault();
			that.handle_column_remove_on_click();
		});

		this.$el.on( 'click', '.uf-columns-layout-column-appearance', function( e ) {
			e.preventDefault();
			that.handle_column_appearance_on_click();
		});

		this.$el.on( 'click', '.uf-columns-layout-column-settings', function( e ) {
			e.preventDefault();
			that.handle_column_settings_on_click();
		});
	}

	// Add methods to columns
	$.extend( columns_layout.Column.prototype, columns_layout.Eventful, {
		/**
		 * Adds an element to the column collection
		 */
		addElement: function( element ) {
			var that = this;

			// Add locally, set simple properties
			this.elements.push( element );
			element.columns = this.columns;

			// When destroyed, remove
			element.on( 'destroy', function() {
				that.elements.splice( that.elements.indexOf( element ), 1 );

				// Remove the column or recalculate
				if( ! that.elements.length ) {
					// that.destroy();
				} else {
					that.isUsed();
				}

				that.trigger( 'elementRemoved' );
			});

			// Bubble up the event when moving
			element.on( 'draggingStarted', function( e ) {
				that.trigger( 'elementDraggingStarted', e, element );
			});
		},

		/**
		 * Detaches an element from the view.
		 */
		detachElement: function( element ) {
			this.elements.splice( this.elements.indexOf( element ), 1 );
			this.isUsed();
			element.off( 'destroy' );
			element.off( 'draggingStarted' );
		},

		/**
		 * Check if the column can accept a new element.
		 */
		accepts: function( args ) {
            var $element   = args.$helper,
				mouse      = args.mouse,
				thisLeft    = this.$el.offset().left,
				thisWidth = this.$el.width(),
				thisTop    = this.$el.offset().top,
    			thisHeight = this.$el.height(),
				fits;

			// Checks if the elements fits in a column
			fits = (
				thisLeft <= mouse.x && mouse.x <= (thisLeft + thisWidth) &&
				thisTop  <= mouse.y && mouse.y <= (thisTop + thisHeight)
			);

			if( fits ) {
				this.$el.addClass( 'uf-columns-layout-column-waiting uf-columns-layout-highlight' );
				return true;
			} else {
				return false;
			}
		},

		/**
		 * Clears the classes of the column and the placeholders inside.
		 */
		cleanup: function() {
			this.$el.removeClass( 'uf-columns-layout-column-waiting' );
			this.removePlaceholder();
			this.isUsed();

			// If the column is empty, just remove it (??????)
			if( ! this.used ) {
				// this.destroy();
			} else {
				// Sort the internal element collection
				this.elements.sort(function( a, b ) {
					return a.$el.index() > b.$el.index() ? 1 : -1;
				});
			}
		},

		/**
		 * Creates a temporary placeholder.
		 */
		addPlaceholder: function( args ) {
			var type  = args.type;

			this.placeholder = new columns_layout.Element({
				el:   'uf-columns-layout-element uf-columns-layout-element-placeholder',
				width: 1,
				type:  type
			});

			if( args.$helper.data( 'element' ) ) {
				this.placeholderElement = args.$helper.data( 'element' );
			} else {
				this.placeholderElement = false;
			}

			this.placeholder.$el.appendTo( this.$groups );
			this.placeholder.adjustToColumns();
			this.repositionPlaceholder( args );
		},

		/**
		 * Removes the palceholder, if any.
		 */
		removePlaceholder: function() {
			if( this.placeholder ) {
				this.placeholder.$el.remove();
				this.placeholder = false;
			}

			this.$el.removeClass( 'uf-columns-layout-column-waiting uf-columns-layout-highlight' );
		},

		/**
		 * Repositions the placeholder within the column.
		 */
		repositionPlaceholder: function( args ) {
			var that   = this,
				placed = false;

			this.elements.forEach(function( element ) {
				if( ! placed && args.mouse.y < element.$el.offset().top + element.$el.height() / 2 ) {
					element.$el.before( that.placeholder.$el );
					placed = true;
				}
			});

			if( ! placed ) {
				this.placeholder.$el.appendTo( this.$groups );
				placed = true;
			}
		},

		/**
		 * Populates the placeholder with a real element.
		 */
		populatePlaceholder: function( args ) {
			var element;

			// Create the element
			if( this.placeholderElement ) {
				element = this.placeholderElement;
			} else {
				element = new columns_layout.Element({
					el:     args.className,
					type:   this.placeholder.type
				});

				element.adjustToColumns();
				args.populateElement( element );
				element.addFinalElements();
			}

			// Replace and remove the placeholder
			this.placeholder.$el.replaceWith( element.$el );
			this.placeholder = false;

			// Adjust the actual column
			this.$el.removeClass( 'uf-columns-layout-empty-column' );

			// Add to the collection
			this.addElement( element );
		},

		/**
		 * Check if the column is used
		 */
		isUsed: function() {
			var that = this;

			this.used = 0;

			this.elements.forEach( function( element ) {
				that.used += 1;
			});

			return this.used;
		},

		/**
		 * Destroys the column.
		 */
		destroy: function() {
			this.$el.remove();
			this.trigger( 'destroy' );
		},

		/**
		 * Handle column remove
		 */
		handle_column_remove_on_click: function(){
			var that = this;

			if ( !confirm("Are you sure you want to delete this column? This CANNOT be undone.") ) return;

			// without this exportToDatastore() method save elements in another column
			that.$el.find( '.uf-columns-layout-element' ).each(function() {
				var $element = $( this );

				$element.data( 'element' ).setAttributes({
					index: -1,
					// preserve 'row' name because we are extending layout groups
					row:   -1
				});
			});

			// detach elements
			for (let i = 0; i < that.elements.length; i++) {
				const element = that.elements[i];

				that.elements.splice( that.elements.indexOf( element ), 1 );
				that.isUsed();
				element.off( 'destroy' );
				element.off( 'draggingStarted' );
			}
			
			that.$el.fadeOut( 200, function() {
				that.$el.remove();
				that.trigger( 'destroy' );
			});
		},

		/**
		 * Handle column appearance
		 */
		handle_column_appearance_on_click: function(){
			var that = this, overlayLayer, column_settings, common_settings_popup_view;

			column_settings = that.$el.data('column_settings') ?? {};

			PopUpModel = Backbone.Model.extend({ 
				defaults: { 
					container: 'common_settings_container',
					storedValues: column_settings,
					save_text: 'Save Settings'
				}, 
			});

			// Create a common settings popup
			common_settings_popup_view = new window.UltimateFields.Field.Common_Settings_Control.PopupView({
				model: new PopUpModel()
			});

			// Show the overlay
			overlayLayer = UltimateFields.Overlay.show({
				view: common_settings_popup_view,
				title: 'Column Settings',
				buttons: common_settings_popup_view.getButtons()
			});

			// Listen for saving
			common_settings_popup_view.on( 'save', function(e, column_settings) {
				that.$el.data('column_settings', column_settings );
				overlayLayer.removeScreen();
				that.trigger('addColumnSettings');
			});
		},

		/**
		 * Handle column settings
		 */
		handle_column_settings_on_click: function(){
			var that = this, overlayLayer, column_settings, common_settings_popup_view;

			column_settings = that.$el.data('column_settings') ?? {};

			PopUpModel = Backbone.Model.extend({ 
				defaults: { 
					container: 'column_settings_container',
					storedValues: column_settings,
					save_text: 'Save Settings'
				}, 
			});

			// Create a common settings popup
			common_settings_popup_view = new window.UltimateFields.Field.Common_Settings_Control.PopupView({
				model: new PopUpModel()
			});

			// Show the overlay
			overlayLayer = UltimateFields.Overlay.show({
				view: common_settings_popup_view,
				title: 'Column Settings',
				buttons: common_settings_popup_view.getButtons()
			});

			// Listen for saving
			common_settings_popup_view.on( 'save', function(e, column_settings) {
				// filter raw setting data
				Object.entries(column_settings).forEach(entry => {
					// check first tab
					if( entry[0] == '__tab' ) column_settings.__tab = 'Desktop';
				});

				that.$el.data('column_settings', column_settings );
				overlayLayer.removeScreen();
				that.trigger('addColumnSettings');
			});
		},

		/**
		 * Converts the column to JSON, allowing easier debugging.
		 */
		toJSON: function() {
			return {
				used:     this.used,
				columns:  this.columns,
				elements: this.elements
			};
		}
	});

	/**
	 * The main class.
	 */
	columns_layout.Core = function( $el, args ) {
		this.$el = $( $el );

		this.args = $.extend( {
			columns: 99999, // big number, default was 12, this is the original number of groups
			types:   {},
			distance: 3, // Amount of pixels before dragging starts

			// CSS classes for the various elements
			body:      'uf-columns-layout-content',
			column:       'uf-columns-layout-column',
			element:   'uf-columns-layout-element',
			prototype: 'uf-columns-layout-element-prototype'
		}, args );

		this.initialize();
	}

	$.extend( columns_layout.Core.prototype, columns_layout.mousePosition, {
		/**
		 * Initializes the columns_layout.
		 */
		initialize: function() {
			var that = this;

			this.$body = this.find( 'body' );

			this.down = false;           // For mousedown
			this.start = { x: 0, y: 0 }; // Starting mouse position, will be changed
			this.now   = { x: 0, y: 0 }; // Current mouse position, will be calculated for each pixel.
			this.where = { x: 0, y: 0 }; // The point wher ethe mouse start dragging the current helper

			this.__columns = []; // Holds all added columns

			this.addStyles();
			this.disableSelection();

			this.find( 'prototype' ).each(function() {
				var $element = $( this );
				that.initializePrototype( $element );
			});

			this.$body.sortable({
				items: '> .uf-columns-layout-column',
				handle: '.uf-columns-layout-handle',
				axis: 'x',
				stop: _.bind( this.save, this )
			});

			this.$el.on( 'click', '.uf-columns-layout-add-column', function( e ) {
				e.preventDefault();

				if( that.__columns.length == 6 ) return;

				that.addEmptyColumn();

				that.save();
			});

			this.$el.on( 'click', '.uf-columns-layout-row-settings', function( e ) {
				e.preventDefault();
				that.handle_row_settings_on_click();
			});

			// Once everything is in place, let external elements know what's where
			this.save();
		},

		/**
		 * Finds all elements within the root element.
		 */
		find: function( selector, $container ) {
			if( selector in this.args ) {
				selector = '.' + this.args[ selector ];
			}

			return ( $container || this.$el ).find( selector );
		},

		/**
		 * Returns the settings for a data type.
		 */
		getTypeSettings: function( id ) {
			var type;

			this.args.types.forEach(function( available ) {
				if( id == available.id ) {
					type = $.extend( {}, available );
					return false;
				}
			});

			if( ! type.min ) {
				type.min = 1;
			}

			if( ! type.max ) {
				type.max = this.args.columns;
			}

			return type;
		},

		/**
		 * Sets a column up.
		 */
		setupColumn: function( data ) {
			var that = this, column;

			// Prepare the arguments for the column
			data = data || {};
			data.columns = this.args.columns;

			// This object will represent the column
			column = new columns_layout.Column( data );
			that.__columns.push( column );

			// Add listeners
			column.on( 'destroy', function() {
				that.__columns.splice( that.__columns.indexOf( column ), 1 );
				that.save();
			});

			column.on( 'elementDraggingStarted', function( e, element ) {
				that.elementDown( e, element, column );
			});

			column.on( 'addColumnSettings', function(){
				that.save();
			});

			column.on( 'elementRemoved', function() {
				that.save();
			});

			this.$el.trigger( 'uf-setup-column', column );

			return column;
		},

		/**
		 * Adds the initial styles to elements.
		 */
		addStyles: function() {
			var that = this;

			// Go through each column and work with the elements inside
			this.find( 'column', this.$body ).each(function() {
				var $column        = $( this ),
					column;

				// This object will represent the column
				column = that.setupColumn({
					el: $column
				});

				that.find( 'element', $column ).each(function() {
					var $element = $( this ), element;

					// Create an object
					element = new columns_layout.Element({
						el:   $element,
						type: that.getTypeSettings( $element.data( 'type' ) )
					});

					// Adjust the widths
					element.adjustToColumns();

					// Add to the column
					column.addElement( element );
				});
			});
		},

		/**
		 * Adds an empty column at the end of the body if there isnt one.
		 */
		addEmptyColumn: function() {
			var column;

			// If there is an existing column, don't add a new one
			if( this.find( 'emptyColumn' ).length ) {
				return;
			}

			// Create the column
			this.emptyColumn = column = this.setupColumn({
				el: this.args.column
			});

			// Add at the end of the body
			this.$body.append( column.$el );

			// Allow plugins to setup the appearance of the column
			this.args.placeholderColumn( column.$el, column );
		},

		/**
		 * Initializes a prototype.
		 */
		initializePrototype: function( $prototype ) {
			var that = this;

			$prototype.on( 'mousedown', function( e ) {
				that.prototypeDown( e, $prototype );
			});

			// $prototype.on( 'click', function( e ) {
			// 	that.prototypeClicked( e, $prototype );
			// });
		},

		/**
		 * Start the process of dragging an element.
		 */
		elementDown: function( e, element, column ) {
			var that = this, pos = {};

			// Save properties to the HTML element
			element.$el.data({
				element: element,
				width:   element.width
			});

			// Indicate the current interaction for other methods
			this.sourceColumn = column;
			this.sourceElement = element;

			// Mark down the start
			this.start = this.getMousePosition( e );
			this.where = {
				x: this.start.x - ( element.$el.offset().left - this.$el.offset().left ),
				y: this.start.y - ( element.$el.offset().top  - this.$el.offset().top )
			}

			// Let the rest work
			that.somethingDown( element.$el );
		},

		/**
		 * Starts the process of dragging a prototype.
		 */
		prototypeDown: function( e, $prototype ) {
			// Indicate that there is no existing element
			this.sourceColumn = false;

			// Mark down the start
			this.start = this.getMousePosition( e );
			this.where = {
				x: this.start.x - ( $prototype.offset().left - this.$el.offset().left ),
				y: this.start.y - ( $prototype.offset().top  - this.$el.offset().top )
			}

			this.somethingDown( $prototype );
		},

		/**
		 * When a prototype is clicked, it should be added on a new column.
		 */
		prototypeClicked: function( e, $prototype ) {
			// var that = this, column, element, type;

			// e.preventDefault();

			// if( that.__columns.length == 13 ) return;

			// // Initialize the column
			// column = this.emptyColumn;

			// // Create the element
			// type = that.getTypeSettings( $prototype.data( 'type' ) );
			// element = new columns_layout.Element({
			// 	el:     'uf-columns-layout-element',
			// 	width:  Math.min( type.max, this.args.columns ),
			// 	type:   type
			// });

			// // Add the element to the column and adjust things
			// element.$el.appendTo( column.$groups );
			// element.adjustToColumns();
			// this.args.populateElement( element );
			// element.addFinalElements();

			// // Clean up the column
			// column.$el.removeClass( 'uf-columns-layout-empty-column' );

			// // Add to the collection
			// column.addElement( element );

			// // Finally, add another empty column
			// this.addEmptyColumn();

			// // Save
			// this.save();
		},

		/**
		 * Handles the mousedown on any element.
		 */
		somethingDown: function( $item ) {
			var that      = this,
				$document = $( document );

			// Start listening for mouse movement
			$document.on( 'mousemove.uf-columns-layout', function( e ) {
				that.mouseMoved( e, $item );
			});

			// Create a callback for when moving stops
			$document.on( 'mouseup.uf-columns-layout', function( e ) {
				$document.off( 'mousemove.uf-columns-layout' );
				$document.off( 'mouseup.uf-columns-layout' );

				this.mouseUp( e );
			}.bind( this ) );
		},

		/**
		 * Handles each move of the mouse.
		 */
		mouseMoved: function( e, $item ) {
			var that = this, accepts;

			// Save the position
			this.now = this.getMousePosition( e );

			// Check if the dragging has already started
			if( ! this.down ) {
				var x = Math.abs( this.start.x - this.now.x ),
					y = Math.abs( this.start.y - this.now.y );

				if( x < this.args.distance && y < this.args.distance ) {
					return;
				}

				this.down = true;
				this.startDragging( $item );
			}

			// Start by repositioning the helper in order to follow mouse movement
			this.$helper.css({
				top:  this.now.y - this.where.y,
				left: this.now.x - this.where.x
			});

			// Check columns, from top? to the bottom? or left to right
			accepts = {
				$helper: that.$helper,
				type:    that.getTypeSettings( that.$helper.data( 'type' ) ),
				mouse:   that.now
			};

			this.__columns.forEach(function( column ) {
				if( ! column.accepts( accepts ) ) {
					if( ! that.sourceColumn ) {
						column.removePlaceholder();

						if( column == that.currentColumn ) {
							that.currentColumn = false;
						}
					}

					return;
				}

				if( column != that.currentColumn ) {
					// Remove the placeholder if the new column can handle the element
					if( that.currentColumn ) {
						that.currentColumn.removePlaceholder();
					}

					// Save the column and add the palceholder
					that.currentColumn = column;
					column.addPlaceholder( accepts );
				} else {
					column.repositionPlaceholder( accepts );
				}

				return false;
			});
		},

		/**
		 * Creates the current helper based on the moved prototype
		 */
		startDragging: function( $item ) {
			var pos;

			if( this.sourceColumn ) {
				this.$helper = $item;

				// Start by detaching the element from the column
				this.sourceColumn.detachElement( $item.data( 'element' ) );

				// Mark down the actual position of the element
				pos = {
					x: $item.offset().left - this.$el.offset().left,
					y: $item.offset().top  - this.$el.offset().top
				}

				// Move the element to the body
				$item
					.css({
						top: pos.y,
						left: pos.x,
						width: $item.width()
					})
					.addClass( 'uf-columns-layout-helper' )
					.appendTo( this.$el );
			} else {
				this.$helper = $item.clone();
				this.$helper.addClass( 'uf-columns-layout-helper' ).appendTo( this.$el );
				this.$helper.data( 'original', $item );
			}
		},

		/**
		 * Dragging has started.
		 */
		mouseUp: function( e ) {
			var that = this, $helper, $original;

			if( ! this.down ) {
				return;
			}

			if( this.currentColumn ) {
				// Adding an element
				this.currentColumn.populatePlaceholder({
					className:      this.args.element,
					populateElement: this.args.populateElement
				});

				if( this.sourceColumn ) {
					this.$helper.removeClass( 'uf-columns-layout-helper' ).css({
						top: 0,
						left: 0
					});

					this.$helper.data( 'element' ).adjustToColumns();

					// Recalculate
					this.currentColumn.isUsed();
					this.currentColumn.cleanup();
				} else {
					this.$helper.remove();
				}
			} else {
				if( this.sourceColumn ) {
					this.sourceColumn.$el.find( '.uf-columns-layout-column-groups' ).append( this.sourceElement.$el.find( '.uf-columns-layout-column-groups' ) );
					this.sourceColumn.addElement( this.sourceElement );
				} else {
					// Animate the helper back and destroy it
					$helper   = this.$helper;
					$original = $helper.data( 'original' );
					this.$helper.animate({
						top:  $original.offset().top  - this.$el.offset().top,
						left: $original.offset().left - this.$el.offset().left
					}, function() {
						$helper.remove();
					});
				}
			}

			// Reset
			this.$helper    = null;
			this.down       = false;
			that.currentColumn = false;
			that.sourceColumn  = false;

			this.__columns.forEach(function( column ) {
				column.cleanup();
			});

			// Save the results
			this.save();
		},

		/**
		 * Handle row settings
		 */
		handle_row_settings_on_click: function(){
			var that = this, overlayLayer, row_settings, common_settings_popup_view;

			row_settings = that.$el.data('row_settings') ?? {};

			PopUpModel = Backbone.Model.extend({ 
				defaults: { 
					container: 'row_settings_container',
					storedValues: row_settings,
					save_text: 'Save Settings'
				}, 
			});

			// Create a common settings popup
			common_settings_popup_view = new window.UltimateFields.Field.Common_Settings_Control.PopupView({
				model: new PopUpModel()
			});

			// Show the overlay
			overlayLayer = UltimateFields.Overlay.show({
				view: common_settings_popup_view,
				title: 'Row Settings',
				buttons: common_settings_popup_view.getButtons(),
				onOpen: function( overlayView ){
					var columns_lenght;

					columns_lenght = that.__columns.length;

					for (let i = 1; i <= 6; i++) {
						if( i != columns_lenght ){
							overlayView.$el.find('.uf-field-name-l_grid_'+i).hide();
							overlayView.$el.find('.uf-field-name-t_grid_'+i).hide();
							overlayView.$el.find('.uf-field-name-m_grid_'+i).hide();
						}
					}
				}
			});

			// Listen for saving
			common_settings_popup_view.on( 'save', function(e, row_settings) {
				var columns_lenght = that.__columns.length;

				// filter raw setting data
				Object.entries(row_settings).forEach(entry => {
					// check first tab
					if( entry[0] == '__tab' ) row_settings.__tab = 'Desktop';

					// remove no needed settings
					for (let i = 1; i <= 6; i++) {
						if( i != columns_lenght ){
							['l_grid_'+i, 't_grid_'+i, 'm_grid_'+i].forEach(no_needed_setting => {
								delete row_settings[ no_needed_setting ];
							});
						}
					}					
				});

				that.$el.data('row_settings', row_settings );
				overlayLayer.removeScreen();
				that.save();
			});
		},

		/**
		 * Saves the field settings.
		 */
		save: function() {
			var that = this,
				column  = 0,
				columns_settings = [],
				row_settings;

			// Do a simple crawl and trigger jQuery events
			this.find( 'body' ).find( '.' + this.args.column ).each(function() {
				var $column = $( this ), index = 0, elements, save_column_settings;

				elements = $column.find( '.' + that.args.element );
				save_column_settings = ( elements.length ) ? true : false;

				$column.find( '.' + that.args.element ).each(function() {
					var $element = $( this );
					
					$element.data( 'element' ).setAttributes({
						index: index++,
						// preserve 'row' name because we are extending layout groups
						row: column
					});
				});

				// colect column settings
				if(save_column_settings) columns_settings.push( $column.data('column_settings') ?? {} );

				column++;
			});

			// save row settings
			row_settings = this.$el.data('row_settings') ?? {};
			// console.log('columns_settings___', columns_settings);

			this.$el.trigger( 'columns-layout-updated', [columns_settings, row_settings] );
		}
	});

	$.fn.columns_layout = function( args ) {
		return this.each(function() {
			new columns_layout.Core( this, args );
		});
	}

})( jQuery );
