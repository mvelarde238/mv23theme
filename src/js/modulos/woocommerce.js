(function($,c){      
    $(function() {
        var is_checkout = $('body').hasClass('woocommerce-checkout'),
            is_cart = $('body').hasClass('woocommerce-cart'),
            is_single = $('body').hasClass('single-product');

        // ****************************************************************************************************
        // ****************************************************************************************************

        function show_cart_item_qty(){
            var qty = getCookie('woocommerce_items_in_cart');
            $('.cart-items-qty').remove();
            if(qty != ""){
                $('.show-cart-items-qty').append(' <span class="cart-items-qty">'+qty+'</span>');
            }
        }
        show_cart_item_qty();

        $( document.body ).on( 'added_to_cart', function(event, fragments, cart_hash, btn){
            show_cart_item_qty();
        });
        $( document.body ).on( 'removed_from_cart', function(event, fragments, cart_hash, btn){
            show_cart_item_qty();
        });

        if (!is_checkout && !is_cart) {
            $('.open-minicart>a').attr('data-activates','minicart-sidenav');
            $('.open-minicart>a').sideNav({ 
                menuWidth: MV23_GLOBALS.minicart_sidenav_width, 
                edge: MV23_GLOBALS.minicart_sidenav_position, 
                closeOnClick: false, 
                draggable: false
            });

            if( MV23_GLOBALS.open_minicart_on_add_to_cart ){
                $( document.body ).on( 'added_to_cart', function(event, fragments, cart_hash, btn){
                    $('.open-minicart>a').trigger('click');
                });
            }
        }

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);
