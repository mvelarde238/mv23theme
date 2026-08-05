(function($,c){      
    // post modal
    var $postModal = $('#post-modal'),
        $postModal_content = $postModal.find('.modal-content');

    // sync trigger links inside each .postcard 
    $('.postcard').each(function(){
        var $pc = $(this);
        var $primary = $pc.find('a.trigger-post-action').first();
        if ( $primary.length ) {
            var href = $primary.attr('href');
            if ( href ) {
                $pc.find('a').filter(function(){
                    return this.getAttribute('href') === href;
                }).addClass('trigger-post-action');
            }
        }
    });

    // post action
    $(document).on('click', '.trigger-post-action', function(event){
        var $postcard = $(this).parents('.postcard'),
            $listingComponent = $postcard.parents('.listing.component'),
            $postsListing = $postcard.parents('.posts-listing'),
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
                    var modalInstance = M.Modal.getInstance($postModal[0]);
                    $postModal.attr('data-status','loading');
                    modalInstance.open( $(event.target) );
                },
                success: function success(response) {
                    $postModal.attr('data-status','');
                    
                    if(response) {
                        var main = $('.main', response);
                        var is_woocommerce = $(main).find('.woocommerce-product-gallery').length;
                        var containerStyles = $('.container style', response);

                        var content_wrapper = document.createElement('div');
                        if ( is_woocommerce ) content_wrapper.className = "woocommerce woocommerce-page woocommerce-js";
                        content_wrapper.innerHTML = main.html();

                        $postModal_content.html( content_wrapper );
                        if ( containerStyles.length ) {
                            containerStyles.clone().prependTo( $postModal_content );
                        }

                        /** Initialize product gallery **/
                        if ( is_woocommerce ) {
                            $postModal_content.find('.woocommerce-product-gallery').css('opacity',1);
                            $postModal_content.find('.woocommerce-product-gallery').find('img').attr('class','zoom');
                        }
                    }
                }
            });
        }

        // expander action is handled by ListingExpander module, so we don't need to do anything here
    });
            
})(jQuery,console.log);