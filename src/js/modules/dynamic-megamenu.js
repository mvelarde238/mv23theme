(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        // initialize active term in megamenu
        $('.dynamic-megamenu').each(function(i,e){
            var $links = $(e).find('.dynamic-megamenu__sidenav a');
            var $term_active_name_wrapper = $(e).find('.term-active-name b');
            var data_term = $($links[0]).attr('data-term');

            // set the name of the first term in the megamenu
            $term_active_name_wrapper.text( $($links[0]).text() );

            // show the first term list in the megamenu
            $(e).find('.dynamic-megamenu-term-'+data_term).show();
        });
        
        // handle megamenu links:hover and focus
        var $nav_links = $('.dynamic-megamenu__sidenav a');
        $nav_links.on('mouseenter focus', function(){
            var data_term = $(this).attr('data-term'),
                term_name = $(this).text(),
                $megamenu = $(this).parents('.dynamic-megamenu'),
                $term_active_name_wrapper = $megamenu.find('.term-active-name b'),
                $scope_nav_links = $megamenu.find('.dynamic-megamenu__sidenav a'),
                $dynamic_content_lists = $megamenu.find('.dynamic-megamenu-list');
                // is_depth_1 = $(this).parent().parent().hasClass('item-depth-1');
            
            // hide all lists and show the selected one
            $dynamic_content_lists.hide();
            $megamenu.find('.dynamic-megamenu-term-'+data_term).show();
            
            // show the name of the category
            $term_active_name_wrapper.text( term_name );

            // handle active class
            $scope_nav_links.removeClass('active');
            $(this).addClass('active');
        });
    });
})(jQuery,console.log); 