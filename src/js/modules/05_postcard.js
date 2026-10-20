(function($,c){
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

    // Add aria attributes to the modal trigger for accessibility
    $.each($('.trigger-post-action'), function(index, element){
        var $postcard = $(this).parents('.postcard'),
            action = $postcard.attr('data-action');

        if (action === 'show-popup'){
            $(element).attr('aria-haspopup','true').attr('aria-expanded','false');
        }
    });

    // post action
    $(document).on('click', '.trigger-post-action', function(event){
        var $postcard = $(this).parents('.postcard'),
            action = $postcard.attr('data-action');

        if( action == 'none' ){
            event.preventDefault();
            return;
        }

        if( action == 'show-popup' ){
            event.preventDefault();

            const $listingComponent = $(this).parents('.listing.component'),
                listing_args_raw = $listingComponent.attr('data-listing-args'),
                listing_args = JSON.parse(listing_args_raw),
                oec_element_id = listing_args.oce_element ?? 0,
                oce_element = OffCanvas_Elements.getElementById( oec_element_id );

            if( oce_element ){
                const modalInstance = oce_element.M_instance;
                modalInstance.open( $(this) );

            } else {
                const that = this;
                const themeModalExists = jQuery('#post-modal').length > 0;
                const $postModal = themeModalExists ? jQuery('#post-modal') : jQuery('<div id="post-modal" class="post-modal modal empty-on-close"><div class="modal-content"></div><a href="#!" class="modal-close"></a></div>');
                if(!themeModalExists){
                    jQuery('body').append($postModal);
                    create_theme_modal($postModal[0]);
                }
                const modalInstance = M.Modal.getInstance($postModal[0]);

                $.ajax({
                    url: that.getAttribute('href'),
                    beforeSend: function() {
                        $postModal.attr('data-status','loading');
                        modalInstance.open( $(that) );
                    },
                    success: function(response) {
                        $postModal.attr('data-status','');
                        if(response) {
                            const responseDocument = new DOMParser().parseFromString(response, 'text/html');
                            const main = responseDocument.querySelector('.main');
                            const styles = responseDocument.querySelectorAll('style');
                            $postModal.find('.modal-content').html(main ? main.innerHTML : '');
                            if (styles.length) {
                                $(styles).clone().prependTo($postModal.find('.modal-content'));
                            }

                            // Dispatch a custom event to indicate that the modal content has been loaded
                            var dynamicContentLoadedEvent = new CustomEvent('oce_dynamic_content_loaded', {
                                detail: { modal: $postModal[0] },
                            });
                            document.body.dispatchEvent(dynamicContentLoadedEvent);
                        }
                    }
                });
            }
        }

        // if (action === 'show-expander') { ... }
        // expander action is handled by ListingExpander module, so we don't need to do anything here
    });
            
})(jQuery,console.log);