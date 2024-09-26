(function($,c){      
    $(function() {
        var is_checkout = $('body').hasClass('woocommerce-checkout'),
            is_cart = $('body').hasClass('woocommerce-cart'),
            is_single = $('body').hasClass('single-product');

        // ****************************************************************************************************
        // ADD TOGGLE BUTTON TO ITEMS IN PRODUCTS CATEGORY WIDGET
        // ****************************************************************************************************
        
        $('.widget_product_categories .children').hide();
        $('<button class="toggle-submenu"></button>').insertBefore( $('.widget_product_categories .children') );

        $('.widget_product_categories').on('click','.toggle-submenu',function(){
            $(this).parent().children('.children').slideToggle();
        });

        // ****************************************************************************************************
        // SHOW CART ITEM QUANTITY
        // ****************************************************************************************************

        function show_cart_item_qty(){
            $('.cart-items-qty').remove();
            
            $.ajax({
                url: MV23_GLOBALS.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_cart_quantity',
                },
                success: function(response) {
                    if(response != "" && response != 0){
                        $('.show-cart-items-qty').append(' <span class="cart-items-qty">'+response+'</span>');
                    }
                }
            });
        }
        
        show_cart_item_qty();

        // Escuchar el evento 'added_to_cart' para actualizar el fragmento del carrito
        $(document.body).on('added_to_cart wc_fragments_refreshed wc_fragments_loaded', function() {
            show_cart_item_qty();
        });

        if( MV23_GLOBALS.open_minicart_on_add_to_cart ){
            $( document.body ).on( 'added_to_cart', function(event, fragments, cart_hash, btn){
                var event = new Event('theme_open_minicart_on_product_added');
                document.body.dispatchEvent(event);
            });
        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);
