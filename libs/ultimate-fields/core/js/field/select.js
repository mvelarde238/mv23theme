(function( $ ){

	var uf          = window.UltimateFields,
		field       = uf.Field,
		selectField = field.Select = {};

	selectField.lastListName = 0;

	selectField.Model = field.Model.extend({
		/**
		 * Returns the options for the field.
		 */
		getOptions: function() {
			var options = this.get( 'options' );
			
			// If options is an array (new format), convert it back to object for compatibility
			if( _.isArray( options ) ) {
				var optionsObj = {};
				_.each( options, function( option ) {
					optionsObj[ option.value ] = option.label;
				});
				return optionsObj;
			}
			
			// Return as-is for backwards compatibility
			return options;
		},

		/**
		 * Returns the options as an array to preserve order.
		 */
		getOptionsArray: function() {
			var options = this.get( 'options' );
			
			// If options is already an array (new format), return as-is
			if( _.isArray( options ) ) {
				return options;
			}
			
			// Convert object to array for backwards compatibility
			var optionsArray = [];
			_.each( options, function( label, value ) {
				optionsArray.push({
					value: value,
					label: label
				});
			});
			
			return optionsArray;
		},

		/**
		 * Checks if there is a selected element.
		 */
		checkForValidSelection: function() {
			var that    = this,
				options = this.getOptions(),
				current = this.getValue(),
				set     = false,
				allOptions = [];

			// Get all options
			_.each( options, function( label, option ) {
				if( 'object' == typeof label ) {
					_.each( label, function( subLlabel, key ) {
						allOptions.push( key.toString() );
					});
				} else {
					allOptions.push( option.toString() );
				}
			});

			// Load the first option
			if( -1 == allOptions.indexOf( current.toString() ) ) {
				_.each( allOptions, function( key ) {
					if( set ) {
						return;
					}

					set = true;
					that.setValue( key );
				});
			}
		}
	});

	selectField.View = field.View.extend({
		events: {
			'change select':            'selectChanged',
			'change input[type=radio]': 'radioChanged'
		},

		/**
		 * Listen for option changes.
		 */
		initialize: function() {
			this.model.on( 'options-changed', _.bind( this.render, this ) );
		},

		/**
		 * Renders the input of the field.
		 */
		render: function() {
			this.$el.empty();

			this.model.checkForValidSelection();

			if( _.isEmpty( this.model.getOptions() ) ) {
				this.$el.text( UltimateFields.L10N.localize( 'select-no-options' ) );
			} else {
				if( 'radio' == this.model.get( 'input_type' ) ) {
					this.renderRadios();
				} else {
					this.renderSelect();
				}
			}
		},

		/**
		 * Renders a select as the input of the field.
		 */
		renderSelect: function() {
			var that    = this,
				current = this.model.getValue(),
				$input;

			this.$el.empty();

			if( prefix = this.model.get( 'prefix' ) ) {
				$( '<span class="uf-field-prefix" />' )
					.html( prefix )
					.appendTo( this.$el );
			}

			// Create a basic select and add options to it
			$input = $( '<select></select>' );

			// Use getOptionsArray to preserve order
			_.each( that.model.getOptionsArray(), function( option ) {
				var key = option.value;
				var label = option.label;
				
				if( 'object' != typeof label ) {
					// Normal option
					var $option = $( '<option />' )
						.attr( 'value', key )
						.text( label )
						.appendTo( $input );

					if( key == current ) $option.prop( 'selected', 'selected' );
				} else {
					// Groups
					var $group = $( '<optgroup />' )
						.attr( 'label', key )
						.appendTo( $input );

					_.each( label, function( label, key ){
						var $option = $( '<option />' )
							.attr( 'value', key )
							.text( label )
							.appendTo( $group );

						if( key == current ) $option.prop( 'selected', 'selected' );
					});
				}
			});

			// Append the element to the dom
			this.$el.append( $input );

			// Add select2 if needed.
			if( this.model.get( 'use_select2' ) ) {
				$input.select2();
			}
		},

		/**
		 * Handles changes of the select field.
		 */
		selectChanged: function( e ) {
			this.model.setValue( e.target.value );
		},

		/**
		 * Renders radio buttons as the input of the field.
		 */
		renderRadios: function() {
			var that    = this,
				$list   = $( '<ul />' ),
				current = this.model.getValue(),
				name;

			// Generate a name for the inputs
			name = 'uf-radio-' + ( selectField.lastListName++ );

			// Add options using getOptionsArray to preserve order
			_.each( this.model.getOptionsArray(), function( option ) {
				var value = option.value;
				var label = option.label;
				var $label, $input;

				$input = $( '<input type="radio" />' ).attr({
					value: value,
					name:  name
				});
				if( value == current ) $input.prop( 'checked', true );

				$label = $( '<label />' ).html( label ).prepend( $input );

				$list.append( $( '<li />' ).append( $label ) );
			});

			// Use the right layout
			$list
				.addClass( 'uf-radio' )
				.addClass( 'uf-radio-orientation-' + this.model.get( 'orientation' ) );

			// Add the list to the dom
			this.$el.append( $list );
		},

		/**
		 * Whenever a radio gets changed, save its value.
		 */
		radioChanged: function( e ) {
			if( e.target.checked ) {
				this.model.setValue( e.target.value );
			}
		},

		/**
		 * Focuses the input within the field.
		 */
		focus: function() {
			if( 'radio' == this.model.get( 'input_type' ) ) {
				this.$el.find( 'input:eq(0)' ).focus();
			} else {
				this.$el.find( 'select' ).focus();
			}
		}
	});

})( jQuery );
