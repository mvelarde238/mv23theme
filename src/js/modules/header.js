(function($,c){      
    $(function() {
        var $header = $('.header'),
            root = document.querySelector(':root');

        // ****************************************************************************************************
        // SET BODY PADDING TOP
        // ****************************************************************************************************
        function set_body_padding_top(){
            let header_height = document.getElementById("header").offsetHeight;
            root.style.setProperty( '--body-padding-top', header_height+'px' );
        }

        addEventListener("resize", (event) => { set_body_padding_top(); });

        set_body_padding_top();

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
                $header.attr('class', STICKY_HEADER.classes);
            },
            hide: function(){
                $logo.attr('src', STATIC_HEADER.logo);
                $header.attr('class', STATIC_HEADER.classes);
            }
        }

        stickyHeader.init();
        
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);