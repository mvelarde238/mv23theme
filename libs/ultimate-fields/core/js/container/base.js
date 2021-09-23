(function( $ ){

	/**
	 * This file handles
	 */
	var container = UltimateFields.Container  = {},
		base      = container.Base = {};

	base.Model = Backbone.Model.extend({
		defaults: {
			description:        '',
			layout:             'auto',
			description:        '',
			visible:            true,
			validation_enabled: false
		},

		initialize: function( args ) {
			var that = this;

			args = args || {};

			this.set( 'tabs', {} );
			this.set( 'xabs', {} );
			this.set( 'fields', new UltimateFields.Field.Collection( args.fields || this.get( 'fields' ) || [] ) );

			// Proxy some settings to the fields
			this.get( 'fields' ).each( function( field ) {
				field.set( 'description_position', that.get( 'description_position' ) );
			});
		},

		/**
		 * Sets a new datastore to the container and fields inside.
		 */
		setDatastore: function( datastore ) {
			var that = this,
				xabs = new UltimateFields.Field.Collection,
				xab,
				tabs = new UltimateFields.Field.Collection,
				tab;

			this.datastore = datastore;

			// Set the first tab if any
			this.get( 'fields' ).each(function( field ) {
				if( ! ( field instanceof UltimateFields.Field.Tab.Model ) )
					return;

				if( ! ( field instanceof UltimateFields.Field.Xab.Model ) )
					return;

				if( window.location.hash.replace( '#', '' ) == field.get( 'name' ) ) {
					that.datastore.set( '__tab', field.get( 'name' ) );
				}

				if( window.location.hash.replace( '#', '' ) == field.get( 'name' ) ) {
					that.datastore.set( '__xab', field.get( 'name' ) );
				}
			});

			if( ! datastore.get( '__tab' ) && ( tab = this.get( 'fields' ).findWhere({ type: 'Tab' }) ) ) {
				datastore.set( '__tab', tab.get( 'name' ) );
			}

			if( ! datastore.get( '__xab' ) && ( xab = this.get( 'fields' ).findWhere({ type: 'Xab' }) ) ) {
				datastore.set( '__xab', xab.get( 'name' ) );
			}

			// Send the datastore to the fields
			this.get( 'fields' ).each(function( field ) {
				field.setDatastore( datastore );

				if( 'Tab'== field.get( 'type' ) ) {
					that.get( 'tabs' )[ field.get( 'name' ) ] = field.get( 'visible' );

					// Save the field within the tabs
					tabs.add( field );
				}

				if( 'Xab'== field.get( 'type' ) ) {
					that.get( 'xabs' )[ field.get( 'name' ) ] = field.get( 'visible' );

					// Save the field within the tabs
					xabs.add( field );
				}
			});

			// When a tab gets invalidated, we should switch away from it (if possible)
			tabs.on( 'change:visible', function( tab ) {
				var currentTab = datastore.get( '__tab' );

				if( tab.get( 'name' ) == currentTab ) {
					// ToDo: Don't simply switch to the first tab, but the first avaiable
					datastore.set( '__tab', tabs.at( 0 ).get( 'name' ) );
				}
			});

			xabs.on( 'change:visible', function( xab ) {
				var currentInnerTab = datastore.get( '__xab' );

				if( xab.get( 'name' ) == currentInnerTab ) {
					// ToDo: Don't simply switch to the first tab, but the first avaiable
					datastore.set( '__xab', xabs.at( 0 ).get( 'name' ) );
				}
			});

			// Wheneve the datastore changes, validate
			datastore.on( 'change', function() {
				if( that.get( 'validation_enabled' ) ) {
					that.validate();
				}
			});
		},

		/**
		 * Forces default datastore values to be used (mainly within repeaters).
		 */
		forceDefaultValues: function() {
			this.get( 'fields' ).each( function( field ){
				field.useDefaultValueIfNeeded( true );
			});
		},

		/**
		 * Prevents automatic syncronisations of containers with the backend.
		 */
		sync: function() {
			return false; // No automatic syncing
		},

		/**
		 * Goes through each field and collects validation errors.
		 *
		 * Returns an array with string errors if found, underfined otherwise.
		 */
		validate: function() {
			var errors      = [],
				xabs        = this.get( 'xabs' ),
				invalidXabs = [];
				tabs        = this.get( 'tabs' ),
				invalidTabs = [];

			// Indivate that variation should be performed on every change
			this.set( 'validation_enabled', true );

			if( ! this.get( 'visible' ) ) {
				return errors;
			}

			this.get( 'fields' ).each( function( field ) {
				var state;

				// If the fields' tab is invisible, the field is invisible too
				if( field.get( 'tab' ) && ! tabs[ field.get( 'tab' ) ] ) {
					return;
				}

				if( field.get( 'xab' ) && ! xabs[ field.get( 'xab' ) ] ) {
					return;
				}

				state = field.validate();

				if( 'undefined' != typeof state ) {
					var tab;
					var xab;

					errors.push( state );

					if( ( tab = field.get( 'tab' ) ) && ! ( tab in invalidTabs ) ) {
						invalidTabs[ tab ] = 1;
					}
					if( ( xab = field.get( 'xab' ) ) && ! ( xab in invalidXabs ) ) {
						invalidXabs[ xab ] = 1;
					}
				}
			});

			// Save the errors if any
			this.set( 'validationErrors', errors );

			// Save the tabs as (in)valid
			_.each( this.get( 'fields' ).where({ type: 'Tab' }), function( tab ) {
				tab.set( 'invalidTab', tab.get( 'name' ) in invalidTabs );
			});

			_.each( this.get( 'fields' ).where({ type: 'Xab' }), function( xab ) {
				xab.set( 'invalidInnerTab', xab.get( 'name' ) in invalidXabs );
			});

			if( errors.length ) {
				return errors;
			} else {
				// Restore the silent state
				this.set( 'validation_enabled', false );
			}
		},

		/**
		 * Resets the container.
		 */
		reset: function() {
			this.setDatastore( new UltimateFields.Datastore( this.get( 'originalData' ) ) );
		},

		/**
		 * Retrieve a value from within the container.
		 */
		getValue: function( key ) {
			return this.datastore.get( key );
		}
	});

	/**
	 * This is the basic view for containers.
	 *
	 * Each location should extend this view and add its own methods and handlers.
	 */
	base.View = Backbone.View.extend({
		/**
		 * Does the basic rendering of a container.
		 */
		render: function() {
			var that    = this,
				$fields = $( '<div class="uf-fields" />' );

			this.$el.append( $fields );

 			// Add normal fields and initialize the hidden field
 			this.addFields( $fields );
		},

		/**
		 * Starts the process of saving all data into a hidden input field.
		 */
		initializeHiddenField: function( $input ) {
			var that = this;

			// Save a handle to the input
			this.$input = this.$el.find( '.uf-container-data' );

			// Do an initial population
			this.populateInput();

			// When the datastore changes, save values too
 			this.model.datastore.on( 'all', function() {
 				that.populateInput();
 			});
		},

		/**
		 * Populates the hidden input with th econtainers' data.
		 */
 		populateInput: function() {
 			this.$input.val( JSON.stringify( this.model.datastore ) );
 		},

		/**
		 * Adds fields to container while formatting their div properly.
		 */
		addFields: function( $container, options ) {
			var that = this,
				xabsAdded = false,
				$xabs,
				tabsAdded = false,
				$tabs,
				grid;

			// Locate the HTML Element
			if( ! $container ) {
				$container = this.$el.find( '.uf-fields' );
			} else if( 'string' == typeof $container ) {
				$container = this.$el.find( $container );
			}

			// Save a handle to the div
			this.$fields = $container;
			this.fieldViews = [];

			// Ensure a correct layout
			var layout = 'grid' === this.model.get( 'layout' )
				? 'grid'
				: 'rows';

			// Load the options and merge them with the defaults
			options = _.extend( {
				// An indicator whether tabs should be added to the fields div
				tabs: true,
				xabs: true,

				// Use the correct input wrapper for the field
				wrap: UltimateFields.Field[
					'grid' == this.model.get( 'layout' )
						? 'GridWrap'
						: 'Wrap'
				],

				// Prepare the correct layout
			}, options || {});

			// Add each individual field
			this.model.get( 'fields' ).each(function( model ) {
				var wrap = options.wrap, view;

				// If the model is a tab, add the tabs wrapper
				if( model instanceof UltimateFields.Field.Tab.Model ) {
					if( ! options.tabs ) {
						return;
					}

					if( ! tabsAdded ) {
						tabsAdded = $tabs = that.createTabsElement( $container );
					}

					return that.addInlineTab( model, $container );
				}

				if( model instanceof UltimateFields.Field.Xab.Model ) {
					if( ! options.xabs ) {
						return;
					}

					if( ! xabsAdded ) {
						xabsAdded = $xabs = that.createXabsElement( $container );
					}

					return that.addInlineInnerTab( model, $container );
				}

				// Sections have a special wrap
				if( model instanceof UltimateFields.Field.Section.Model ) {
					wrap = UltimateFields.Field.Section.Wrap;
				}

				// Generate, append and render the view
				that.fieldViews.push( view = new wrap({
					model: model
				}));

				view.$el.appendTo( $container );
				view.render();
			});

			this.fieldsLayout = new UltimateFields.ContainerLayout({
				el:        $container,
				container: this,
				layout:    layout,
				fields:    that.fieldViews,
				xabs:      $xabs || this.$popupXabs || false
			})
		},

		/**
		 * Creates an element for tabs.
		 */
		createTabsElement: function( $container ) {
			this.$tabs = $( '<div class="uf-tab-wrapper" />' )
				.append( this.getTabs() )
				.appendTo( $container );

			return this.$tabs;
		},

		createXabsElement: function( $container ) {
			this.$xabs = $( '<div class="uf-xab-wrapper" />' )
				.append( this.getXabs() )
				.appendTo( $container );

			return this.$xabs;
		},

		/**
		 * Generates the views for the tabs, but not their wrapper.
		 */
		getTabs: function() {
			var that  = this,
				tabs  = [],
				views = [];

			this.model.get( 'fields' ).each(function( field ){
				var tab;

				if( ! ( field instanceof UltimateFields.Field.Tab.Model ) )
					return;

				tab = new UltimateFields.Tab({
					model: field
				});

				that.model.get( 'tabs' )[ field.get( 'name' ) ] = field.get( 'visible' );
				field.on( 'change:visible', function() {
					that.model.get( 'tabs' )[ field.get( 'name' ) ] = field.get( 'visible' );
				});

				tabs.push( tab.$el );
				views.push( tab );

				if( ! that.tabHasVisibleFields( field ) ) {
					tab.$el.addClass( 'uf-tab-hidden' )
				}
			});

			// Check tabs when valus change
			this.model.datastore.on( 'change', function() {
				_.each( views, function( tab ) {
					var has = that.tabHasVisibleFields( tab.model );

					tab.$el[ has ? 'removeClass' : 'addClass' ]( 'uf-tab-hidden' );
				});
			});

			return tabs;
		},

		getXabs: function() {
			var that  = this,
				xabs  = [],
				views = [];

			this.model.get( 'fields' ).each(function( field ){
				var xab;

				if( ! ( field instanceof UltimateFields.Field.Xab.Model ) )
					return;

				xab = new UltimateFields.Xab({
					model: field
				});

				that.model.get( 'xabs' )[ field.get( 'name' ) ] = field.get( 'visible' );
				field.on( 'change:visible', function() {
					that.model.get( 'xabs' )[ field.get( 'name' ) ] = field.get( 'visible' );
				});

				xabs.push( xab.$el );
				views.push( xab );

				if( ! that.tabHasVisibleFields( field ) ) {
					xab.$el.addClass( 'uf-xab-hidden' )
				}
			});

			// Check tabs when valus change
			this.model.datastore.on( 'change', function() {
				_.each( views, function( xab ) {
					var has = that.xabHasVisibleFields( xab.model );

					xab.$el[ has ? 'removeClass' : 'addClass' ]( 'uf-xab-hidden' );
				});
			});

			return xabs;
		},

		/**
		 * Adds an inline tab, which will be visible in responsive mode.
		 */
		addInlineTab: function( model, $container ) {
			var that = this,
				tmpl = UltimateFields.template( 'inline-tab' ),
				$tab = $( tmpl( model.toJSON() ) );

			$container.append( $tab );
			$tab.on( 'click', '.uf-button', function() {
				model.datastore.set( '__tab', model.get( 'name' ) );
			});

			model.datastore.on( 'change:__tab', function() {
				model.datastore.get( '__tab' ) == model.get( 'name' )
					? $tab.find( 'button' ).attr( 'disabled', 'disabled' )
					: $tab.find( 'button' ).attr( 'disabled', false )
			});

			model.on( 'change:visible', function() {
				$tab[
					model.get( 'visible' )
						? 'removeClass'
						: 'addClass'
				]( 'uf-inline-tab-disabled' );
			});
		},

		addInlineInnerTab: function( model, $container ) {
			var that = this,
				tmpl = UltimateFields.template( 'inline-xab' ),
				$xab = $( tmpl( model.toJSON() ) );

			$container.append( $xab );
			$xab.on( 'click', '.uf-button', function() {
				model.datastore.set( '__xab', model.get( 'name' ) );
			});

			model.datastore.on( 'change:__xab', function() {
				model.datastore.get( '__xab' ) == model.get( 'name' )
					? $xab.find( 'button' ).attr( 'disabled', 'disabled' )
					: $xab.find( 'button' ).attr( 'disabled', false )
			});

			model.on( 'change:visible', function() {
				$xab[
					model.get( 'visible' )
						? 'removeClass'
						: 'addClass'
				]( 'uf-inline-xab-disabled' );
			});
		},

		/**
		 * Checks if any of the fields, which belong in a tab are visible.
		 */
		tabHasVisibleFields: function( tab ) {
			var that = this,
				has  = false;

			_.each( this.model.get( 'fields' ).where({ tab: tab.get( 'name' ) }), function( field ) {
				if( field.get( 'visible' ) ) {
					has = true;
				}
			});

			return has;
		},

		xabHasVisibleFields: function( xab ) {
			var that = this,
				has  = false;

			_.each( this.model.get( 'fields' ).where({ xab: xab.get( 'name' ) }), function( field ) {
				if( field.get( 'visible' ) ) {
					has = true;
				}
			});

			return has;
		},

		/**
		 * Initializes all locations when needed.
		 */
		initializeLocations: function( locationClass ) {
			var that        = this,
			 	initialized = false,
				go;

			go = function() {
				if( ! initialized ) {
					that._initializeLocations( locationClass );
					initialized = true;
				}
			}

			this.on( 'addedToDOM', go );
			$( document ).on( 'uf-initialize-loaded', go );
			$( document ).on( 'uf-init', go );
		},

		/**
		 * Initializes the location when the right trigger has happened.
		 */
		_initializeLocations: function( locationClass ) {
			var that = this, locations = new UltimateFields.Locations;

			// Convert to the right class
		   _.each( this.model.get( 'locations' ), function( location ) {
			   locations.add( new locationClass( location ) );
		   });

		   // Save a handle
		   this.model.set( 'locations', locations );

		   // Listen for changes
		   locations.on( 'change:visible', function() {
			   that[ locations.isVisible() ? 'show' : 'hide' ]();
			   that.model.set( 'visible', locations.isVisible() );
			   UltimateFields.ContainerLayout.DOMUpdated();
		   });

		   that[ locations.isVisible() ? 'show' : 'hide' ]();
		   this.model.set( 'visible', locations.isVisible() );
		},

		/**
		 * Shows the container based on location rules.
		 */
 		show: function() {
 			this.$el.show();
 		},

 		/**
 		 * Hides the container based on location rules.
 		 */
 		hide: function() {
 			this.$el.hide();
 		},

		/**
		 * Makes the container seamless by removing WordPress elements.
		 *
		 * Applies to options, post types, users, comments and more.
		 */
		seamless: function() {
			var that = this, $box;

			if( 'seamless' != this.model.get( 'style' ) ) {
				return;
			}

			$box = this.$el.closest( '.postbox' );

			// Start by removing the box
			$box.removeClass( 'postbox' ).addClass( 'uf-container-seamless' );

			// Remove un-neccessary elements
			$box.children( '.handlediv' ).remove();

			// ToDo: Do this conditionally
			$box.children( '.hndle' ).remove();

			this.$el.find( '.uf-fields' ).eq( 0 ).addClass( 'uf-fields-seamless' ).removeClass( 'uf-boxed-fields' );
		},

		/**
		 * Focuses the first field in the container.
		 */
		focusFirstField: function() { // mv23
			// var focused = false;

			// _.each( this.fieldViews, function( view ) {
			// 	if( focused ) {
			// 		return;
			// 	}

			// 	if( 'function' != typeof view.input.focus ) {
			// 		return;
			// 	}

			// 	view.input.focus();
			// 	focused = true;
			// }) 
		},

		/**
		 * Indicates whether the container supports inline tabs.
		 */
		allowsInlineTabs: function() {
			return true;
		},

		allowsInlineXabs: function() {
			return true;
		}
	});

	/**
	 * This model should be used in forms, where multiple containers
	 * should be validated and handled simultaneously on the forms' submit.
	 */
	container.Controller = UltimateFields.Controller.extend({
		initialize: function() {
			this.containers = [];
		},

 		/**
 		 * Handles new containers.
 		 */
 		addContainer: function( container ) {
 			this.containers.push( container );
 		},

 		/**
 		 * Attempts validating all available forms.
 		 */
 		validate: function() {
			var that     = this,
				problems = [];

			_.each( that.containers, function( view ){
				_.each( view.model.validate(), function( problem ) {
					problems.push( problem );
				})
			});

			if( problems.length ) {
				that.showErrorMessage( problems );
				return false;
			}

			return true;
 		},

 		/**
 		 * Shows a message with all errors.
 		 */
 		generateErrorMessage: function( problems ) {
 			var tmpl = UltimateFields.template( 'container-error' );

			return $( tmpl( {
				title: UltimateFields.L10N.localize( 'container-issues' ),
				problems: problems
			}));
 		}
	});

})( jQuery );
