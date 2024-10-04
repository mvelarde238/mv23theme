(function( $ ){

	var field    = UltimateFields.Field,
		mapField = field.Map_Multiple = {};

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
			var that = this;

			if( that.apiLoaded() ) {
				setTimeout( that.renderInput.bind( this ), 10 );
			} else {
				that.showErrorStatus();
			}
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
				tmpl = UltimateFields.template( 'map-base' ),
				value = this.model.getValue(),
				$map, $input, center, zoom;

			this.markers = [];
			this.flightPath = null;

			// Add the structure.
			that.$el.html( tmpl() );

			// Initialize the map
			$map = this.$el.find( '.uf-map-ui div' );

			$map.css({
				height: this.model.get( 'height' ) || 400
			});

			if( value && typeof value == 'object' ) {
				center = new google.maps.LatLng( value.latLng.lat, value.latLng.lng );
				zoom   = parseInt( value.zoom );
			} else {
				center = new google.maps.LatLng( -33.434757, -70.664902 );
				zoom   = 8;
			}

			this.map = new google.maps.Map( $map.get( 0 ), {
				center: center,
				zoom: zoom
			});

			google.maps.event.addListener(this.map, 'dragend', function() { that.saveValues(); } );

			// create initial markers
			if (value.coordenadas && value.coordenadas.length > 0) {
			    value.coordenadas.forEach(function(coordenada) {
    				var new_marker = that.createMarker(coordenada);
			    	that.markers.push(new_marker);
    			});

    			that.unirLosPuntos();
			}

			// Add autocomplete
			$input = this.$el.find( '.uf-map-input' ).on( 'keydown', function( e ) {
				if( e.which == 13 ) {
					e.preventDefault();
				}
			});
			this.autocomplete = new google.maps.places.Autocomplete( $input.get( 0 ) );

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
			});

			// When the field is toggled, force a map resize event
			this.model.on( 'change:visible', function() {
				setTimeout(function(){
					google.maps.event.trigger( that.map, 'resize' );
				}, 50 );
			});


			// create marker on click
			google.maps.event.addListener(that.map, 'click', function(event) {
			    var marker = that.createMarker(event.latLng);
			    that.markers.push(marker);
			    that.unirLosPuntos();
			});

			// add a button to clear markers
			function clearMarker(clearMarkersBtn, map){
        	    clearMarkersBtn.className = 'button button-primary button-large';
        	    clearMarkersBtn.style.margin = '10px';
        	    clearMarkersBtn.innerHTML = 'Limpiar Marcadores';
	        	clearMarkersBtn.addEventListener('click', function() {
    				that.limpiarLinea();
	        		that.markers.forEach(function(marker) {
    					marker.setMap(null);
    				});
    				that.markers = [];
	        	});
        	}

			var clearMarkersBtn = document.createElement('div');
        	var clearMarker = new clearMarker(clearMarkersBtn, that.map);
        	clearMarkersBtn.index = 1;
			that.map.controls[google.maps.ControlPosition.TOP_CENTER].push(clearMarkersBtn);
		},

		createMarker : function(position){
			var that = this;
			var m = new google.maps.Marker({
				position: position, 
				map: that.map,
				draggable: true,
				icon: {
					path: google.maps.SymbolPath.CIRCLE,
					scale: 8,
				},
			});

			that.handleDrag(m);
			that.handleClearing(m);

			return m;
		},

		handleDrag : function(marker){
			var that = this;
			google.maps.event.addListener( marker, 'dragend', function( event ) {
				that.limpiarLinea();
    			that.unirLosPuntos();
			});
		},

		handleClearing : function(marker){
			var that = this;
			marker.addListener('dblclick', function(){
				marker.setMap( null );
				that.limpiarLinea();

				// eliminar la referencia en el array
				that.markers.forEach(function(savedmarker, index) {
    				if (savedmarker == marker) {
    					that.markers.splice(index, 1);
    				}
    			});

    			that.unirLosPuntos();
			});
		},

		/**
		 * UNIR LOS PUNTOS
		 */
		unirLosPuntos : function(){
			var that = this;

			that.limpiarLinea();

			if (that.markers.length > 1) {
			    var coordenadas = [];

			    that.markers.forEach(function(marker) {
    				coordenadas.push( {lat:marker.getPosition().lat(), lng:marker.getPosition().lng()} );
    			});

			    that.flightPath = new google.maps.Polyline({
					path: coordenadas,
					geodesic: true,
					strokeColor: '#0000ff',
					strokeOpacity: .8,
					strokeWeight: 6
				});

				that.flightPath.setMap(that.map);
			}

			that.saveValues();
		},

		/**
		 * LIMPIAR LA LINEA
		 */
		limpiarLinea : function(){
			var that = this;
			if (that.flightPath != null) {
				that.flightPath.setMap(null);
			}
		},

		/**
		 * GUARDAR VALORES
		 */
		saveValues : function(address=''){
			var that = this,
				coordenadas = [];

			if (that.markers.length > 0) {
			    that.markers.forEach(function(marker) {
    				coordenadas.push( {lat:marker.getPosition().lat(), lng:marker.getPosition().lng()} );
    			});
			}

			var dacenter = that.map.getCenter().toUrlValue();
  			var latlngStr = dacenter.split(',', 2);

			that.model.setValue({
				latLng:       { lat: latlngStr[0], lng: latlngStr[1] },
				coordenadas:  coordenadas,
				address:      address,
				zoom:         that.map.getZoom(),
			});
		},

		/**
		 * Shows a message that the API couldn't be loaded.
		 */
		showErrorStatus: function() {
			this.$el.append( UltimateFields.template( 'map-error' )() );
		},

		/**
		 * Focuses the address input.
		 */
		focus: function() {
			this.$el.find( '.uf-map-input' ).focus();
		}
	});

})( jQuery );
