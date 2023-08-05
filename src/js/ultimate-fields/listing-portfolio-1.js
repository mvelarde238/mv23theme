// GENERAL
(function($,c){      
    var $portfolio1Listings = $('.posts-listing--portfolio1');
    var headerHeight = MV23_GLOBALS.headerHeight;
    var expanderHeight = MV23_GLOBALS.expanderHeight;
    var scrollTo = MV23_GLOBALS.listingPortfolioScrollTo;
    var scrollDuration = MV23_GLOBALS.listingPortfolioScrollDuration;

    if( $portfolio1Listings.length ){
        $portfolio1Listings.each(function(i, e){
            var $listing = $(e),
                $listingItems = $listing.find('.post-card');

            // create expander elements
            var expanderResponse = '<div class="expander-response"></div>',
                closeBtn = '<div class="expander-close"></div>',
                expanderInner = '<div class="expander-inner container">'+expanderResponse+closeBtn+'</div>',
                loading = '<div class="expander-loading"></div>';

            $listing.on('click', '.expander-open', function(event){
                event.preventDefault();
                var $postCard = $(this).parents('.post-card');

                // reset all
                $listing.find('.expander').remove();
                $listingItems.removeClass('active');
                $listingItems.attr('style', '');

                var url = this.getAttribute('href');

                $.ajax({
                    url: url,
                    beforeSend: function beforeSend() {
                        // open expander
                        $postCard.css('paddingBottom', expanderHeight);
                        $postCard.addClass('active').append('<div class="expander">'+expanderInner+'</div>');
                        $postCard.find('.expander').append(loading);

                        if( scrollTo == 'postcard' || scrollTo == 'expander' ){
                            var $element = (scrollTo == 'postcard') ? $postCard : $postCard.find('.expander');
                            $("html, body").animate({ scrollTop: ($element.offset().top - headerHeight) }, {duration: scrollDuration, queue: false, easing: 'easeOutCubic'}); 
                        }
                    },
                    success: function success(response) {
                        var content = $('.main', response);
                        if(response) {
                            $postCard.find('.expander-loading').remove();
                            $postCard.find('.expander-response').css('height', expanderHeight).html( content.html() );

                            // colorbox
                            $postCard.find('.expander-response .zoom').colorbox({ rel:'expander-group', maxHeight:"96%", maxWidth: "96%" });
                        }
                    }
                });
            });

            $listing.on('click', '.expander-close', function(){
                $listing.find('.expander').remove();
                $listingItems.removeClass('active');
                $listingItems.attr('style', '');
            });
        });   
    }
})(jQuery,console.log);