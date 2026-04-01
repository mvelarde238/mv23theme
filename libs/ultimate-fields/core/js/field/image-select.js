(function( $ ){

	var uf          = window.UltimateFields,
		field       = uf.Field,
		selectField = field.Image_Select = {};

	selectField.lastListName = 0;

	selectField.Model = field.Model.extend({
		/**
		 * Overwrite the datastore method in order to allow setting a
		 * default value as soon as there is a datastore to set it to.
		 */
		setDatastore: function( datastore ) {
			var that = this, set = false;

			// Do the normal initialization
			field.Model.prototype.setDatastore.call( this, datastore );

			// Locate the first option and use it if any
			const useButtons = this.get( 'use_buttons' );
			if( ! this.getValue() && ! useButtons ) {
				_.each( this.get( 'options' ), function( option, key ) {
					if( ! set ) {
						that.setValue( key );
						set = true;
					}
				});
			}
		}
	});

	selectField.View = field.View.extend({
		events: {
			'change input': 'changed',
			'click button': 'buttonClicked'
		},

		initialize: function() {
			this.model.on( 'update-views', _.bind( this.updateView, this ) );
		},

		/**
		 * Renders the input of the field.
		 */
		render: function() {
			var that    = this,
				current = this.model.getValue(),
				tmpl    = UltimateFields.template( 'image-select' ), args, current;

			args = {
				options:     this.model.get( 'options' ),
				inputId:     'image-select-' + ( selectField.lastListName++ ),
				show_label:  this.model.get( 'show_label' ),
				use_buttons: this.model.get( 'use_buttons' )
			};

			this.$el
				.addClass( 'uf-image-select' )
				.toggleClass( 'uf-image-select--buttons', !! args.use_buttons )
				.html( tmpl( args ) );

			// Activate the right element
			this.updateView();
		},

		updateView: function() {
			var current    = this.model.getValue(),
				useButtons = this.model.get( 'use_buttons' );

			if( useButtons ) {
				this.$el.find( 'button' ).each(function() {
					$( this ).toggleClass( 'uf-selected', $( this ).val() == current );
				});
			} else {
				this.$el.find( 'input' ).each(function() {
					if( this.value == current ) {
						$( this ).prop( 'checked', 'checked' ).closest( 'label' ).addClass( 'uf-selected' );
					} else {
						// needed when 'update-views' is triggered
						$( this ).prop( 'checked', false ).closest( 'label' ).removeClass( 'uf-selected' );
					}
				});
			}
		},

		/**
		 * Whenever a radio gets changed, save its value.
		 */
		changed: function( e ) {
			if( e.target.checked ) {
				this.model.setValue( e.target.value );

				$( e.target )
					.closest( 'label' )
					.addClass( 'uf-selected' )
					.siblings()
					.removeClass( 'uf-selected' );
			}
		},

		/**
		 * Whenever a button is clicked, save its value.
		 */
		buttonClicked: function( e ) {
			var $btn = $( e.currentTarget );
			this.model.setValue( $btn.val() );
			$btn.addClass( 'uf-selected' ).siblings( 'button' ).removeClass( 'uf-selected' );
		}
	});

})( jQuery );
