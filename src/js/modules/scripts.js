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
        // Init Masonry Grid (wait for images/videos/background images)
        // ****************************************************************************************************
        if (MV23_GLOBALS.masonry_is_active) {
            const grids = document.querySelectorAll('.has-masonry-columns');
            grids.forEach(grid => {
                imagesLoaded(grid, function () {
                    new Masonry(grid, {
                        itemSelector: '.masonry-grid-item',
                        columnWidth: '.masonry-grid-sizer',
                        percentPosition: true,
                        gutter: 20
                    });
                });
            });
        }

        // ****************************************************************************************************
        // Dynamic fix for .full-width to account for scrollbar width and mobile
        // ****************************************************************************************************

        (function(){
            var fullWidthRaf = null;
            function updateFullWidth() {
                var vw = document.documentElement.clientWidth; // excludes scrollbar
                var half = Math.round(vw / 2);
                var els = document.querySelectorAll('.full-width');
                els.forEach(function(el){
                    // override problematic 100vw-based styles
                    el.style.setProperty('width', vw + 'px', 'important');
                    el.style.setProperty('max-width', vw + 'px', 'important');
                    el.style.setProperty('left', '50%', 'important');
                    el.style.setProperty('right', 'auto', 'important');
                    el.style.setProperty('margin-left', -half + 'px', 'important');
                    el.style.setProperty('margin-right', -half + 'px', 'important');
                    // ensure left positioning works
                    if (window.getComputedStyle(el).position === 'static') {
                        el.style.setProperty('position', 'relative', 'important');
                    }
                });
            }
            function scheduleUpdate() {
                if (fullWidthRaf) cancelAnimationFrame(fullWidthRaf);
                fullWidthRaf = requestAnimationFrame(updateFullWidth);
            }
            // Run on load and resize/orientation change
            window.addEventListener('load', scheduleUpdate);
            window.addEventListener('resize', scheduleUpdate);
            window.addEventListener('orientationchange', scheduleUpdate);
            // Initial run (DOM ready)
            scheduleUpdate();
        })();
    });
})(jQuery,console.log);