(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // HEADER -- FIXED
        // ****************************************************************************************************

        var floatingHeader = {
            isFixed : false,
            element : '.header',
            init: function(){
                var xscrollTop = $(document).scrollTop();
                if (xscrollTop>50 && !this.isFixed) {
                    this.isFixed = true;
                    floatingHeader.show();
                } 

                $(window).scroll(function(){
                    var xscrollTop = $(document).scrollTop();
                    
                    if (xscrollTop>50 && !this.isFixed) {
                        this.isFixed = true;
                        floatingHeader.show();
                    } 
                    if (xscrollTop<50 && this.isFixed) {
                        this.isFixed = false;
                        floatingHeader.hide();
                    }
                });
            },
            show: function(){
                $(this.element).addClass('fixed');
            },
            hide: function(){
                $(this.element).removeClass('fixed');
            }
        }

        floatingHeader.init();
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);