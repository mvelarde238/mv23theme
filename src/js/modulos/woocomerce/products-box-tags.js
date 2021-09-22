(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // ****************************************************************************************************

        var $checkboxes = $('.products-box__tags input[type=checkbox]');

        $checkboxes.click(function(){
            var $checkeds = $('.products-box__tags input:checked'),
                keywords = [],
                $grid = $(this).parents('.products-box__accordion-item').find('.products-box__accordion-item__content');

            $grid.addClass('isotope-initialized');

            if ($checkeds.length > 0) {
                $.each($checkeds,function(i,e){
                    keywords.push(e.value);
                });
                $grid.isotope({ filter: keywords.join() });
            } else {
                $grid.isotope({ filter: '*' });
            }
            
            Waypoint.refreshAll();
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);