(function( $ ){

	var field        = UltimateFields.Field,
		objectField  = field.WP_Object,
		menuSelectorField    = field.Menu_Selector = {};

	/**
	 * Add an extended model.
	 */
	menuSelectorField.Model = objectField.Model.extend({
		defaults: _.extend( {}, objectField.Model.prototype.defaults, {
			target_control: true
		})
	});

	/**
	 * Extend the singular object view.
	 */
	menuSelectorField.View = objectField.View.extend({
		events: {
			'change select': 'selectChanged',
		},

		/**
		 * Adds listeners on initialisation.
		 */
		initialize: function() {
			var that = this;

			// When the model is saved, re-render the input
			this.model.on( 'save', function() {
				that.render();
			});
		},

		/**
		 * Renders the field.
		 */
		render: function() {
			var that   = this,
				tmpl   = UltimateFields.template( 'menu-selector' ),
                value  = this.model.getValue();

			this.$el.html( tmpl({
				value: value
			}));

			// Locate elements
			$select = this.$el.find( 'select' );

			// Populate values
			if( value && 'function' != typeof value ) {
				$select.find('option[value="'+value+'"]').attr("selected", "selected");
			}
        },
        
        /**
		 * Handles changes of the select field.
		 */
		selectChanged: function( e ) {
			this.model.setValue( e.target.value );
		},
	});


})( jQuery );
