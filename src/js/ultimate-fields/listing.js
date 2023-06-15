// GENERAL
(function($,c){      
    var $components = $('.componente.listing');
    var current_lang = MV23_GLOBALS.lang;
    var loading_text = MV23_GLOBALS.listing_loading_text[current_lang];

    function do_the_ajax($component, term, paged, post_template, per_page, $listing, $pagination, posttype, taxonomy, action, filterValues, order, orderby, offset){
        $.ajax({
            type: 'POST',
            dataType: "json",
            url: MV23_GLOBALS.ajaxUrl,
            data: { 
                action: "load_posts",
                nonce: MV23_GLOBALS.nonce,
                lang: MV23_GLOBALS.lang,
                post_template: post_template,
                term: (filterValues.areParams) ? filterValues.term : term,
                paged: paged || 1,
                per_page: per_page,
                offset: offset,
                order: order,
                orderby: orderby,
                posttype : posttype,
                taxonomy : taxonomy,
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
        var term = ($filter.length) ? $filter.find('.posts-filter__term-select').val() : '',
            year = ($filter.length) ? $filter.find('.posts-filter__year-select').val() : '',
            month = ($filter.length) ? $filter.find('.posts-filter__month-select').val() : '',
            search = ($filter.length) ? $filter.find('.posts-filter__search-input').val() : '';

        var areParams = (term == '' && year == '' && month == '' && search == '') ? false : true;

        return {areParams:areParams, term:term, search:search, year:year, month:month}
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
                    taxonomy = $component.attr("data-taxonomy"),
                    term = $component.attr("data-term"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    action = 'replace',
                    filterValues = getFilterValues($filter);
                
                do_the_ajax($component, term, paged, post_template, per_page, $listing, $pagination, posttype, taxonomy, action, filterValues, order, orderby, offset);
            });
            
            $component.on('click','.load_more_posts', function(event){
                event.preventDefault();
                var $this = $(this),
                    paged = $this.attr("data-paged"),
                    $pagination = null,
                    posttype = $component.attr("data-posttype"),
                    taxonomy = $component.attr("data-taxonomy"),
                    term = $component.attr("data-term"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    action = 'append',
                    filterValues = getFilterValues($filter);
                
                do_the_ajax($component, term, paged, post_template, per_page, $listing, $pagination, posttype, taxonomy, action, filterValues, order, orderby, offset);
            });
            
            $component.on('click','.posts-filter__submit',function(ev){
                ev.preventDefault();
                var $pagination = $component.find('.pagination'),
                    posttype = $component.attr("data-posttype"),
                    taxonomy = $component.attr("data-taxonomy"),
                    term = $component.attr("data-term"),
                    post_template = $component.attr("post-template"),
                    per_page = $component.attr("data-qty"),
                    offset = $component.attr("data-offset"),
                    order = $component.attr("data-order"),
                    orderby = $component.attr("data-orderby"),
                    paged = 1,
                    action = 'replace',
                    filterValues = getFilterValues($filter);

                do_the_ajax($component, term, paged, post_template, per_page, $listing, $pagination, posttype, taxonomy, action, filterValues, order, orderby, offset);
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