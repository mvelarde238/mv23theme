(function( $ ){

	var uf            = window.UltimateFields,
		field         = uf.Field,
		textareaField = field.Textarea = {};

	textareaField.Model = field.Model.extend({
		/**
		 * Returns a value for Yoast SEO.
		 */
		getSEOValue: function() {
			return this.getValue();
		}
	});

	textareaField.View = field.View.extend({
		events: {
			'change textarea': 'change'
		},

		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var $input, codemirrorSettings, currentValue;

			// WordPress's get_option() returns boolean false for non-existent options.
			// Normalize it here so the model never serializes the string "false".
			currentValue = this.model.getValue();
			if ( currentValue === false || currentValue === null || currentValue === undefined ) {
				currentValue = '';
				this.model.setValue( '' );
			}

			$input = $( '<textarea />' )
				.attr( 'rows', this.model.get( 'rows' ) )
				.val( currentValue )
				.attr( 'placeholder', this.model.get( 'placeholder' ) )
				.appendTo( this.$el );

			codemirrorSettings = this.model.get( 'codemirror' );

			if ( codemirrorSettings && window.wp && wp.codeEditor ) {
				var placeholder = this.model.get( 'placeholder' );
				// Remove the HTML placeholder attribute — CodeMirror receives it as a config
				// option below, so the attribute on the hidden textarea is redundant and can
				// cause a brief visual overlap before fromTextArea() hides the element.
				$input.removeAttr( 'placeholder' );
				if ( placeholder ) {
					codemirrorSettings = _.extend( {}, codemirrorSettings, {
						codemirror: _.extend( {}, codemirrorSettings.codemirror, { placeholder: placeholder } )
					} );
				}
				this.editor = wp.codeEditor.initialize( $input[0], codemirrorSettings );
				this.editor.codemirror.on( 'change', _.throttle( _.bind( this.syncFromCodeMirror, this ), 100 ) );
				// Defer refresh so CodeMirror recalculates gutter/layout after the element
				// is fully painted in the DOM — prevents gutter overlapping the code text.
				_.defer( _.bind( this.editor.codemirror.refresh, this.editor.codemirror ) );
			} else {
				// Assign a manual keyup handler only when CodeMirror is not active
				this.$el.find( 'textarea' ).on( 'keyup', _.throttle( _.bind( this.change, this ), 100 ) );
			}
		},

		/**
		 * Syncs the CodeMirror editor value back to the textarea and triggers a model update.
		 */
		syncFromCodeMirror: function() {
			this.editor.codemirror.save();
			this.change();
		},

		/**
		 * Saves the value of the field when it gets changed.
		 */
		change: function() {
			var raw    = this.model.getValue(),
				value  = ( raw === false || raw === null || raw === undefined ) ? '' : String( raw ),
				$input = this.$el.find( 'textarea' );

			if( value !== $input.val() ) {
				this.model.setValue( $input.val() );
			}
		},

		/**
		 * Focuses the input within the field.
		 */
		focus: function() {
			if ( this.editor ) {
				this.editor.codemirror.focus();
			} else {
				this.$el.find( 'textarea' ).focus();
			}
		}
	});

})( jQuery );
