// GENERAL
(function($,c){      
    // post modal
    var $postModal = $('#post-modal'),
        $postModal_content = $postModal.find('.modal-content');

    // create expander elements
    var headerHeight = MV23_GLOBALS.headerHeight,
        expanderHeight = MV23_GLOBALS.expanderHeight,
        expanderResponseHeight = MV23_GLOBALS.expanderResponseHeight,
        scrollDuration = MV23_GLOBALS.expanderScrollDuration,
        expanderResponse = '<div class="expander-response"></div>',
        closeBtn = '<div class="expander-close"></div>',
        expanderInner = '<div class="expander-inner">'+expanderResponse+closeBtn+'</div>',
        loading = '<div class="expander-loading"></div>';

    $(document).on('click', '.trigger-post-action', function(event){
        var $postcard = $(this).parents('.postcard'),
            $listing = $(this).parents('.posts-listing'),
            url = this.getAttribute('href'),
            action = $postcard.attr('data-action');

        if( action == 'none' ){
            event.preventDefault();
            return;
        }

        if( action == 'show-popup' ){
            event.preventDefault();

            $.ajax({
                url: url,
                beforeSend: function beforeSend() {
                    $postModal.modal('open').attr('data-status','loading');
                },
                success: function success(response) {
                    $postModal.attr('data-status','');
                    
                    if(response) {
                        var main = $('.main', response);
                        var is_woocommerce = $(main).find('.woocommerce-product-gallery').length;

                        var content_wrapper = document.createElement('div');
                        if ( is_woocommerce ) content_wrapper.className = "woocommerce woocommerce-page woocommerce-js";
                        content_wrapper.innerHTML = main.html();

                        $postModal_content.html( content_wrapper );

                        /** Initialize product gallery **/
                        if ( is_woocommerce ) {
                            $postModal_content.find('.woocommerce-product-gallery').css('opacity',1);
                            $postModal_content.find('.woocommerce-product-gallery').find('img').attr('class','zoom');
                        }
                    }
                }
            });
        }

        if( action == 'show-expander' ){
            event.preventDefault();
            var scrollTo = $postcard.attr('data-scroll-to');
                
            var listingIsCarrusel = $listing.hasClass('posts-listing--carrusel');
            // where to add the expander
            var $expanderTarget = ( listingIsCarrusel ) ? $listing : $postcard;

            // reset all
            var $listingItems = $listing.find('.postcard');
            $listing.find('.expander').remove();
            $listingItems.removeClass('active');
            $listingItems.attr('style', '');

            $.ajax({
                url: url,
                beforeSend: function beforeSend() {
                    // open expander
                    $postcard.addClass('active');
                    $expanderTarget.css('paddingBottom', expanderHeight);
                    $expanderTarget.append('<div class="expander">'+expanderInner+'</div>');
                    $expanderTarget.find('.expander-response').css('height', expanderResponseHeight);
                    $expanderTarget.find('.expander').append(loading);
                    if( scrollTo == 'postcard' || scrollTo == 'expander' ){
                        var $element = (scrollTo == 'postcard') ? $postcard : $expanderTarget.find('.expander');
                        $("html, body").animate({ scrollTop: ($element.offset().top - headerHeight) }, {duration: scrollDuration, queue: false }); 
                    }
                },
                success: function success(response) {
                    var content = $('.main', response);
                    if(response) {
                        $expanderTarget.find('.expander-loading').remove();
                        $expanderTarget.find('.expander-response').html( content.html() );
                        // colorbox
                        // $expanderTarget.find('.expander-response .zoom').colorbox({ rel:'expander-group', maxHeight:"96%", maxWidth: "96%" });
                    }
                }
            });
        }
    });

    // expander close
    $(document).on('click', '.expander-close', function(){
        var $listing = $(this).parents('.posts-listing');

        $listing.find('.expander').remove();
        
        var $listingItems = $listing.find('.postcard'); 
        $listingItems.removeClass('active');
        
        var listingIsCarrusel = $listing.hasClass('posts-listing--carrusel');
        var $expanderTarget = ( listingIsCarrusel ) ? $listing : $listingItems;
        $expanderTarget.attr('style', '');
    });
            
})(jQuery,console.log);