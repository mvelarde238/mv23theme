(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ****************************************************************************************************

        var $products_box = $('.products-box');
        var $btns = $('.products-box__accordion-btn');

        $btns.click(function(event){
            event.preventDefault();
            var $that = $(this), 
                id = $that.attr('data-id'),
                $parent = $that.parent(),
                status = $parent.attr('data-status');

            if (status == 'active') {
                limpiar_products_box();
                return;
            }

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data: {
                    action: 'traer_productos',
                    id: id
                },
                beforeSend: function(){
                    limpiar_products_box();
                    $("html, body").animate({ scrollTop: ($that.offset().top) - 90 }, 300);
                    $products_box.attr('data-status','loading');
                    $parent.attr('data-status','loading');
                },
                success: function(response){
                    if(response.status == "success") {
                        $parent.find('.products-box__accordion-item__content').append(response.productos);
                        $products_box.attr('data-status','');
                        $parent.attr('data-status','active');
                        $('.products-box__categories li[data-id='+id+']').addClass('in-view');
                        Waypoint.refreshAll();
                    }
                    if(response.status == "error") {
                        alert(response.message);
                    }
                }
            });
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);