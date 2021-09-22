(function($,c){      
    $(function() {
        if (is_checkout) {
            // ****************************************************************************************************
            // USER INTERFACE
            // ****************************************************************************************************
    
            $('input[name=shipping_option]').on('change', function(ev){
                var value = $('input[name=shipping_option]:checked').val();
    
                $('.custom-shipping-fields__option-content').hide();
                $('.custom-shipping-fields__option-content[data-id='+value+']').show();

                send_session_data( {shipping_option:value} );
            });
    
            // ****************************************************************************************************
            // CAMBIA LA FECHA
            // ****************************************************************************************************
    
            // var timeout = null;
    
            // $('#shipping_date').on('change', function(ev){
            //     clearTimeout(timeout);
            //     timeout = setTimeout(function () {
                    
            //     }, 800);
            // });
    
            // ****************************************************************************************************
            // DATEPICKER
            // ****************************************************************************************************
    
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);

            var the_date = null;
        
            var datepickerOptions = {
                // 'altField'        : '#shipping_date',
                'dateFormat'      : 'yy-mm-dd',
                'altFormat'      : 'D',
                'changeMonth'     : false,
                'changeYear'      : false,
                'showButtonPanel' : false,
                'language'        : 'es',
                'minDate'         : 0,
                'maxDate'         : 50,
                onSelect: function(date) {
                    the_date = $(this).datepicker('getDate');
                    $('.time-blocks__item').removeClass('active').find('ul').html('');
                    validar_despacho(date_error,date_success);
                },
            };

            function date_error(response){
                c(the_date);
            }

            var days = ['dom', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab'];

            function date_success(response){
                var no_time_blocks = true;
                var d = new Date(the_date);
                var dayName = days[d.getDay()];
                c(dayName);

                var keys = ['morning','evening','night'];

                $.each(keys,function(count,key){
                    for (var i = 0; i < response.bloques_horarios[key].length; i++) {
                        var time_block = response.bloques_horarios[key][i];
                        if(time_block.days.indexOf(dayName) !== -1){
                            var hora = time_block.start + ' - ' + time_block.end;
                            no_time_blocks = false;
                            $('.time-blocks__'+key).addClass('active').find('ul').append('<li class="option-box"><input type="radio" id="tm'+i+'" name="time_block" value="'+hora+'|'+key+'|'+time_block.precio+'"><label for="tm'+i+'">'+hora+' <span>$'+time_block.precio+'</span></label></li>');
                        }
                    }
                });

                if(no_time_blocks) $('.error-msgs').append('<p>No tenemos horarios de despacho disponibles para el día seleccionado.</p>');
            }
    
            $('#shipping_date').datepicker(datepickerOptions); 

        // ****************************************************************************************************
        // ON CHANGE TIME BLOCK
        // ****************************************************************************************************

        $('.custom-shipping-fields').on('change', 'input[name=time_block]', function(ev){
            var value = $('input[name=time_block]:checked').val();

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data : { action:'update_time_block', key:value, fechaDespacho:$('#shipping_date').val() },
                beforeSend: function(){
                    $('.checkout-multistep').attr('data-status','loading');
                },
                success: function(response){
                    $('body').trigger( 'update_checkout' );
                    $('.checkout-multistep').attr('data-status','');
                }
            });
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
        }
    });
})(jQuery,console.log);