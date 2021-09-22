(function($,c){      
    $(function() {
        
        var initial_url = window.location.href;

        // ****************************************************************************************************
        // ****************************************************************************************************
        $('#producto-modal').modal({ dismissible:true,  opacity:.5,  inDuration:300,  outDuration:200, startingTop: '2%', endingTop: '5%', 
            ready: function(modal, trigger) { $(trigger).css('z-index','initial'); },
            complete: function(){ url_updates(initial_url); }
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
        
        function start_woocommerce_gallery(){
            var galleries = $('.woocommerce-product-gallery');

            if (galleries.length > 0) {
                $.each(galleries, function(i,e){
                    var $first_image = $(e).find('.woocommerce-product-gallery__image:first-child');

                    if ($first_image.length > 0) {
                        var image = $first_image.find('a').attr('href');
                        $(e).css('background-image','url('+image+')');
                    }
                });
    
                $('.woocommerce-product-gallery__image a').click(function(ev){
                    ev.preventDefault();
                    var href = $(this).attr('href');
                    $(this).parents('.woocommerce-product-gallery').css('background-image','url('+href+')');
                });            
            }  
        }

        start_woocommerce_gallery();
 
        // ****************************************************************************************************
        // ****************************************************************************************************

        var $fragment_refresh = {
            url: woocommerce_params.ajax_url,
            type: 'POST',
            data: { action: 'woocommerce_get_refreshed_fragments' },
            success: function( data ) {
                if ( data && data.fragments ) {          
                    jQuery.each( data.fragments, function( key, value ) {
                        jQuery(key).replaceWith(value);
                    });
                    // if ( $supports_html5_storage ) {
                        sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
                        sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
                    // }                
                    jQuery('body').trigger( 'wc_fragments_refreshed' );
                }
            }
        };

        function updateCartFragment() {
            jQuery.ajax( $fragment_refresh );  
        }

        // ****************************************************************************************************
        // ****************************************************************************************************

        var $clicked_btn = null;

        $('#producto-modal').on('submit','form.cart',function(ev){
            ev.preventDefault();

            var form = $(this),
                formInputs = form.serializeObject(),
                id = $($clicked_btn).attr('data-id'),
                data = {
                    action: "mv23_add_to_cart",
                    product_id: id,
                    variation_id: formInputs['variation_id'],
                    nonce: MV23_GLOBALS.nonce,
                    quantity: formInputs['quantity']
                };

            // *******************************************************
            // agregar los product options a la data que se va enviar
            var product_options_elements = $(form).find('.product-options').find('input, textarea, checkbox, radio').serializeArray();
            $.each(product_options_elements, function() {
                if (data[this.name] !== undefined) {
                    if (!data[this.name].push) {
                        data[this.name] = [data[this.name]];
                    }
                    data[this.name].push(this.value || '');
                } else {
                    data[this.name] = this.value || '';
                }
            });
            // *******************************************************

            $.ajax({
                type: 'post',
                dataType: "json",
                url: MV23_GLOBALS.ajaxUrl,
                data: data,
                beforeSend: function (response) {},
                complete: function (response) {},
                success: function (response) {
                    if (response.status == 'success'){
                        updateCartFragment();
                        $('.cart-qty').text(response.cartQty);
                        if(response.cartQty>0) $('.cart-qty').attr('data-status','has-products');
                        $($clicked_btn).attr('data-status','added');
                    } else {
                        $($clicked_btn).attr('data-status','initial');
                        alert(response.msg);
                    }
                },
            });
        });
        
        $('body').on('click', '.custom-add-to-cart', function(ev){
            ev.preventDefault();

            var status = $(this).attr('data-status');

            if (status == 'initial') {
                var that = this,
                    $form = $(this).parents('form'),
                    $variation_input = $form.find('input[name=variation_id]');

                if ($variation_input.length > 0 && ($variation_input.val() == '' || $variation_input.val() == '0') ) {
                    alert('Elige las opciones del producto antes de añadir este producto a tu carrito.');
                } else {
                    var $form_elements = $form.find('input, textarea, radio, checkbox');
                    for(var i=0; i < $form_elements.length; i++){
                        if($form_elements[i].value === '' && $form_elements[i].hasAttribute('required')){
                            alert('Completa los campos requeridos');
                            $($form_elements[i]).focus().addClass('invalid');
                            return false;
                        }
                    }
                    $(this).attr('data-status','processing');
                    $clicked_btn = that;
                    $form.trigger('submit');
                }
            }
            if (status == 'added') {
                alert('Este producto ya ha sido agregado al pedido');
            }
            if (status == 'processing') {
                alert('Procesando');
            }
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        $( document.body ).on( 'wc_fragments_refreshed', function(){
            var minicartitems = $('.mini_cart_item');
            var newqty = minicartitems.length;
            $('.cart-qty').text(newqty); 
            if(newqty<1) { 
                $('.cart-qty').attr('data-status','empty');
            } else {
                $('.cart-qty').attr('data-status','');
            }
            $('.mini_cart_item').removeClass('loading');
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        $( document.body ).on( 'removed_from_cart', function(){
            updateCartFragment();
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        $(document).on("click", ".changeQty", function() {
            var key = $(this).attr('data-key'),
                qty = $(this).attr('data-qty'),
                $liParent = $(this).parents('li');

            $.ajax({
                type : "post",
                dataType : "html",
                url : woocommerce_params.ajax_url,
                data : {action: "update_mini_cart", cart_item_key:key, cart_item_qty:qty}, 
                beforeSend: function () {
                    $liParent.addClass('loading');
                },
                success: function(response) {
                    updateCartFragment();
                }
            })
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        $('body').on('click','.view-product-js', function(ev){
            // if ( $(this).hasClass('active') ) {
                ev.preventDefault();

                var id = $(this).attr('data-id'),
                    href = $(this).attr('href'),
                    $product_modal = $('#producto-modal'),
                    $product_modal_content = $('#producto-modal .modal-content');

                $.ajax({
                    url: href,
                    beforeSend: function beforeSend() {
                        $product_modal_content.empty();
                        $product_modal.modal('open');
                        $product_modal.attr('data-status','loading');
                    },
                    success: function success(response) {
                        $product_modal.attr('data-status','loaded');
                        var content = $('#content', response);

                        $product_modal_content.html(content.html());
                        start_woocommerce_gallery();
                        $product_modal_content.find( 'label' ).addClass('active');
                        $product_modal_content.find('.open-sidenav-right').sideNav({ menuWidth:380, edge:'right', closeOnClick: false, draggable:false,});
                        $product_modal_content.find('.variations_form').wc_variation_form();

                        var toggleboxes = $product_modal_content.find('.v23-togglebox');
                        for (var i = 0; i < toggleboxes.length; i++) {
                            var el = toggleboxes[i],
                                desktopTemplate = el.dataset.desktopTemplate,
                                options = {};
                                if (desktopTemplate) { options.desktopTemplate = desktopTemplate; }
                                V23_ToggleBox.create( el, options );
                        }

                        var share_content = $('#share-modal', response);
                        $('#ajax-share-modal').html(share_content.html());
                        $product_modal_content.find('a[href="#share-modal"]').attr('href','#ajax-share-modal');
                        window.Sharer.init();

                        url_updates(href);
                    }
                });
            // }
        });
        // ****************************************************************************************************
        // ****************************************************************************************************
        // $( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
            // Fires whenever variation selects are changed
        // } );

        // Fired when the user selects all the required dropdowns / attributes
        // and a final variation is selected / shown
        // $('body').on("show_variation", ".single_variation_wrap", function ( event, variation ) {
        //     c(variation);
        // } );
        // ****************************************************************************************************
        // ****************************************************************************************************
        if (is_checkout) {
            var address = localStorage.getItem('address');
            if (address != '') $('input[name="billing_address_1"]').val(address);
    
            var address_details = localStorage.getItem('address_details');
            if (address_details != '') $('input[name="billing_address_2"]').val(address_details);

            // ****************************************************************************************************
            // AUTOCOMPLETE INPUT
            // ****************************************************************************************************
            var options = { componentRestrictions: {country: 'CL'} };
            var direccionBox = new google.maps.places.Autocomplete( document.getElementById('billing_address_1'), options );
            google.maps.event.addListener( direccionBox, 'place_changed', function() {
                var place = direccionBox.getPlace();

                if( ! place.geometry ) {
                    return;
                }

                localStorage.setItem('address', place.formatted_address); 
            });

            // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            var timeout = null;

            $('input[name="billing_address_1"]').on('keyup change paste blur', function(ev){
                var that = this;
                clearTimeout(timeout);
    
                timeout = setTimeout(function () {
                    var val = $(that).val();
                    localStorage.setItem('address', val);
                }, 1000);
            });

            // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            var timeout2 = null;
            $('input[name="billing_address_2"]').on('keyup change paste blur', function(ev){
                var that = this;
                clearTimeout(timeout2);
    
                timeout2 = setTimeout(function () {
                    var val = $(that).val();
                    localStorage.setItem('address_details', val);
                }, 1000);
            });
        }
        // ****************************************************************************************************
        // ****************************************************************************************************
        // OPEN TERMINOS Y CONDICIONES EN UN MODAL
        // ****************************************************************************************************
        // ****************************************************************************************************
        if (is_checkout) {
            function open_contract(){
                $.ajax({
                    url: MV23_GLOBALS.homeUrl+'/terminos-y-condiciones',
                    beforeSend: function beforeSend() {
                        $('#contract-modal .modal-content').empty();
                        $('#contract-modal').modal('open');
                        $('#contract-modal').attr('data-status','loading');
                    },
                    success: function success(response) {
                        $('#contract-modal').attr('data-status','loaded');
                        var content = $('#content .main', response);
                        $('#contract-modal .modal-content').html(content.html());
                    }
                });
            }
    
            $('#payment-box').on('change','input[name="terms"]', function(ev){
                ev.preventDefault();
                if ($(this).is(':checked')) {
                    $(this).removeProp('checked');
                    open_contract();
                }
            });

            $().on('click', '.woocommerce-terms-and-conditions-link, .woocommerce-privacy-policy-link', function(ev){
                ev.preventDefault();
                open_contract();
            });
    
            $('body').on('updated_checkout', function() {
                $('.woocommerce-terms-and-conditions-link, .woocommerce-privacy-policy-link').click(function(ev){
                    ev.preventDefault();
                    open_contract();
                });
            });
    
            // ****************************************************************************************************
            // ****************************************************************************************************
    
            $('.agree-contract').click(function(ev){
                ev.preventDefault();
                $('input[name="terms"]').prop('checked', true);
                $('#contract-modal').modal('close');
            });
        }
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);