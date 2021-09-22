(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ****************************************************************************************************

        var $pb_searchform = $('#products-box__search-form'),
            $pb_search = $('.products-box__search'),
            $pb_search_results = $('.products-box__search__results');

        $pb_searchform.submit(function(event){
            event.preventDefault();

            var form = $(this),
                formInputs = form.serializeObject();
                formInputs['action'] = "v23getposts";

            if (formInputs['keyword'].length < 3) {
                $pb_search_results.html('<p class="center">Escribe 3 o más caracteres</p>');
                return false;
            }

            $.ajax({
                type: 'POST',
                dataType : "json",
                url: MV23_GLOBALS.ajaxUrl,
                data : formInputs,
                beforeSend: function(){
                    $pb_search.attr('data-status','loading');
                    $pb_search_results.html('<p class="center">Buscando...</p>');
                },
                success: function(response){
                    $pb_search.attr('data-status','active');

                    if(response.status == "success") {
                        $pb_search_results.html(response.results);
                    }

                    if(response.status == "error") {
                        $pb_search_results.html('<p class="center">'+response.message+'</p>');
                    }
                }
            });
        });


        var timeout = null,
            currentValue = '';

        $('input[name="keyword"]').on('keyup change paste', function(ev){
            var that = this,
                newValue = $(this).val();

            if (newValue.length > 2 && newValue != currentValue) {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    currentValue = newValue;
                    $(that).parents('form').submit();
                }, 1000);
            } 

            if (newValue.length < 3) {
                $pb_search.attr('data-status','waiting');
                $pb_search_results.html('');
                $pb_search_results.html('<p class="center">Escribe 3 o más caracteres</p>');
                return false;
            }

            if (newValue.length < 0) {
                $pb_search.attr('data-status','initial');
            }
        });

        // ****************************************************************************************************
        // ****************************************************************************************************

        $('.products-box__search__close').click(function(){
            $pb_search_results.html('');
            $pb_search.attr('data-status','initial');
            $('input[name="keyword"]').val('');
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);