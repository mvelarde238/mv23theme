(function( $ ){
	var uf                  = window.UltimateFields,
		field               = uf.Field,
		actionsControlField = field.Actions_Control = {};

	/**
	 * A basic model for the "field" with some default values.
	 */
	actionsControlField.Model = field.Model.extend({});

	/**
	 * A view, which will do most of the heavy lifting
	 */
	actionsControlField.View = field.View.extend({
		events: {
			'click .action-trigger': 'onTriggerClick'
		},
		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var tmpl = UltimateFields.template('actions-control');
				actions = this.model.get( 'actions' );

			// Start with the base
			this.$el.html( tmpl( this.model.toJSON() ) );
		},
		onTriggerClick: function( e ) {
			e.preventDefault();

			var $button = $( e.currentTarget ),
				action  = $button.data( 'action' ),
				args    = $button.data( 'args' ) || [];

			if( window['UF_Editor']?.getSelected() ){
				args.component = window['UF_Editor'].getSelected();
				window['UF_Editor'].runCommand( action, args );
			} else {
				console.warn( 'No component selected in UF_Editor.' );
			}
		}
	});

})( jQuery );
