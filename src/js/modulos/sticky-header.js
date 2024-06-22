(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // HEADER -- STICKY
        // ****************************************************************************************************
        var $header = $('.header'), 
            $logo = $('.header__logo__link img'),
            breakpoint = MV23_GLOBALS.stickyHeaderBreakpoint;

        var stickyHeader = {
            isSticky : false,
            element : '.header',
            init: function(){
                var xscrollTop = $(document).scrollTop();
                if (xscrollTop>breakpoint && !this.isSticky) {
                    this.isSticky = true;
                    stickyHeader.show();
                } 

                $(window).scroll(function(){
                    var xscrollTop = $(document).scrollTop();
                    
                    if (xscrollTop>breakpoint && !this.isSticky) {
                        this.isSticky = true;
                        stickyHeader.show();
                    } 
                    if (xscrollTop<breakpoint && this.isSticky) {
                        this.isSticky = false;
                        stickyHeader.hide();
                    }
                });
            },
            show: function(){
                $logo.attr('src', STICKY_HEADER.logo);
                $header.attr('style', STICKY_HEADER.styles);
                $header.attr('class', STICKY_HEADER.classes);
            },
            hide: function(){
                $logo.attr('src', STATIC_HEADER.logo);
                $header.attr('style', STATIC_HEADER.styles);
                $header.attr('class', STATIC_HEADER.classes);
            }
        }

        stickyHeader.init();
        
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);