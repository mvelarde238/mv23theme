(function($,c){      
    $(function() {
        if(!MV23_GLOBALS.woocommerce_is_active) return;

        // var is_checkout = $('body').hasClass('woocommerce-checkout'),
        //     is_cart = $('body').hasClass('woocommerce-cart'),
        //     is_single = $('body').hasClass('single-product');

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

(function($,c){
    // ****************************************************************************************************
    // Handle dynamic content loaded asynchronously for WooCommerce product profiles within modals
    // ****************************************************************************************************
    $(document.body).on(
        'oce_dynamic_content_loaded',
        function(event) {
            const modal = event.detail.modal;
            const is_woocommerce = $(modal).find('.woocommerce-product-gallery').length;
            if ( is_woocommerce ) {
                // add woo classes to modal-content
                $(modal).find('.modal-content').addClass('woocommerce woocommerce-page woocommerce-js');

                // Fix WooCommerce product gallery display within modal
                $(modal).find('.woocommerce-product-gallery').css('opacity',1);
                $(modal).find('.woocommerce-product-gallery').find('a').attr('class','zoom');
                window.mv23RefreshLightbox();

                // Initialize WooCommerce tabs within the modal (not working)
                // $(modal).find( '.wc-tabs-wrapper, .woocommerce-tabs' ).trigger( 'init' ); // not working
                // $(modal).find( '.wc-tabs-wrapper, .woocommerce-tabs' ).wc_tabs(); // not working
                $(modal).find('.wc-tabs-wrapper, .woocommerce-tabs').each(function() {
                    const $tabsWrapper = jQuery(this);
                    const $wrapper = $tabsWrapper;
        
                    // 1. Ocultar todos los paneles excepto el activo por defecto
                    $wrapper.find('.wc-tab, .panel:not(.panel.wc-tab)').hide();
        
                    // Encontrar cuál es la pestaña activa inicial
                    const $activeTab = $wrapper.find('ul.tabs li.active a');
                    if ($activeTab.length) {
                        $wrapper.find($activeTab.attr('href')).show();
                    } else {
                        // Si no hay ninguna activa por defecto, activar la primera
                        $wrapper.find('ul.tabs li:first-child').addClass('active');
                        $wrapper.find('.wc-tab:first, .panel:not(.panel.wc-tab):first').show();
                    }

                    // 2. Crear el listener para los clics en las pestañas (eliminando duplicados previos)
                    $wrapper.off('click', 'ul.tabs li a').on('click', 'ul.tabs li a', function(event) {
                        event.preventDefault();

                        const $clickedTab = jQuery(this);
                        const $currentWrapper = $clickedTab.closest('.wc-tabs-wrapper, .woocommerce-tabs');

                        // Cambiar clases de las pestañas
                        $currentWrapper.find('ul.tabs li').removeClass('active');
                        $clickedTab.closest('li').addClass('active');

                        // Alternar visibilidad de los paneles de contenido
                        $currentWrapper.find('.wc-tab, .panel:not(.panel.wc-tab)').hide();
                        $currentWrapper.find($clickedTab.attr('href')).show();
                    });
                });
            }
        }
    );
})(jQuery,console.log);