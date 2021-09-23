(function( $ ){

	var xab = UltimateFields.Xab = Backbone.View.extend({
		tagName: 'a',
		className: 'uf-xab',

		events: {
			click: 'clicked'
		},

		initialize: function( args ) {
			var that = this;

			this.render();

			that.$el[ that.model.get( 'invalidXab' ) ? 'addClass' : 'removeClass' ]( 'uf-xab-invalid' );
			this.model.on( 'change:invalidXab', function() {
				that.$el[ that.model.get( 'invalidXab' ) ? 'addClass' : 'removeClass' ]( 'uf-xab-invalid' );
			});
		},

		render: function() {
			var that = this;

			this.$el.attr( 'href', '#' );

			// Add the icon
			if( this.model.get( 'icon' ) ) {
				$( '<span class="uf-xab-icon" />' )
					.appendTo( this.$el )
					.addClass( this.model.get( 'icon' ) );
			}

			// Add the text
			$( '<div class="uf-xab-text" />' )
				.appendTo( this.$el )
				.text( this.model.get( 'label' ) );

			// Activate the tab if possible
			if( this.model.get( 'name' ) == this.model.datastore.get( '__xab' ) ) {
				this.$el.addClass( 'uf-xab-active' );
			}

			// Listen to tab changes
			that.toggleActive();
			this.model.datastore.on( 'change:__xab', function() {
				that.toggleActive();
			});

			// Show/hide the tab when neccessary
			this.toggleVisibility();
			this.model.on( 'change:visible', function() {
				that.toggleVisibility();
			});
		},

		clicked: function() {
			if( ! this.model.get( 'visible' ) ) {
				return false;
			}

			this.model.datastore.set( '__xab', this.model.get( 'name' ) );
			this.$el.blur();

			$( document ).trigger( 'uf-xab-changed' );
			UltimateFields.ContainerLayout.DOMUpdated();

			return false;
		},

		toggleActive: function() {
			var method = this.model.datastore.get( '__xab' ) == this.model.get( 'name' )
				? 'addClass'
				: 'removeClass';

			this.$el[ method ]( 'uf-xab-active' );
		},

		toggleVisibility: function() {
			this.$el[ this.model.get( 'visible' ) ? 'removeClass' : 'addClass' ]( 'disabled' );
		}
	});

})( jQuery );
