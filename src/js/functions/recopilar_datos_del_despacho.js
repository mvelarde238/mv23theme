function recopilar_datos_del_despacho(){
	var options = {};

	options.metodo_de_despacho = $('input[name=shipping_option]:checked').val();
	options.address = $('input[name=billing_address_1]').val();
	options.address_is_valid = localStorage.getItem('existeDespacho');
	options.fecha_de_despacho = $('#shipping_date').val();

	return options;
}

function validar_despacho(on_error,on_success){
	var options = recopilar_datos_del_despacho();

	$.ajax({
	    type: 'POST',
	    dataType : "json",
	    url: MV23_GLOBALS.ajaxUrl,
	    data : { action:'validar_despacho_checkout', options:options },
	    beforeSend: function(){
	        $('.checkout-multistep').attr('data-status','loading');
	        $('.error-msgs').empty();
	    },
	    success: function(response){
	        $('.checkout-multistep').attr('data-status','');

	        if (response.status=="error") {
	        	if (typeof on_error === 'function') on_error(response);

	        	if (response.errores.length > 0) {
	        		for (var i = 0; i < response.errores.length; i++) {
	        			$('.error-msgs').append('<p>'+response.errores[i]+'</p>');
	        		}
	        	}
	        }

	        if (response.status=="success") {
	        	if (typeof on_success === 'function') on_success(response);
	        }
	    }
	});
}