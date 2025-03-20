var styleArray = [ { "stylers": [ { "hue": "#19511B" }, { "saturation": 0 }, { "gamma": 0.7 } ] } ];

(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

		var mapas = document.getElementsByClassName('map__gmap'); 
		if (mapas.length){
	
        	function initMap(element) {
				var map_id = element.id,
					lat = parseFloat(element.dataset.lat),
                    lng = parseFloat(element.dataset.lng),
                    zoom = parseFloat(element.dataset.zoom),
				    icon = element.dataset.icon,
					provider = element.dataset.provider,
					location = [lat,lng],
                    map = null,
					infoContent = $(element).find('.infowindow').html();

				if(provider == 'leaflet'){
					map = L.map(map_id).setView(location, zoom);
	
					L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
						attribution: '© OpenStreetMap contributors'
					}).addTo(map);
	
					var marker = L.marker(location).addTo(map);
	
					if(infoContent){
						marker.bindPopup(infoContent).openPopup();
					}
	
					if(icon){
						// marker.setIcon(L.icon({
							// iconUrl: icon,
							// iconSize: [38, 95]
						// }));
					}
				}

				if(provider == 'google'){
					map = new google.maps.Map(element, { zoom:zoom, center: {lat:lat, lng:lng} });
					var marker_options = { map: map, position: {lat:lat, lng:lng} };
	
					if(icon) marker_options.icon = icon;
					var marker = new google.maps.Marker(marker_options)
					// map.setOptions({styles: styleArray});
	
					if(infoContent){
						var infoWindow = new google.maps.InfoWindow({map: map});
						infoWindow.setPosition(marker_options.position);
						infoWindow.setContent( infoContent )
					}
				}

				element.mapObject = map;
    		} 
	
			$.each( mapas, function(i,e){
				initMap(e);
			});
		}
    });
})(jQuery,console.log); 