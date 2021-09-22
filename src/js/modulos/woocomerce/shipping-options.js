(function($,c){      
    $(function() {
        var is_inicio = $('body').hasClass('home');

        // ****************************************************************************************************
        // ****************************************************************************************************
        $('.shipping-options__tab li').click(function(){
            $('#shipping-options').attr('data-status','active')
            $('.shipping-options__map-wrapper').hide();
            var key = $(this).attr('data-key');
            $('.shipping-options__form-box').hide();
            $('.shipping-options__msg').removeClass('active');
            $('#'+key+'-box').show();
            $('#'+key+'-msg').addClass('active');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            $('label[for="direccion"]').addClass('active');

            if (key=='retiro-en-local') { send_session_data( {shipping_option:'retiro_en_local'} ) }
            if (key=='despacho-a-domicilio') { send_session_data( {shipping_option:'despacho_a_domicilio'} ) }
        });
        $('.shipping-options__overlay').click(function(){
            hide_shipping_options();
        });
        $('.shipping-options__close').click(function(){
            hide_shipping_options();
        });
        function hide_shipping_options(){
            $('#shipping-options').attr('data-status','initial')
            $('.shipping-options__form-box').hide();
            $('.shipping-options__map').hide();
        }
        $('.escoger-otro').click(function(){            
            var key = $(this).attr('data-key');
            $('#shipping-options').attr('data-status','active')
            $('#'+key+'-box').show();
            $('.shipping-options__tab li').removeClass('active');
            $('.shipping-options__tab li[data-key="'+key+'"]').addClass('active');
            $('label[for="direccion"]').addClass('active');
        });

        $('.gm-button').click(function(ev){
            ev.preventDefault();
            var mapEle = $(this).attr('data-map');
            $(mapEle).show();
            ver_todas();
        });

        // ****************************************************************************************************
        // RETIRO EN LOCAL
        // ****************************************************************************************************

        var $products_box = $('.products-box'),
            localesMap, bounds, 
            lugares = MV23_GLOBALS.locales,
            $retiro_en_local_form = $('.retiro-en-local-form'),
            $retiro_en_local_box = $retiro_en_local_form.parent();

        $retiro_en_local_form.submit(function(event){
            event.preventDefault();

            var form = $(this),
                formInputs = form.serializeObject();
                formInputs['action'] = "shipping_options";

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data : formInputs,
                beforeSend: function(){
                    $retiro_en_local_box.attr('data-status','loading');
                    limpiar_products_box();
                },
                success: function(response){
                    $retiro_en_local_box.attr('data-status','');

                    if(response.status == "success") {
                        $('#retiro-en-local-msg span').html(response.local.name);
                        hide_shipping_options();
                        $("html, body").animate({ scrollTop: ($products_box.offset().top) - 90 }, 300);
                    }

                    if(response.status == "error") {
                        alert(response.message);
                    }
                }
            });
        });

        function initLocalesMap() {
            localesMap = new google.maps.Map(document.getElementById('locales-map'), {});
            bounds = new google.maps.LatLngBounds();
            localesMap.markers = [];
    
            $.each(lugares, function(i,e){
                var marker = new google.maps.Marker({
                    map: localesMap,
                    position: {lat:e.lat, lng:e.lng},
                    title : e.title,
                    localID : e.id
                })

                localesMap.markers[i] = marker;
    
                bounds.extend(marker.position);
    
                marker.addListener('click', function() {
                    $('.shipping-options__map-wrapper').hide();
                    $('#local option[value="'+marker.localID+'"]').prop('selected', true);
                });
            });
        } 

        if(is_inicio) initLocalesMap(); 

        function ver_todas(){
            localesMap.fitBounds(bounds);
            userMap.fitBounds(bounds);
        }

        // ****************************************************************************************************
        // DESPACHO A DOMICILIO
        // ****************************************************************************************************
        var $despacho_msg = $('#despacho-a-domicilio-msg span'),
            $direccion_input = $('#direccion'),
            $direccion_detalle = $('#direccion_detalle'),
            $despacho_a_domicilio_box = $('#despacho-a-domicilio-box');

        function send_google_direction(address){
            var address_details = $direccion_detalle.val();
            $('#despacho-a-domicilio-error-msg').hide();

            localStorage.setItem('address', address);
            localStorage.setItem('address_details', address_details);
            localStorage.setItem('existeDespacho', true);

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data : { action: "shipping_options", user_option:"despacho-a-domicilio", direccion:address, direccion_detalle:address_details },
                beforeSend: function(){ 
                    $despacho_a_domicilio_box.attr('data-status','loading'); 
                },
                success: function(response){ 
                    $despacho_a_domicilio_box.attr('data-status',''); 
                }
            });
        }

        function get_address_complete(address){
            var address_details = $direccion_detalle.val();
            var address_complete = address + ( (address_details != '') ? ' | '+address_details : '' );
            return address_complete;
        }

        function reset_address_data(){
            $direccion_input.val('');
            $direccion_detalle.val('');
            $despacho_msg.html('');
            localStorage.setItem('address','');
            localStorage.setItem('address_details','');
            localStorage.setItem('existeDespacho', false);
            $('#despacho-a-domicilio-error-msg').show();
        }
    
        if (is_inicio) {

            // ****************************************************************************************************
            // AUTOCOMPLETE INPUT
            // ****************************************************************************************************
            var options = { componentRestrictions: {country: 'CL'} };
            var direccionBox = new google.maps.places.Autocomplete( document.getElementById('direccion'), options );
            google.maps.event.addListener( direccionBox, 'place_changed', function() {
                var place = direccionBox.getPlace();

                if( ! place.geometry ) {
                    return;
                }

                var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()),
                    verificarDespacho = verificar_despacho(latlng);

                if (verificarDespacho.status == 'success') {
                    send_google_direction(place.formatted_address);
                    $despacho_msg.html( get_address_complete(place.formatted_address) );
                } else {
                    if( verificarDespacho.key == 'fixedMsg' ) alert(verificarDespacho.msg);
                    if( verificarDespacho.key == 'noExisteDespacho' ) alert('No tenemos despacho para la ubicación seleccionada.');
                    reset_address_data();
                }
            });

            // ****************************************************************************************************
            // OBTENER COORDENADAS 
            // ****************************************************************************************************
            $('#get-address').click(function(ev){
                ev.preventDefault();
    
                var dacenter = userMap.getCenter().toUrlValue(),
                    latlngStr = dacenter.split(',', 2),
                    latlng = new google.maps.LatLng(latlngStr[0], latlngStr[1]),
                    verificarDespacho = verificar_despacho(latlng);

                if (verificarDespacho.status == 'success') {
                    geocoder.geocode({'location': latlng}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                var address = results[0].address_components[1].long_name + ' ' + results[0].address_components[0].long_name + ', ' +  results[0].address_components[2].long_name;
                                send_google_direction(address);
                                $direccion_input.val(address);
                                $despacho_msg.html( get_address_complete(address) );
                            } else {
                                window.alert('No results found');
                            }
                        } else {
                            window.alert('Geocoder failed due to: ' + status);
                        }
                        $('.shipping-options__map-wrapper').hide();
                    });
                } else {
                    if( verificarDespacho.key == 'fixedMsg' ) alert(verificarDespacho.msg);
                    if( verificarDespacho.key == 'noExisteDespacho' ) alert('No tenemos despacho para la ubicación escogida.');
                    reset_address_data();
                }
            });

            // ****************************************************************************************************
            // LOS INPUTS CAMBIAN DE FORMA MANUAL
            // ****************************************************************************************************
            var timeout = null;

            $('.verificar-direccion').on('keyup change paste blur', function(ev){
                var that = this;
                clearTimeout(timeout);
    
                timeout = setTimeout(function () {
                    var address = $direccion_input.val();

                    if (address!='') {
                        geocoder.geocode( { 'address': address}, function(results, status) {
                            if (status == 'OK') {
                                var verificarDespacho = verificar_despacho(results[0].geometry.location);
    
                                if (verificarDespacho.status == 'success') {
                                    send_google_direction(address);
                                    $despacho_msg.html( get_address_complete(address) );
                                } else {
                                    if( verificarDespacho.key == 'fixedMsg' ) alert(verificarDespacho.msg);
                                    if( verificarDespacho.key == 'noExisteDespacho' ) alert('No tenemos despacho a esa dirección.');
                                    reset_address_data();
                                }
                            } else {
                                alert('Geocode was not successful for the following reason: ' + status);
                            }
                        });
                    }
                }, 1000);
            });

        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);