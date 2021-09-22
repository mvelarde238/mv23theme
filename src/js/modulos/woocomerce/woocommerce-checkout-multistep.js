(function($,c){      
    $(function() {
        if (is_checkout) {
            // ****************************************************************************************************
            // NEXT STEP
            // ****************************************************************************************************

            var $items = $('.v23-togglebox__item'),
                $next_step = $('.next-step');

            $.each($next_step, function(index,element){
                $(element).click(function(ev){
                    ev.preventDefault();
    
                    var $nextbox = $items.eq(index+1),
                        $currentbox = $items.eq(index);

                    var currentbox_id = $currentbox.attr('id'),
                        nextbox_id = $nextbox.attr('id');

                    if ( currentbox_id == 'billing-box') {
                    	var billingValidation = verificar_billing();
                    	if ( billingValidation.status == "error" ) {
                    	    for (var i = 0; i < billingValidation.failedInputs.length; i++) {
                    	        $(billingValidation.failedInputs[i]).addClass('invalid');
                    	        myscrollTo(document.documentElement, ($(billingValidation.failedInputs[0]).offset().top - 120), 800);
                    	    }
                            return false;
                    	} else {
                            open_next(nextbox_id,currentbox_id);
                    	}
                    } else if ( currentbox_id == 'shipping-box') {
                        var shippingValidation = verificar_shipping();
                        if ( shippingValidation.status == "error" ) {
                            alert(shippingValidation.message);
                            return false;
                        } else {
                            open_next(nextbox_id,currentbox_id);
                        }
                    } else {   
                        open_next(nextbox_id,currentbox_id);
                    }

                    if ( nextbox_id == 'shipping-box') {
                        validar_despacho(say_no,say_yes);
                    }
                });
            });

            function say_yes(){ console.log('yes') }
            function say_no(){ console.log('no') }
    
            function open_next(nextbox,currentbox){
            	$('.v23-togglebox__btn[data-boxid="#'+nextbox+'"]').click().attr('data-disabled','false');
                $('.v23-togglebox__btn[data-boxid="#'+currentbox+'"]').attr('data-disabled','false');
            }
    
            // ****************************************************************************************************
            // Verificar si el boton next-step deberia continuar al siguiente paso
            // ****************************************************************************************************
    
            function verificar_billing(){
                $('.invalid').removeClass('invalid');
                var response = { status:'error', failedInputs:[] };
                // -------------------------------------------------------------------------------------
                // campos requeridos
                // -------------------------------------------------------------------------------------
                var campos_billing = [
                    '#billing_first_name',
                    '#billing_last_name',
                    '#billing_phone',
                    '#billing_email',
                    '#billing_address_1',
                    '#billing_address_2',
                    // '#billing_city',
                    // '#billing_state'
                ];

                if (MV23_GLOBALS.userIsLoggedIn == '') {
                    campos_billing.push('#account_password');
                }

                for (var i = 0; i < campos_billing.length; i++) {
                    var valor = $(campos_billing[i]).val();
                    if (valor == '') response.failedInputs.push(campos_billing[i]); 
                }
                
                if ( response.failedInputs.length <= 0 ) response.status = "success";
                return response;
            }

            // ****************************************************************************************************
            // ****************************************************************************************************

            function verificar_shipping(){
                var response = { status:'error', message:'No pasó la validación.' };
                var val = $('input[name="shipping_option"]:checked').val();

                if (val == undefined) response.message = 'Elige una de las opciones de despacho.';
                if (val == 'retiro_en_local') response.status = "success";
                if (val == 'despacho_a_domicilio') {
                    var fecha = $('input[name="shipping_date"]').val();
                    if(fecha == '') {
                        response.message = "Elige una fecha y hora de despacho.";
                    } else {
                        var hora = $('input[name="time_block"]:checked').val();
                        if(hora == undefined) {
                            response.message = "Elige una hora de despacho";
                        } else{
                            response.status = "success";
                        }
                    }
                }

                return response;
            }
    
            // ****************************************************************************************************
            // ****************************************************************************************************
        }
	});
})(jQuery,console.log);