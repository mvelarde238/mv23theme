(function( $ ){

	var field      = UltimateFields.Field,
		colorField = field.Color = {};

	/**
	 * Handles the input of the color field.
	 */
	colorField.View = field.View.extend({
		/**
		 * Renders the color picker/field.
		 */
		render: function() {
			var that = this,
				$input, args, color;

			if( prefix = this.model.get( 'prefix' ) ) {
				$( '<span class="uf-field-prefix" />' )
					.html( prefix )
					.appendTo( this.$el );
			}

			color = this.model.getValue() || '';

			args = {
				defaultColor: this.model.get( 'default_value' ) || '',
				palettes: COLOR_PICKER.palettes,
				change: function( e, ui ) {
					that.model.setValue( ui.color.toString() );
				},
				clear: function( e, ui ) {
					that.model.setValue( '' );
				}
			}

			$input = $( '<input type="text" />' )
				.val( color )
				.appendTo( this.$el );

			if( $.fn.wpColorPicker ) {
				$input.wpColorPicker( args );
			}
		},

		/**
		 * Focuses the input within the field.
		 */
		focus: function() {
			this.$el.find( '.wp-color-result' ).focus();
		}
	});

})( jQuery );
