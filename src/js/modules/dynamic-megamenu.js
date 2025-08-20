(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        $('.dynamic-megamenu__nav .toggle-submenu').attr('aria-expanded', false);

        $('.dynamic-megamenu__nav').on('click','.toggle-submenu',function(){
            const expanded = $(this).attr('aria-expanded') === 'true' || false;
            if( expanded ){
                // hide
                $(this).attr('aria-expanded', false);
                $(this).parent().find('.submenu').slideUp();
                $(this).parent().find('.toggle-submenu').attr('aria-expanded', false);
            } else {
                // show
                $(this).attr('aria-expanded', true);
                $(this).parent().children('.submenu').slideDown();
            }
        });

        // initialize active term in megamenu
        $('.dynamic-megamenu').each(function(i,e){
            var $links = $(e).find('.dynamic-megamenu__nav a');
            var $term_active_name_wrapper = $(e).find('.term-active-name b');
            var data_term = $($links[0]).attr('data-term');

            // mostrar el nombre del primer term
            $term_active_name_wrapper.text( $($links[0]).text() );

            // mostrar la lista del primer term
            $(e).find('.dynamic-megamenu-term-'+data_term).show();
        });
        
        // handle megamenu links:hover
        var $nav_links = $('.dynamic-megamenu__nav a');
        $nav_links.hover(function(){
            var data_term = $(this).attr('data-term'),
                term_name = $(this).text(),
                $megamenu = $(this).parents('.megamenu'),
                $term_active_name_wrapper = $megamenu.find('.term-active-name b'),
                $scope_nav_links = $megamenu.find('.dynamic-megamenu__nav a'),
                $dynamic_content_lists = $megamenu.find('.dynamic-megamenu-list');
                // is_depth_1 = $(this).parent().parent().hasClass('item-depth-1');
            
            // ocultar listas activas y mostrar la que corresponde
            $dynamic_content_lists.hide();
            $megamenu.find('.dynamic-megamenu-term-'+data_term).show();
            
            // mostrar el nombre de la categoria
            $term_active_name_wrapper.text( term_name );

            // handle active class
            $scope_nav_links.removeClass('active');
            $(this).addClass('active');
        });
    });
})(jQuery,console.log); 