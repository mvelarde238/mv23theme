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
				value = this.model.getValue(), new_field_value,
				$map, $input, center, zoom, default_position, initial_map_position;

			// Initialize the map
			$map = this.$el.find( '.uf-map-google .uf-map-ui div' );

			$map.css({
				height: this.model.get( 'height' ) || 400
			});

			if( value && typeof value == 'object' ) {
				center = new google.maps.LatLng( value.latLng.lat, value.latLng.lng );
				zoom   = parseInt( value.zoom );
			} else {
				default_position = { lat: 40.416775, lng: -3.703790, zoom: 8 };
				initial_map_position = this.model.get( 'initial_map_position' ) || default_position;
				center = new google.maps.LatLng( initial_map_position.lat, initial_map_position.lng );
				zoom   = initial_map_position.zoom;
			}

			this.map = new google.maps.Map( $map.get( 0 ), {
				center: center,
				zoom: zoom
			});

			// Add autocomplete
			$input = this.$el.find( '.uf-map-input' ).on( 'keydown', function( e ) {
				if( e.which == 13 ) {
					e.preventDefault();
				}
			});
			this.autocomplete = new google.maps.places.Autocomplete( $input.get( 0 ) );

			if( value && typeof value == 'object' ) {
				that.addElements();
			}

			// Handle changes
			google.maps.event.addListener( this.autocomplete, 'place_changed', function() {
				var place = that.autocomplete.getPlace();

				if( ! place.geometry ) {
					return;
				}

				// Center the map
				that.map.setCenter( place.geometry.location );

				// Change the zoom
				if( place.geometry.viewport ) {
					that.map.fitBounds( place.geometry.viewport );
				} else {
					that.map.setZoom( 17 );
				}

				// Save the value
				new_field_value = {
					latLng:       { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
					address:      place.formatted_address,
					zoom:         that.map.getZoom(),
					provider:     'google',
					addressParts: $.map( place.address_components, function( line ) {
						return line.long_name
					})
				};
				that.model.setValue( new_field_value );

				// save the position of the marker
				that.model.google_position = new_field_value.latLng;

				that.addElements();
			});

			// When the field is toggled, force a map resize event
			this.model.on( 'change:visible', function() {
				setTimeout(function(){
					google.maps.event.trigger( that.map, 'resize' );
				}, 50 );
			});
		},

		/**
		 * Handles location changes.
		 */
		addElements: function() {
			var that = this;

			// Clear old elements
			if( typeof that.infoWindow != 'undefined' ) {
				that.infoWindow.close();
			}
			if( typeof that.marker != 'undefined' ) {
				that.marker.setMap( null );
			}

			// Add a marker
			that.marker = new google.maps.Marker({
				map:       that.map,
				position:  that.map.getCenter(),
				draggable: true
			});

			// Show a popup
			that.infoWindow = new google.maps.InfoWindow();
			that.infoWindow.setContent( that.model.getValue().address );
			that.infoWindow.open( that.map, that.marker );

			// Handle clearing
			google.maps.event.addListener( that.infoWindow, 'closeclick', function() {
				that.clearLocation();
			});

			google.maps.event.addListener( that.marker, 'dragend', function( event ) {
				var latLng = event.latLng,
					lat    = latLng.lat(),
					lng    = latLng.lng(),
					new_field_value;

				new_field_value = {
					latLng:       { lat: lat, lng: lng },
					address:      lat + ', ' + lng,
					zoom:         that.map.getZoom(),
					provider:     'google',
					addressParts: [ lat + ', ' + lng ]
				};

				that.model.setValue(new_field_value);

				// save the position of the marker
				that.model.google_position = new_field_value.latLng;

				that.infoWindow.setContent( lat + ', ' + lng );
			});
		},

		/**
		 * Clears the location.
		 */
		clearLocation: function() {
			// Google Maps
			if (this.marker) {
				this.marker.setMap(null);
			}
			if (this.infoWindow) {
				this.infoWindow.close();
			}
		
			// Leaflet
			if (this.L_marker) {
				this.L_map.removeLayer(this.L_marker);
				this.L_marker = undefined;
			}
			if (this.L_infoWindow) {
				this.L_map.closePopup(this.L_infoWindow);
				this.L_infoWindow = undefined;
			}
		
			// Limpiar el modelo
			this.model.setValue(false);
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
				$map, center, zoom, default_position, initial_map_position;
		
			// Initialize the map
			$map = this.$el.find('.uf-map-leaflet .uf-map-ui div');
		
			$map.css({
				height: this.model.get('height') || 400
			});
		
			if (value && typeof value == 'object' && value.hasOwnProperty('latLng')) {
				center = [value.latLng.lat, value.latLng.lng];
				zoom = parseInt(value.zoom);
			} else {
				default_position = { lat: 40.416775, lng: -3.703790, zoom: 8 };
				initial_map_position = this.model.get( 'initial_map_position' ) || default_position;
				center = [initial_map_position.lat, initial_map_position.lng];
				zoom = initial_map_position.zoom;
			}
		
			this.L_map = L.map($map.get(0)).setView(center, zoom);
		
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '© OpenStreetMap contributors'
			}).addTo(this.L_map);
		
			// Add Geocoder control
			var geocoder = L.Control.geocoder({
				defaultMarkGeocode: false, // Prevent automatic marker placement
				collapsed: false // Keep the search box always visible
			})
			.on('markgeocode', function(e) {
				var latLng = e.geocode.center,
					new_field_value;
		
				// Center the map on the selected location
				that.L_map.setView(latLng, 17);
		
				// Add or update the marker
				if (that.L_marker) {
					that.L_marker.setLatLng(latLng);
				} else {
					that.L_marker = L.marker(latLng, { draggable: true }).addTo(that.L_map);
				}
		
				// Add or update the popup
				if (that.L_infoWindow) {
					that.L_infoWindow
						.setLatLng(latLng)
						.setContent(e.geocode.name)
						.openOn(that.L_map);
				} else {
					that.L_infoWindow = L.popup({
						offset: [0, -35],
						autoClose: false,
						closeOnClick: false
					})
					.setLatLng(latLng)
					.setContent(e.geocode.name)
					.openOn(that.L_map);
				}
		
				// Update the model
				new_field_value = {
					latLng: { lat: latLng.lat, lng: latLng.lng },
					address: e.geocode.name,
					zoom: that.L_map.getZoom(),
					provider: 'leaflet',
					addressParts: [latLng.lat + ', ' + latLng.lng]
				};
				that.model.setValue(new_field_value);
				// save the position of the marker
				that.model.leaflet_position = new_field_value.latLng;
			})
			.addTo(this.L_map);
		
			if (value && typeof value == 'object') {
				that.addLeafletElements();
			}
		},

		addLeafletElements: function() {
			var that = this;
		
			// Limpiar elementos antiguos
			if (typeof that.L_infoWindow !== 'undefined') {
				that.L_map.closePopup(that.L_infoWindow);
			}
			if (typeof that.L_marker !== 'undefined') {
				that.L_map.removeLayer(that.L_marker);
			}
		
			// Obtener el valor actual del modelo
			var value = that.model.getValue(),
				mapCenter = that.L_map.getCenter(),
				latLng = value && value.hasOwnProperty('latLng') ? [value.latLng.lat, value.latLng.lng] : [mapCenter.lat, mapCenter.lng];

			// Agregar un marcador
			that.L_marker = L.marker(latLng, { draggable: true }).addTo(that.L_map);
		
			// Mostrar un popup
			that.L_infoWindow = L.popup({
					offset: [0, -35], // Desplaza el popup 'X' pixeles hacia arriba
					autoClose: false, // Evita que el popup se cierre automáticamente
            		closeOnClick: false // Evita que se cierre al hacer clic en el mapa
				})
				.setLatLng(latLng)
				.setContent(value && value.address ? value.address : latLng.join(', '))
				.openOn(that.L_map);

			// Manejar el evento de cierre del popup
			that.L_infoWindow.on('remove', function() {
				that.clearLocation();
			});
		
			// Manejar el evento de arrastrar el marcador
			that.L_marker.on('dragend', function(event) {
				var latLng = event.target.getLatLng(),
					lat = latLng.lat,
					lng = latLng.lng,
					new_field_value;
		
				// Actualizar el modelo con la nueva ubicación
				new_field_value = {
					latLng: { lat: parseFloat(lat), lng: parseFloat(lng) },
					address: lat + ', ' + lng,
					zoom: that.L_map.getZoom(),
					provider: 'leaflet',
					addressParts: [lat + ', ' + lng]
				}
				that.model.setValue(new_field_value);
				// save the position of the marker
				that.model.leaflet_position = new_field_value.latLng;
		
				// Actualizar el contenido del popup
				that.L_infoWindow
					.setLatLng(latLng)
					.setContent(lat + ', ' + lng)
					.openOn(that.L_map);
			});
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
