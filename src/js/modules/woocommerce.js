(function($,c){      
    $(function() {
        var is_checkout = $('body').hasClass('woocommerce-checkout'),
            is_cart = $('body').hasClass('woocommerce-cart'),
            is_single = $('body').hasClass('single-product');

        // ****************************************************************************************************
        // ****************************************************************************************************

        function get_items_in_cart_qty(cart_fragments){
            var TotalCount = 0;

            if(cart_fragments != null){
                var cartCount = cart_fragments['div.widget_shopping_cart_content'].split('<span class=\"quantity\">');
                for (let index = 1; index < cartCount.length; index++) {
                    var item = cartCount[index];
                    var ItemCount = item.split(' &times;')[0];
                    TotalCount += parseInt(ItemCount);
                }
            } else {
                TotalCount = MV23_GLOBALS.items_in_cart;
            }

            return TotalCount;
        }

        function show_cart_item_qty(cart_fragments = null){
            // var qty = getCookie('woocommerce_items_in_cart'); // boolean cookie :/
            var qty = get_items_in_cart_qty(cart_fragments);

            $('.cart-items-qty').remove();
            if(qty != "" && qty != 0){
                $('.show-cart-items-qty').append(' <span class="cart-items-qty">'+qty+'</span>');
            }
        }
        show_cart_item_qty();

        $( document.body ).on( 'added_to_cart', function(event, fragments, cart_hash, btn){
            show_cart_item_qty(fragments);
        });
        $( document.body ).on( 'removed_from_cart', function(event, fragments, cart_hash, btn){
            show_cart_item_qty(fragments);
        });

        if (!is_checkout && !is_cart) {
            $('.open-minicart>a').attr('data-target','minicart-sidenav');
            $('#minicart-sidenav').sidenav({ 
                edge: MV23_GLOBALS.minicart_sidenav_position, 
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
