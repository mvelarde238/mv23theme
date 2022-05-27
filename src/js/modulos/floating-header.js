(function($,c){      
    $(function() {
        // ****************************************************************************************************
        // HEADER -- FIXED
        // ****************************************************************************************************
        var $header = $('.header'), 
            $logo = $('.header__logo__link img'),
            breakpoint = FLOATING_HEADER.breakpoint,
            initial_logo = $logo.attr('src'),
            change_logo = (initial_logo != FLOATING_HEADER.logo);

        var initial_style = $header.attr('style');
        if (initial_style == undefined) { initial_style = 'style=""' }

        var change_style = (initial_style != FLOATING_HEADER.style),
            change_text_color = (FLOATING_HEADER.fixed_text_color != FLOATING_HEADER.floating_text_color);


        var floatingHeader = {
            isFixed : false,
            element : '.header',
            init: function(){
                var xscrollTop = $(document).scrollTop();
                if (xscrollTop>breakpoint && !this.isFixed) {
                    this.isFixed = true;
                    floatingHeader.show();
                } 

                $(window).scroll(function(){
                    var xscrollTop = $(document).scrollTop();
                    
                    if (xscrollTop>breakpoint && !this.isFixed) {
                        this.isFixed = true;
                        floatingHeader.show();
                    } 
                    if (xscrollTop<breakpoint && this.isFixed) {
                        this.isFixed = false;
                        floatingHeader.hide();
                    }
                });
            },
            show: function(){
                $(this.element).addClass('fixed');

                if (change_logo) $logo.attr('src',FLOATING_HEADER.logo);
                if (change_style) $header.attr('style',FLOATING_HEADER.style);
                if (change_text_color) $header.removeClass(FLOATING_HEADER.fixed_text_color).addClass(FLOATING_HEADER.floating_text_color);
            },
            hide: function(){
                $(this.element).removeClass('fixed');

                if (change_logo) $logo.attr('src',initial_logo);
                if (change_style) $header.attr('style',initial_style);
                if (change_text_color) $header.removeClass(FLOATING_HEADER.floating_text_color).addClass(FLOATING_HEADER.fixed_text_color);
            }
        }

        floatingHeader.init();
        // ****************************************************************************************************
        // ****************************************************************************************************
    });
})(jQuery,console.log);