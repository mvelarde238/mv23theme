(function( $ ){

	var field        = UltimateFields.Field,
		objectField  = field.WP_Object,
		botonField    = field.Boton = {};

	/**
	 * Handle WpLink Click
	 */
	$(document).on('click', '#wp-link-submit', function(event) {
		var that = wpLink.referer;
		$text = wpLink.referer.$el.find( '.uf-boton-text-input' );
		$url = wpLink.referer.$el.find( '.uf-boton-url-input' );
		$tab = wpLink.referer.$el.find( '.uf-boton-new-tab' );

		var linkAtts = wpLink.getAttrs();
		$url.val(linkAtts.href);
		var texto = $('#wp-link-text').val();
		$text.val( texto );
		var isChecked = linkAtts.target == "_blank" ? 'checked' : '';
		$tab.find( 'input' ).prop({ checked: isChecked });

		$text.trigger("change");
		$url.trigger("change");
		$tab.find( 'input' ).trigger("change");

		that.handleHelper(linkAtts.href, texto);

		wpLink.close();
		wpLink.referer = null;
		//trap any events
		event.preventDefault ? event.preventDefault() : event.returnValue = false;
		event.stopPropagation();
		return false;
	});
		
		

	/**
	 * Add an extended model.
	 */
	botonField.Model = objectField.Model.extend({
		defaults: _.extend( {}, objectField.Model.prototype.defaults, {
			target_control: true
		})
	});

	/**
	 * Extend the singular object view.
	 */
	botonField.View = objectField.View.extend({
		events: {
			'change .uf-boton-text-input': 'saveText',
			'change .uf-boton-url-input': 'saveURL',
			'change .uf-boton-new-tab-input': 'saveTab'
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
				tmpl   = UltimateFields.template( 'boton' ),
				value  = this.model.getValue(),
				$tab;

			this.$el.html( tmpl({
				value: value,
				target_control: this.model.get( 'target_control' )
			}));

			// Locate elements
			$text = this.$el.find( '.uf-boton-text-input' );
			$url = this.$el.find( '.uf-boton-url-input' );
			$tab = this.$el.find( '.uf-boton-new-tab' );

			// Populate values
			if( value.boton && 'function' != typeof value.boton ) {
				$url.val( value.boton );
			}

			if( value.text && 'function' != typeof value.text ) {
				$text.val( value.text );
			}
			
			if( value.new_tab ) {
				$tab.find( 'input' ).prop({ checked: 'checked' });
			}

			this.initializeLinkSelector();
			this.handleHelper(value.boton,value.text);
		},

		/**
		 * Saves the Text if manually typed.
		 */
		saveText: function( e ) {
			var value = _.clone( this.model.getValue() );
			value.text = e.target.value;
			this.model.setValue( value );
		},

		/**
		 * Saves the URL if manually typed.
		 */
		saveURL: function( e ) {
			var value = _.clone( this.model.getValue() );
			value.boton = e.target.value;
			this.model.setValue( value );
		},

		/**
		 * Saves the tab setting.
		 */
		saveTab: function( e ) {
			var value = _.clone( this.model.getValue() );
			value.new_tab = e.target.checked;
			this.model.setValue( value );
		},

		/**
		 * Create a element to visualize the data
		 */
		handleHelper: function(enlace, texto){
			if(enlace && texto){
				var linkHelper = '<p style="margin:0">'+texto+' <a href="'+enlace+'">'+enlace+'</a></p>';
				this.$el.find( '.link-helper' ).html( linkHelper );
			} else {
				this.$el.find( '.link-helper' ).html( '' );
			}
		},

		/**
		 * Inicializa el botón para seleccionar un link
		 */
		initializeLinkSelector: function(){
			var that   = this;

			$(this.$el).on('click', '.link-selector', function(event) {
				event.preventDefault();

				$text = that.$el.find( '.uf-boton-text-input' );
				$url = that.$el.find( '.uf-boton-url-input' );
				$tab = that.$el.find( '.uf-boton-new-tab' );

				wpLink.setDefaultValues = function() {
					$('#wp-link-url').val( $url.val() );
					$('#wp-link-text').val( $text.val() );
					var isChecked = $tab.find( 'input' ).prop('checked') ? 'checked' : '';
					$('#wp-link-target').prop('checked', isChecked);
				};

				wpLink.open('fake-editor');
				wpLink.referer = that;
				return false;
			});
		},
	});


})( jQuery );
