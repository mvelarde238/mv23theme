// GENERAL
(function($,c){      
    var $components = $('.componente.listing');
    var current_lang = MV23_GLOBALS.lang;
    var loading_text = MV23_GLOBALS.listing_loading_text[current_lang];

    function do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: MV23_GLOBALS.ajaxUrl,
            data: { 
                action: "load_posts",
                nonce: MV23_GLOBALS.nonce,
                lang: MV23_GLOBALS.lang,
                post_template: post_template,
                terms: (filterValues.areParams) ? filterValues.terms : terms,
                paged: paged || 1,
                per_page: per_page,
                offset: offset,
                order: order,
                orderby: orderby,
                posttype : posttype,
                taxonomies: taxonomies,
                search: filterValues.search,
                year: filterValues.year,
                month: filterValues.month
            },
            beforeSend: function(){
                $component.attr('data-status','loading');
                $pagination && $pagination.html('<p class="center">'+loading_text+'</p>');
            },
            success: function(response) {
                $component.attr('data-status','loaded');
                switch(response.status){
                    case 'success':
                        var $items = $(response.posts);
                        if(action === 'replace') $listing.html($items);
                        if(action === 'append') $listing.append($items);
                        $listing.trigger('listingUpdated', {listing:$listing, items:$items, action:action, response:response});
                        $pagination && $pagination.html(response.pagination);

                        var scrolltop = $component.attr("data-scrolltop");
                        if(scrolltop) {
                            var headerHeight = MV23_GLOBALS.headerHeight;
                            $("html, body").animate({ scrollTop: ($component.offset().top - headerHeight) }, {duration: 800, queue: false, easing: 'easeOutCubic'});
                        }
                        break;
                    case 'error':
                        $listing.html('<p class="center posts-filter-error-msg">'+response.message+'</p>');
                        $pagination && $pagination.html('');
                        break;
                    default:
                        c(response);
                }
            }
        });
    }

    function getFilterValues($filter){
        var $year_selector = $filter.find('.posts-filter__year-select'),
            $month_selector = $filter.find('.posts-filter__month-select'),
            $search_input = $filter.find('.posts-filter__search-input'),
            $terms_selects = $filter.find('.posts-filter__term-select'),
            year = ($year_selector.length) ? $year_selector.val() : '',
            month = ($month_selector.length) ? $month_selector.val() : '',
            search = ($search_input.length) ? $search_input.val() : '',
            terms = '';

        if( $terms_selects.length ){
            var term_values = [];
            $terms_selects.each(function(i,elem){
                term_values.push( $(elem).val() );
            });
            var are_terms_selected = false;
            for (let i = 0; i < term_values.length; i++) {
                if( term_values != '' ){
                    are_terms_selected = true;
                    break;
                } 
            }
            if(are_terms_selected) terms = term_values.join();
        }

        var areParams = (terms == '' && year == '' && month == '' && search == '') ? false : true;

        return {areParams:areParams, terms:terms, search:search, year:year, month:month}
    };

    if( $components.length ){
        $components.each(function(i, e){
            var $component = $(e),
                $filter = $component.find('.posts-filter'),
                $listing = $component.find('.posts-listing');
    
            $component.on('click','a.page-numbers', function(event){
                event.preventDefault();
                var href = event.target.getAttribute('href'),
                    url = new URL(href),
                    paged = url.searchParams.get("paged"),
                    $pagination = $component.find('.pagination'),
                    posttype = $component.attr("data-posttype"),
                    taxonomies = $component.attr("data-taxonomies"),
                    terms = $component.attr("data-terms"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    action = 'replace',
                    filterValues = getFilterValues($filter);
                
                do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset);
            });
            
            $component.on('click','.load_more_posts', function(event){
                event.preventDefault();
                var $this = $(this),
                    paged = $this.attr("data-paged"),
                    $pagination = null,
                    posttype = $component.attr("data-posttype"),
                    taxonomies = $component.attr("data-taxonomies"),
                    terms = $component.attr("data-terms"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    action = 'append',
                    filterValues = getFilterValues($filter);
                
                do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset);
            });
            
            $component.on('click','.posts-filter__submit',function(ev){
                ev.preventDefault();
                var $pagination = $component.find('.pagination'),
                    posttype = $component.attr("data-posttype"),
                    taxonomies = $component.attr("data-taxonomies"),
                    terms = $component.attr("data-terms"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    paged = 1,
                    action = 'replace',
                    filterValues = getFilterValues($filter);

                do_the_ajax($component, terms, paged, post_template, per_page, $listing, $pagination, posttype, taxonomies, action, filterValues, order, orderby, offset);
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