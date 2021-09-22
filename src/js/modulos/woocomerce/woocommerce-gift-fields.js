(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ****************************************************************************************************

        var GiftFieldsSlider = $('.gift-fields__slider');

        if(GiftFieldsSlider.length){
            for (var i = 0; i < GiftFieldsSlider.length; i++) {
                tns({
                    container: GiftFieldsSlider[i], slideBy: 'page', autoplayButtonOutput: false, loop: false,
                    gutter: 15, speed: 650, autoplayButton: false, autoplay: false, controlsPosition: 'bottom',
                    controlsText: ['<i class="material-icons">arrow_back</i>','<i class="material-icons">arrow_forward</i>'],
                    autoHeight: false, nav: false, mouseDrag: true, controls: true,
                    responsive: { 1024: { items: 3}, 768: { items: 2}, 1:{ items: 1} }
                });
            }
        }

        // ****************************************************************************************************
        // ****************************************************************************************************

        var timeout = null;

        $('#is_a_gift, input[name=card], #card_message').on('keyup change paste blur', function(ev){
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                send_ajax_call();
            }, 800);
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        function send_ajax_call(){
            var is_a_gift = $('#is_a_gift').prop('checked'),
                card = $('input[name=card]:checked').val(),
                card_message = $('#card_message').val();

            card = (card != undefined) ? card : '';

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data : { action:'update_gift_options', card:card, card_message:card_message, is_a_gift:is_a_gift },
                beforeSend: function(){},
                success: function(response){
                    $('body').trigger( 'update_checkout' );
                }
            });
        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);