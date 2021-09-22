(function($,c){      
    $(function() {
 
        // var styleArray = [ { "stylers": [ { "hue": "#36A9E1" }, { "saturation": -2 }, { "gamma": 0.7 } ] } ];

        var map, bounds, circle,
            lugares = [],
            localesItems = $('.locales-list__item');
	
        $.each( localesItems, function(i,e){
        	var lat = parseFloat(e.dataset.lat),
                lng = parseFloat(e.dataset.lng),
                id = e.id,
                icon = e.dataset.icon;

        	lugares.push( { lat:lat, lng:lng, icon:icon, id:id } );
        });

	
        function initMap() {
        	map = new google.maps.Map(document.getElementById('locales-list__map'), {});
        	bounds = new google.maps.LatLngBounds();
        	map.markers = [];
	
        	$.each(lugares, function(i,e){
        		var marker = new google.maps.Marker({
      				map: map,
      				position: {lat:e.lat, lng:e.lng},
                    icon: e.icon,
                    elementID : e.id
      			})

      			map.markers[i] = marker;
	
      			bounds.extend(marker.position);
	
      			marker.addListener('click', function() {
					map.setCenter(marker.getPosition());
					map.setZoom(17);
                    set_status('showing');
                    $('#'+marker.elementID).addClass('is-active');
				});
        	});
	
            ver_todas();
			// map.setOptions({styles: styleArray});
    	} 
	
        function ver_todas(){
        	map.fitBounds(bounds);
        }

        function set_status(status){
            $('.locales-list').attr('data-status',status);
        }
        
        if ( document.getElementById('locales-list__map') ) {
        	initMap();  
    
        	$('.locales-list__item h6').click(function(ev){
        		ev.preventDefault();
                $('.locales-list__item').removeClass('is-active');
                $(this).parent().addClass('is-active');
                var parent = $(this).parent();
        		var lat = parseFloat($(parent).attr('data-lat')),
            		lng = parseFloat($(parent).attr('data-lng'));
    			map.setZoom(17);
    			map.setCenter({lat:lat, lng:lng});
                set_status('showing');
            }); 
    
            $('.locales-list__view-all').click(function(ev){
                ev.preventDefault();
                $('.locales-list__item').removeClass('is-active');
                ver_todas();
                set_status('initial');
        	}); 
        }

    	// -----------------------------------------------------------------------------------------------
    	// -----------------------------------------------------------------------------------------------
  });

})(jQuery,console.log);