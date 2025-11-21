(function( $ ){

	var field    = UltimateFields.Field,
		mapField = field.Map = {};

	/**
	 * Basic model for the map field.
	 */
	mapField.Model = field.Model.extend({

	});

	/**
	 * Handles the input of the map field.
	 */
	mapField.View = field.View.extend({
		/**
		 * Based on the availability of the API, renders either a map or an error.
		 */
		render: function() {
			var that = this,
				value = this.model.getValue(),
				provider = value.provider || 'leaflet',
				tmpl = UltimateFields.template( 'map-base' );

			// Set some vars
			this.model.google_position = {...value, provider: 'google'};
			this.model.leaflet_position = {...value, provider: 'leaflet'};

			// Add the structure.
			that.$el.html( tmpl({ provider: provider }) );

			if( that.apiLoaded() ) {
				setTimeout( that.renderInput.bind( this ), 10 );
			} else {
				that.showErrorStatus();
			}
			
			setTimeout( that.renderLeaflet.bind( this ), 10 );

			// Show the saved provider
			that.showProvider(provider);

			// Add event to change map provider
			this.$el.find('.uf-map-radio').on('change', function(ev) {
				ev.preventDefault();
				var provider = $(this).val();
			
				// Guardar la posición del marcador en el modelo
				if (provider === 'google') {
					that.model.setValue(that.model.google_position);
					
				} else if (provider === 'leaflet') {
					that.model.setValue(that.model.leaflet_position);

					// Redimensionar el mapa de Leaflet
					setTimeout(function() {
						that.L_map.invalidateSize();
						if (that.L_infoWindow) {
							that.L_infoWindow.update(); // Reajustar el tamaño del popup
						}
					}, 50);
				}
			
				// Cambiar el proveedor de mapas
				that.showProvider(provider);
			});
		},

		/**
		 * Checks if the API is already loaded.
		 *
		 * @return <boolean>
		 */
		apiLoaded: function() {
			if( typeof google == 'undefined' || typeof google.maps == 'undefined' ) {
				return false;
			} else {
				return true;
			}
		},

		/**
		 * When the Google Maps API is present, this will generate the whole input.
		 */
		renderInput: function() {
			var that  = this,
				value = this.model.getValue(),
				$map, $input, center, zoom, default_position;

			// Initialize the map
			$map = this.$el.find( '.uf-map-google .uf-map-ui div' );

			$map.css({
				height: this.model.get( 'height' ) || 400
			});

			if( value && typeof value == 'object' && value.latLng ) {
				center = new google.maps.LatLng( value.latLng.lat, value.latLng.lng );
				zoom   = parseInt( value.zoom );
			} else {
				default_position = { lat: -12.554563528593656, lng: -57.65625000000001, zoom: 2 };
				center = new google.maps.LatLng( default_position.lat, default_position.lng );
				zoom   = default_position.zoom;
			}

			this.map = new google.maps.Map( $map.get( 0 ), {
				center: center,
				zoom: zoom
			});

			// Save center position when map movement ends
			google.maps.event.addListener(this.map, 'idle', function() {
				that.saveCenterPosition('google');
			});

			// Add autocomplete search
			$input = this.$el.find( '.uf-map-input' ).on( 'keydown', function( e ) {
				if( e.which == 13 ) {
					e.preventDefault();
				}
			});
			this.autocomplete = new google.maps.places.Autocomplete( $input.get( 0 ) );

			// Handle search results
			google.maps.event.addListener( this.autocomplete, 'place_changed', function() {
				var place = that.autocomplete.getPlace();

				if( ! place.geometry ) {
					return;
				}

				// Center the map on search result
				that.map.setCenter( place.geometry.location );

				// Adjust zoom
				if( place.geometry.viewport ) {
					that.map.fitBounds( place.geometry.viewport );
				} else {
					that.map.setZoom( 17 );
				}
			});

			// When the field is toggled, force a map resize event
			this.model.on( 'change:visible', function() {
				setTimeout(function(){
					google.maps.event.trigger( that.map, 'resize' );
				}, 50 );
			});
		},

		/**
		 * Saves the current center position of the map.
		 */
		saveCenterPosition: function(provider) {
			var center, lat, lng, zoom, newValue;

			if (provider === 'google' && this.map) {
				center = this.map.getCenter();
				lat = center.lat();
				lng = center.lng();
				zoom = this.map.getZoom();
			} else if (provider === 'leaflet' && this.L_map) {
				center = this.L_map.getCenter();
				lat = center.lat;
				lng = center.lng;
				zoom = this.L_map.getZoom();
			} else {
				return;
			}

			newValue = {
				latLng: { lat: lat, lng: lng },
				zoom: zoom,
				provider: provider
			};

			this.model.setValue(newValue);

			// Save position for provider switching
			if (provider === 'google') {
				this.model.google_position = newValue;
			} else {
				this.model.leaflet_position = newValue;
			}
		},

		/**
		 * Shows a message that the API couldn't be loaded.
		 */
		showErrorStatus: function() {
			this.$el.find('.uf-map-google').html( UltimateFields.template( 'map-error' )() );
		},

		/**
		 * Focuses the address input.
		 */
		// focus: function() {
		// 	this.$el.find( '.uf-map-input' ).focus();
		// },

		/**
		 * Renders a map using Leaflet.
		 */
		renderLeaflet: function() {
			var that = this,
				value = this.model.getValue(),
				$map, center, zoom, default_position;
		
			// Initialize the map
			$map = this.$el.find('.uf-map-leaflet .uf-map-ui div');
		
			$map.css({
				height: this.model.get('height') || 400
			});
		
			if (value && typeof value == 'object' && value.latLng) {
				center = [value.latLng.lat, value.latLng.lng];
				zoom = parseInt(value.zoom);
			} else {
				default_position = { lat: -12.554563528593656, lng: -57.65625000000001, zoom: 2 };
				center = [default_position.lat, default_position.lng];
				zoom = default_position.zoom;
			}
		
			this.L_map = L.map($map.get(0)).setView(center, zoom);
		
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '© OpenStreetMap contributors'
			}).addTo(this.L_map);

			// Save center position when map movement ends
			this.L_map.on('moveend', function() {
				that.saveCenterPosition('leaflet');
			});
		
			// Add Geocoder control for search
			var geocoder = L.Control.geocoder({
				defaultMarkGeocode: false,
				collapsed: false
			})
			.on('markgeocode', function(e) {
				var latLng = e.geocode.center;
		
				// Center the map on search result
				that.L_map.setView(latLng, 17);
			})
			.addTo(this.L_map);
		},

		/**
		 * Show a specific map provider.
		 * @param {string} provider 
		 */
		showProvider: function( provider ) {
			this.$el.find( '.uf-map-provider' ).hide();
			this.$el.find( '.uf-map-' + provider ).show();
		}
	});

})( jQuery );
