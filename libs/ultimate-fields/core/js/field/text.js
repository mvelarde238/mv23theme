(function( $ ){

	var uf       = window.UltimateFields,
		field    = uf.Field,
		textField = field.Text = {};

	/**
	 * A basic model for the field with some default values.
	 */
	textField.Model = field.Model.extend({
		defaults: _.extend({
			prefix:      '',
			suffix:      '',
			placeholder: '',
			suggestions: [],
		}, field.Model.prototype.defaults ),

		/**
		 * Returns a value for Yoast SEO.
		 */
		getSEOValue: function() {
			var value = this.getValue(),
				attr  = this.get( 'html_attributes' ),
				cssClass, heading;

			if( ( 'class' in attr )  ) {
				cssClass = attr[ 'class' ];

				if( cssClass.match( /seo-h/ ) ) {
					heading = 'h' + cssClass.match( /seo-h(\d)/ )[ 1 ];
					value = '<' + heading + '>' + value + '</' + heading + '>';
				}
			}

			return value;
		}
	});

	textField.View = field.View.extend({
		events: {
			'change input': 'change',
			'blur input':   'change'
		},

		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var that = this, $input, prefix, suffix;

			// Indicate that it's a basic input
			this.$el.addClass( 'uf-basic-input' );

			if( prefix = this.model.get( 'prefix' ) ) {
				$( '<span class="uf-field-prefix" />' )
					.html( prefix )
					.appendTo( this.$el );
			}

			$input = $( '<input type="text" />' )
				.appendTo( this.$el )
				.attr( 'placeholder', this.model.get( 'placeholder' ) )
				.val( this.model.getValue() );

			if( suffix = this.model.get( 'suffix' ) ) {
				$( '<span class="uf-field-suffix" />' )
					.html( suffix )
					.appendTo( this.$el );
			}

			// Add autocomplete suggestions
			// $( document ).on( 'uf-init', function() {
				that.addAutoComplete( $input );
			// });

			// Add JQuery Flexdatalist
			that.addFlexdatalist( $input );

			// Listen for external changes
			this.model.on( 'external-value', function() {
				$input.val( that.model.getValue() );
			});

			// Assign a manual keyup handler
			this.$el.find( 'input' ).on( 'keyup', _.throttle( _.bind( this.keyUp, this ), 100 ) );
		},

		/**
		 * Saves the value of the field when it gets changed.
		 */
		change: function() {
			var value  = this.model.getValue(),
				$input = this.$el.find( 'input' );

			if( value != $input.val() ) {
				this.model.setValue( $input.val() );
			}

			this.model.trigger( 'text-changed' );
		},

		/**
		 * Immediately saves the value of the field on keyup.
		 */
		keyUp: function() {
			var value  = this.model.getValue(),
				$input = this.$el.find( 'input' );

			if( value != $input.val() ) {
				this.model.setValue( $input.val() );
			}
		},

		/**
		 * Adds autocomplete options to the field.
		 */
		addAutoComplete: function( $input ) {
			var that = this, 
				model = this.model,
				suggestions = this.model.get( 'suggestions' );

			if( ! ( suggestions && suggestions.length ) ) {
				return;
			}

			const $wrapper = $('<div class="dropdown-wrapper"></div>');
			const $row = $('<div class="input-row"></div>');
			const $button = $('<button type="button" class="dropdown-button">▼</button>');

			// Reorganizar DOM
			$input.after($wrapper);
			$input.detach().appendTo($row);
			$button.appendTo($row);
			$row.appendTo($wrapper);

			// Crear el DropdownInput pasando el wrapper DOM nativo
			new DropdownInput(
				$wrapper.get(0), 
				suggestions,
				function(selectedValue) {
					model.setValue(selectedValue);
				}
			);

			// Add the UI widget
			// $input.autocomplete({
			// 	source: suggestions
			// });
		},

		/**
		 * Adds flex datalist options to the field.
		 */
		addFlexdatalist: function( $input ) {
			var that = this, datalist = this.model.get( 'datalist' );
			
			if( ! ( datalist && datalist.length ) ) {
				return;
			}

			// Crear el objeto <datalist>
			var datalist_name = this.cid+'-datalist';
			var $dataList = $('<datalist id="'+datalist_name+'">');
			datalist.forEach(function(opcion) {
				$('<option>').attr('value', opcion).appendTo($dataList);
			});
			$dataList.appendTo( this.el );
			$input.attr( 'list', datalist_name );
				
			$input.flexdatalist({
				minLength: 0,
				multiple: true,
				valuesSeparator : ' ',
				// normalizeString	to do
			});			
		},

		/**
		 * Focuses the input within the field.
		 */
		focus: function() {
			this.$el.find( 'input' ).focus();
		}
	});

})( jQuery );
