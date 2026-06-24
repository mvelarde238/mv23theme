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

    /**
     * For grid-based post listings, finds the last postcard in the same row
     * as the clicked postcard so the expander can be inserted right after it.
     * The expander then uses grid-column: 1 / -1 in CSS to span the full row.
     */
    function getGridRowInsertTarget($postsListing, $postcard) {
        // Detect the current device key (d/l/t/m) based on viewport width,
        // using the same breakpoints defined in the CSS variables
        const viewport = updateViewportDimensions();
        const breakpoints = { m: 480, t: 768, l: 992 };
        let device = 'd';
        for (const key in breakpoints) {
            if (viewport.width < breakpoints[key]) {
                device = key;
                break;
            }
        }

        // Read the column count for the active device from the CSS custom property
        // e.g. --d-columns:3, --l-columns:3, --t-columns:2, --m-columns:1
        const columns = parseInt( getComputedStyle($postsListing[0]).getPropertyValue(`--${device}-columns`).trim() ) || 1;

        // Determine the index of the clicked postcard within the listing
        const $allPostcards = $postsListing.find('.postcard');
        const postcardIndex = $allPostcards.index($postcard);

        // Calculate the index of the last postcard in the same row,
        // clamped to the total number of postcards to handle incomplete rows
        const lastInRowIndex = Math.min( Math.floor(postcardIndex / columns) * columns + columns - 1, $allPostcards.length - 1 );

        // Return the postcard element after which the expander will be inserted
        return $allPostcards.eq(lastInRowIndex);
    }

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
                    $postModal.modal('open').attr('data-status','loading');
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

        if( action == 'show-expander' ){
            event.preventDefault();
            var scrollTo = $postcard.attr('data-scroll-to');
                
            // where to add the expander
            let $expanderTarget = null;
            let $expanderInsertAfter = null;
            if ( $postsListing.hasClass('posts-listing--carousel') ){
                $expanderTarget = $postsListing;
            } else if ( $postsListing.hasClass('posts-listing--masonry') ){
                $expanderTarget = $listingComponent;
            } else {
                // Grid layout: insert expander after the last postcard in the clicked row
                $expanderTarget = $postsListing;
                $expanderInsertAfter = getGridRowInsertTarget($postsListing, $postcard);
            }

            // reset all
            var $postsListingItems = $postsListing.find('.postcard');
            $postsListing.find('.expander').remove();
            $postsListingItems.removeClass('active');
            $postsListingItems.attr('style', '');

            $.ajax({
                url: url,
                beforeSend: function beforeSend() {
                    // insert expander
                    $postcard.addClass('active');
                    if ( $expanderInsertAfter ) {
                        $('<div class="expander">'+expanderInner+'</div>').insertAfter($expanderInsertAfter);
                    } else {
                        $expanderTarget.append('<div class="expander">'+expanderInner+'</div>');
                    }
                    $expanderTarget.find('.expander-response').css('height', expanderResponseHeight);
                    $expanderTarget.find('.expander').append(loading);
                    if( scrollTo == 'postcard' || scrollTo == 'expander' ){
                        var $element = (scrollTo == 'postcard') ? $postcard : $expanderTarget.find('.expander');
                        $("html, body").animate({ scrollTop: ($element.offset().top - headerHeight) }, {duration: scrollDuration, queue: false }); 
                    }
                },
                success: function success(response) {
                    var content = $('.main', response);
                    var containerStyles = $('.container style', response);
                    if(response) {
                        $expanderTarget.find('.expander-loading').remove();
                        $expanderTarget.find('.expander-response').html( content.html() );
                        if ( containerStyles.length ) {
                            containerStyles.clone().prependTo( $expanderTarget.find('.expander-response') );
                        }
                        // colorbox
                        // $expanderTarget.find('.expander-response .zoom').colorbox({ rel:'expander-group', maxHeight:"96%", maxWidth: "96%" });
                    }
                }
            });
        }
    });

    // expander close
    $(document).on('click', '.expander-close', function(){
        var $listingComponent = $(this).parents('.listing.component');
        var $postsListing = $listingComponent.find('.posts-listing');

        $listingComponent.find('.expander').remove();
        
        var $postsListingItems = $listingComponent.find('.postcard'); 
        $postsListingItems.removeClass('active');
        
        let $expanderTarget = null;
        if ( $postsListing.hasClass('posts-listing--carousel') ){
            $expanderTarget = $postsListing;
        } else if ( $postsListing.hasClass('posts-listing--masonry') ){
            $expanderTarget = $listingComponent;
        } else {
            $expanderTarget = $postsListingItems;
        }
    });
            
})(jQuery,console.log);