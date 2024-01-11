var styleArray = [ { "stylers": [ { "hue": "#19511B" }, { "saturation": 0 }, { "gamma": 0.7 } ] } ];

(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

		var mapas = document.getElementsByClassName('mapa__gmap'); 
		if (mapas.length){

			var map;
	
        	function initMap(element) {
				var lat = parseFloat(element.dataset.lat),
                    lng = parseFloat(element.dataset.lng),
                    zoom = parseFloat(element.dataset.zoom),
				    icon = element.dataset.icon,
					infoContent = $(element).find('.infowindow').html(),
                    map = new google.maps.Map(element, { zoom:zoom, center: {lat:lat, lng:lng} }),
                    marker_options = { map: map, position: {lat:lat, lng:lng} };

                if(icon) marker_options.icon = icon;
        		var marker = new google.maps.Marker(marker_options)
				// map.setOptions({styles: styleArray});

				if(infoContent){
					var infoWindow = new google.maps.InfoWindow({map: map});
					infoWindow.setPosition(marker_options.position);
					infoWindow.setContent( infoContent )
				}

				element.mapObject = map;
    		} 
	
			$.each( mapas, function(i,e){
				initMap(e);
			});

		}
    });
})(jQuery,console.log); 