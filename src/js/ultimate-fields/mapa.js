var styleArray = [ { "stylers": [ { "hue": "#19511B" }, { "saturation": 0 }, { "gamma": 0.7 } ] } ];

(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

		var mapas = document.getElementsByClassName('mapa__gmap'); 
		if (mapas.length){

			var map;
	
        	function initMap(element) {
				var lat = parseFloat(element.dataset.lat),
                    lng = parseFloat(element.dataset.lng),
				    icon = element.dataset.icon,
                    map = new google.maps.Map(element, { zoom:17, center: {lat:lat, lng:lng} }),
                    marker_options = { map: map, position: {lat:lat, lng:lng} };

                if(icon) marker_options.icon = icon;
        		var marker = new google.maps.Marker(marker_options)
				// map.setOptions({styles: styleArray});
    		} 
	
			$.each( mapas, function(i,e){
				initMap(e);
			});

		}
    });
})(jQuery,console.log); 