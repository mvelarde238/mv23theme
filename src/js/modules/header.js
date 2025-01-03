(function($,c){      
    $(function() {
        var $header = $('.header'),
            root = document.querySelector(':root');

        // ****************************************************************************************************
        // SET HEADER HEIGHT
        // ****************************************************************************************************
        function set_header_height( timeoout = 800 ){
            setTimeout(() => { // let css transitions change elements height
                let header = document.getElementById("header"),
                    key = header.classList.contains('header--static') ? 'static' : 'sticky',
                    header_height = header.offsetHeight;
    
                root.style.setProperty( '--'+key+'-header-height', header_height+'px' );
            }, timeoout);
        }

        addEventListener("resize", (event) => { set_header_height(); });

        set_header_height(0);

        // ****************************************************************************************************
        // STICKY HEADER
        // ****************************************************************************************************
        var $logo = $('.header__logo__link img'),
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
                $header.attr('class', STICKY_HEADER.classes.join(' '));
                $header.attr('style', STICKY_HEADER.styles.join(' '));
                set_header_height();
            },
            hide: function(){
                $logo.attr('src', STATIC_HEADER.logo);
                $header.attr('class', STATIC_HEADER.classes.join(' '));
                $header.attr('style', STATIC_HEADER.styles.join(' '));
                set_header_height();
            }
        }

        stickyHeader.init();
        
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);