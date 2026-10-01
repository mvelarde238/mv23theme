(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // SCRIPT PARA MOVER EL RECAPTCHA A UN WRAPPER
        // ****************************************************************************************************

        $(window).load(function(e) {
            setTimeout(function(){            
                $('.grecaptcha-badge').appendTo('.grecaptcha-badge-wrapper');
            }, 3500 );
	    });        

        // *********************************************************************
        // REMOVE ACTIVE IN MENU ITEMS WITH ANCHOR
        // *********************************************************************
        var menu_items_links = $(".main-nav li a");
        menu_items_links.each(function () {
            if ($(this).is('[href*="#"')) {
                $(this).parent().removeClass('current-menu-item current-menu-ancestor');
                // $(this).click(function () {
                    // var current_index = $(this).parent().index(),
                        // parent_element = $(this).closest('ul');
                        // parent_element.find('li').not(':eq(' + current_index + ')').removeClass('current-menu-item current-menu-ancestor');
                    // $(this).parent().addClass('current-menu-item current-menu-ancestor');
                // })
            }
        })

        // ****************************************************************************************************
        // Init Bricks Layout (wait for images/videos/background images)
        // ****************************************************************************************************
        if (MV23_GLOBALS.masonry_is_active) {
            const grids = document.querySelectorAll('.has-masonry-columns');
            grids.forEach(grid => {
                imagesLoaded(grid, function () {
                    const packery = new Packery(grid, {
                        itemSelector: '.masonry-grid-item',
                        columnWidth: '.masonry-grid-sizer',
                        gutter: '.masonry-gutter-sizer',
                        percentPosition: true
                    });

                    // Store Packery instance on the grid element for later access
                    grid.packeryInstance = packery;
                });
            });
        }
    });
})(jQuery,console.log);