(function( $ ){

	var uf       = window.UltimateFields,
		field    = uf.Field,
		repeater = field.Repeater,
		columns_layout   = field.Columns_Layout = {};

	// Add some extra functionality for layout models
	columns_layout.Model  = repeater.Model.extend({
		/**
		 * Overwrite the datastore method in order to avoid working
		 * with values before there is a datastore to save them in.
		 */
		setDatastore: function( datastore ) {
			var that = this;

			// Do the normal initialization
			field.Model.prototype.setDatastore.call( this, datastore );

			// This collection will hold each added group, no models or views
			this.__columns = new UltimateFields.Datastore.Collection();

			// Create a collection for the groups (actual models)
			this.groups = [];

			// This will indicate how many of each group we have
			this.groupTypes = new Backbone.Collection;
			_.each( this.get( 'groups' ), function( group ) {
				that.groupTypes.add( new repeater.GroupType({
					id:       group.id,
					existing: 0,
					minimum:  group.minimum || false,
					maximum:  group.maximum || false
				}));
			});
		},

		/**
		 * Forces columns to be cached/saved properly.
		 */
		// refresh: function() {
		// 	this.__columns.sort();
		// },

		/**
		 * Starts listening for data changes.
		 */
		listenToGroupChanges: function() {
			var that = this;

			// Handle changes
			this.__columns.on( 'change sort destroy', function( e ) {
				if( ( 'changed' in e ) && ( '__tab' in e.changed ) )
					return;

				that.exportToDatastore( that.get('columns_settings'), that.get('row_settings') );
			});
		},

		/**
		 * When there is a reason to, exports the added columns in a proper format.
		 */
		exportToDatastore: function(columns_settings, row_settings) {
			var exported = {}, content = [], max = 0, i, totalIndex = 1;

			// Get the maximum amount of columns
			this.groups.forEach(function( group ) {
				// preserve 'row' name because we are extending layout groups
				max = Math.max( max, group.get( 'row' ) + 1 );
			});

			// Create empty columns
			for( i=0; i<max; i++ ) {
				content.push([]);
			}

			// Go through columns and export them
			this.groups.forEach(function( group ) {
				// preserve 'row' name because we are extending layout groups
				var columnIndex = group.get( 'row' );

				if( content[ columnIndex ] ){
					content[ columnIndex ].push({
						index: group.get( 'index' ),
						data:  group.datastore,
						group: group
					});
				}
			});

			// Go through each column, sort results and then export them
			for( i=0; i<max; i++ ) {
				// Sort
				content[ i ].sort(function compare(a, b) {
					return a.index > b.index  ? 1 : -1;
				});

				// Export
				content[ i ] = content[ i ].map(function( combo ) {
					combo.group.set( 'displayed_index', totalIndex++ );
					return combo.data.toJSON();
				});
			}

			exported = {
				content: content, 
				columns_settings: columns_settings,
				row_settings: row_settings
			};

			this.setValue(exported);
			this.trigger( 'value-saved' );
		},

		/**
		 * Returns an SEO-analyzable value of the field.
		 */
		getSEOValue: function() {
			var values = [],
				groups = this.get( 'groups' );

			_.each( this.groups, function( group ) {
				group.get( 'fields' ).each(function( field ) {
					var value = field.getSEOValue();

					if( value ) {
						values.push( value );
					}
				});
			});

			return values.join( ' ' );
		}
	});

	// Define the view for the layout
	columns_layout.View = repeater.View.extend({
		initialize: function() {
			var that = this;

			// Do the standard initialization
			field.View.prototype.initialize.apply( this, arguments );

			// Listen for replacements
			this.model.datastore.on( 'value-replaced', function( name ) {
				if( name != that.model.get( 'name' ) ) {
					return;
				}

				that.model.groups = [];
				that.model.__columns.reset([]);
				that.render();
			})
		},

		/**
		 * Renders the view.
		 */
		render: function() {
			var that  = this,
				tmpl  = UltimateFields.template( 'columns-layout' ),
				proto = UltimateFields.template( 'columns-layout-element-prototype' ),
				types = [];

			// Add the CSS class and the basic layout
			this.$el.addClass( 'uf-columns-layout' );
			this.$el.html( tmpl() );

			// Add row settings
			var field_value = this.model.getValue() ?? [];
			var row_settings = ( field_value['row_settings'] ) ? field_value['row_settings'] : {};
			this.$el.data('row_settings', row_settings );
			this.model.set('row_settings', row_settings );

			// Add has-value class if there is any value, commented because row settings has always values
			// var row_has_settings_class = ( !_.isEmpty(row_settings) ) ? 'has-values' : '';
			// this.$el.find('.uf-columns-layout-row-settings').addClass( row_has_settings_class );

			// Add all existing elements
			this.addExistingData();

			// Add all types as draggables
			_.each( this.model.get( 'groups' ), function( group ) {
				var type = that.model.getGroupType( group.id ), $proto;

				types.push({
					id:    group.id	,
					title: group.title,
					min:   group.min_width,
					max:   group.max_width
				});

				$proto = $( proto({
					type:        group.id,
					icon:       group.icon,
					title:       group.title,
					description: group.description
				}));

				type.on( 'change', function() {
					$proto[ type.canBeAdded() ? 'show' : 'hide' ]();
					UltimateFields.ContainerLayout.DOMUpdated();
				});

				if( ! type.canBeAdded() ) {
					$proto.hide();
				}

				that.$el.find( '.uf-columns-layout-draggables' ).append( $proto );
			});

			// Start the external columns layout script
			this.$el.columns_layout({
				columns: this.model.get( 'columns' ),
				types:   types,
				placeholderColumn: function( $column, column ) {
					$column.append( UltimateFields.template( 'columns-layout-placeholder' )() );
					column.$groups = $column.find( '.uf-columns-layout-column-groups' );
				},
				populateElement: this.populateElement.bind( this )
			});

			// Listen for updates in the layout, in order to dump values
			this.$el.on( 'columns-layout-updated', function(e, columns_settings, row_settings) {
				that.model.exportToDatastore( columns_settings, row_settings );
			});

			// Start a grid within the draggables to adjust spacings based on position
			var columns_layout = new UltimateFields.ContainerLayout({
				el: this.$el.find( '.uf-columns-layout-draggables' ),
				gridSelector: '.uf-repeater-prototypes-column'
			});

			columns_layout.startGrid();

			// When everything is set up, start listening for changes
			this.model.listenToGroupChanges();
		},

		/**
		 * Creates and prepares the view for a group.
		 */
		prepareGroupView: function( type, data ) {
			var that = this, settings, datastore, model, view;

			settings = _.findWhere( this.model.get( 'groups' ), {
				id: type
			});

			if( data && data.datastore ){
				datastore = data.datastore;
			} else {
				datastore = new UltimateFields.Datastore( data || {} );
				datastore.parent = this.model.datastore;
			}	

			datastore.set( '__type', type );

			// Prepare the container model
			model = new UltimateFields.Container.Layout_Group.Model( settings );
			model.set( '__type', settings.id );
			model.setDatastore( datastore );
			if( data && data.displayedIndex ) model.set( 'displayed_index', data.displayedIndex );

			// Push the datastore to the columns
			this.model.__columns.add( datastore, {
				silent: false
			});

			this.model.groups.push( model );
			model.on( 'destroy', function() {
				that.model.groups.splice( that.model.groups.indexOf( model ), 1 );
			});

			// Create the view
			view = new UltimateFields.Container.Columns_Layout_Group.View({
				model: model
			});

			view.on( 'uf-duplicate', function( data ) {
				view.$el.trigger('duplicateElementRequest', {
					columnIndex: model.get('row'),
					type: model.get('__type'),
					datastore: data.datastore,
					insertAfter: view.$el.parent('.uf-columns-layout-element')
				});
			});

			return view;
		},

		/**
		 * Populates an element once its within the columns_layout.
		 */
		populateElement: function( element, data = {} ) {
			var view;

			view = this.prepareGroupView( element.type.id, data );
			element.$el.append( view.$el );
			view.render();

			// Bind the view to the element it's contained within.
			view.bindToElement( element );

			// If the model is destroyed, remove the element
			view.model.on( 'destroy', function() {
				element.$el.remove();
				element.trigger( 'destroy' );
			});
		},

		/**
		 * Adds the existing data to the view.
		 */
		addExistingData: function() {
			var that = this, $content = this.$el.find( '.uf-columns-layout-content' ), columnIndex = 0;

			var field_value = this.model.getValue() ?? [];
			var field_content = ( field_value['content'] && field_value['content'].length ) ? field_value['content'] : [[],[]];
			var columns_settings = ( field_value['columns_settings'] && field_value['columns_settings'].length ) ? field_value['columns_settings'] : [[],[]];
			this.model.set('columns_settings', columns_settings );

			_.each( field_content, function( groups ) {
				var $column       = $( UltimateFields.template( 'columns-layout-column' )() )
					$columnGroups = $column.find( '.uf-columns-layout-column-groups' );

				// Add column settings
				$column.data('column_settings', columns_settings[columnIndex] );

				// Add the column to the content
				$content.append( $column );

				_.each( groups, function( element ) {
					var $element, view;

					$element = $( '<div class="uf-columns-layout-element" />' )
						.data({
							type:  element.__type,
							width: element.__width
						})
						.appendTo( $columnGroups );

					view = that.prepareGroupView( element.__type, element );
					// preserve 'row' name because we are extending layout groups
					view.model.set( 'row', columnIndex );
					$element.append( view.$el );
					view.render();

					// Once the layout plugin associates the div with an element, bind the view to it
					$element.on( 'uf-columns-layout-added', function( e, layoutElement ) {
						view.bindToElement( layoutElement );

						// If the model is destroyed, remove the element
						view.model.on( 'destroy', function() {
							layoutElement.$el.remove();
							layoutElement.trigger( 'destroy' );
						});
					});
				});

				columnIndex++;
			});
		}
	});

})( jQuery );
