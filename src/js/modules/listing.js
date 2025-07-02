// GENERAL
(function($,c){      
    var $components = $('.component.listing');
    var current_lang = MV23_GLOBALS.lang;
    var loading_text = MV23_GLOBALS.listing_loading_text[current_lang];

    function do_the_ajax($component, $listing, $pagination, $filter, paged, action){

        var formData = create_form_data($filter),
            listing_args = $component.attr('data-listing-args'),
            paged = paged || 1,
            listing_template = JSON.parse(listing_args).listing_template,
            scrollTop = JSON.parse(listing_args).scrollTop;

        formData.append('action', "load_posts");
        formData.append('nonce', MV23_GLOBALS.nonce);
        formData.append('lang', MV23_GLOBALS.lang);
        formData.append('listing_args', listing_args);
        formData.append('paged', paged);

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: MV23_GLOBALS.ajaxUrl,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(){
                $component.attr('data-status','loading');
                $pagination && $pagination.html('<p class="center">'+loading_text+'</p>');
            },
            success: function(response) {
                $component.attr('data-status','loaded');
                var $items_container = ( listing_template === 'carrusel' ) ? $listing.find('.carrusel__slider') : $listing;
                if ( listing_template === 'carrusel' ) {
                    var tns_uid = $listing.find('.carrusel').attr('data-tns-uid');
                    var carousel = MV23_GLOBALS.carousels[ tns_uid ];
                }

                switch(response.status){
                    case 'success':
                        var $items = $(response.posts);

                        if ( listing_template === 'carrusel' ){
                            carousel.destroy();
                            $items_container = $listing.find('.carrusel__slider');
                            if(action === 'replace') $items_container.html('');
                        } 
                        if(action === 'replace') $items_container.html($items);
                        if(action === 'append') $items_container.append($items);
                        if ( listing_template === 'carrusel' ) {
                            MV23_GLOBALS.carousels[ tns_uid ] = create_tns_slider( $items_container[0] );
                            if(action === 'append') MV23_GLOBALS.carousels[ tns_uid ].goTo('next');
                        }

                        $listing.trigger('listingUpdated', {listing:$listing, items:$items, action:action, response:response});
                        $pagination && $pagination.html(response.pagination);

                        if(scrollTop) {
                            var headerHeight = MV23_GLOBALS.headerHeight;
                            $("html, body").animate({ scrollTop: ($component.offset().top - headerHeight) }, {duration: 800, queue: false});
                        }
                        break;

                    case 'error':
                        if ( listing_template === 'carrusel' ) {
                            carousel.destroy();
                            $items_container = $listing.find('.carrusel__slider');
                            $items_container.html('');
                        }
                        $items_container.html('<p class="center posts-filter-error-msg">'+response.message+'</p>');
                        if ( listing_template === 'carrusel' ) MV23_GLOBALS.carousels[ tns_uid ] = create_tns_slider( $items_container[0] );
                        $pagination && $pagination.html('');
                        break;

                    default:
                        c(response);
                }

                // Refresh ScrollTrigger breakpoints after content update
                setTimeout(function() {
                    if (typeof window.gsap !== 'undefined' && window.gsap.ScrollTrigger) {
                        if (DEBUG) console.log('Refreshing ScrollTrigger');
                        window.gsap.ScrollTrigger.refresh();
                    } else if (typeof window.ScrollTrigger !== 'undefined') {
                        if (DEBUG) console.log('Refreshing ScrollTrigger (global)');
                        window.ScrollTrigger.refresh();
                    } else {
                        if (DEBUG) console.log('GSAP ScrollTrigger not available - gsap:', typeof window.gsap, 'ScrollTrigger:', typeof window.ScrollTrigger);
                    }
                }, 100); // Small delay to ensure DOM updates are complete
            }
        });
    }

    function getPagedParameter(url) {
        const parsedUrl = new URL(url, window.location.origin);
        const params = new URLSearchParams(parsedUrl.search);
        const paged = params.get('paged'); 
        return (paged) ? paged : 1;
    }

    if( $components.length ){
        $components.each(function(i, e){
            var $component = $(e),
                $filter = $component.find('.posts-filter form'),
                $listing = $component.find('.posts-listing'),
                $pagination = $component.find('.pagination');
    
            $component.on('click','a.page-numbers', function(event){
                event.preventDefault();
                var href = event.target.getAttribute('href'),
                    paged = getPagedParameter(href),
                    action = 'replace';
                    
                do_the_ajax($component, $listing, $pagination, $filter, paged, action);
            });
            
            $component.on('click','.load_more_posts', function(event){
                event.preventDefault();
                var $this = $(this),
                    paged = $this.attr("data-paged"),
                    action = 'append';

                do_the_ajax($component, $listing, null, $filter, paged, action);
            });
            
            $component.on('click','.posts-filter__submit',function(ev){
                ev.preventDefault();
                var paged = 1, action = 'replace';

                do_the_ajax($component, $listing, $pagination, $filter, paged, action);
            });

            $component.on('listingUpdated', function(e,data){
                e.preventDefault();
                var $load_more_btn = $(this).find('.load_more_posts'),
                    paged = $load_more_btn.attr("data-paged"),
                    new_paged = parseInt(paged) + 1;

                if( new_paged > data.response.max_num_pages ) {
                    $load_more_btn.parent().remove();
                } else {
                    $load_more_btn.attr('data-paged', new_paged);
                }
            });
        });   
    }
})(jQuery,console.log);