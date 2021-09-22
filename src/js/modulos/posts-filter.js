(function($,c){      
    $(function() {

        // ****************************************************************************************************
        // FILTRO DE POSTS
        // ****************************************************************************************************

        var postsFilter = $('.posts-filter'),
            resultsBox = $('.filter-result-box');

        function searchPosts(filterValues,paged){
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: MV23_GLOBALS.ajaxUrl,
                data: { 
                    action: "load_posts",
                    nonce: MV23_GLOBALS.nonce,
                    lang: MV23_GLOBALS.lang,
                    term: filterValues.term,
                    search: filterValues.search,
                    year: filterValues.year,
                    month: filterValues.month,
                    paged: paged,
                    posttype : filterValues.posttype,
                    taxonomy : filterValues.taxonomy
                },
                beforeSend: function(){
                    $(resultsBox).html('');
                    $(postsFilter).attr('data-status','loading');
                    $(resultsBox).attr('data-status','loading');
                },
                success: function(response) {
                    $(postsFilter).attr('data-status','loaded');
                    $(resultsBox).attr('data-status','loaded');
                    switch(response.status){
                        case 'success':
                            $(resultsBox).html(response.content);
                            break;
                        case 'error':
                            $(resultsBox).html('<p class="error-msg">'+response.message+'</p>');
                            break;
                        default:
                            c(response);
                    }
                }
            });
        };

        function getFilterValues(){
            var term = $('.posts-filter__term-select').val(),
                year = $('.posts-filter__year-select').val(),
                month = $('.posts-filter__month-select').val(),
                search = $('.posts-filter__search-input').val(),
                posttype = $('.posts-filter__posttype').val(),
                taxonomy = $('.posts-filter__taxonomy').val();

            var areParams = (term == '' && year == '' && month == '' && search == '') ? false : true;

            return {areParams: areParams, term: term, search: search, year: year, month: month, posttype: posttype, taxonomy: taxonomy}
        };

        $('.posts-filter__submit').click(function(ev){
            ev.preventDefault();
            var filterValues = getFilterValues();
            if ( filterValues.areParams ) {
                searchPosts(filterValues,1);
            }
        });

        $('.filter-result-box').on('click','a.page-numbers', function(event){
            event.preventDefault();
            var href = event.target.getAttribute('href'),
                url = new URL(href),
                paged = url.searchParams.get("paged"),
                filterValues = getFilterValues();

            searchPosts(filterValues, paged);
        });
        
	});

})(jQuery,console.log);