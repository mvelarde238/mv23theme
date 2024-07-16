(function($,c){      
    $(function() {
        $(".has-megamenu").removeClass('menu-item-has-children');

        $(".header li.menu-item-has-children").hover(function(){
            hide_main_megamenu();
        },function(){});

        $(".header li.menu-item:not(.is-active)").hover(function(){
            hide_main_megamenu();
        },function(){});

        // ****************************************************************************************************
        // MEGAMENÚ
        // ****************************************************************************************************
        $('.megamenu').appendTo('#megamenus');

        var $body = $('body'),
            $header = $('.header'),
            timeout = null;

        $(".header .has-megamenu").hover(function(){ 
            clearTimeout(timeout);
            var $that = $(this);

            timeout = setTimeout(function () {
                var megamenu = $that.find('a').eq(0).attr('data-activates');
    
                // Disable Scrolling
                var oldWidth = $body.innerWidth();
                // $body.css('overflow', 'hidden');
                $body.width(oldWidth);
                $header.width(oldWidth);
    
                $('.megamenu').removeClass('is-active');
                $that.addClass('is-active');
                $('#'+megamenu).addClass('is-active');
    
                if ( $('.megamenu-overlay').length < 1 ) {
                    $body.append('<div class="megamenu-overlay"></div>');
                }
            }, 250);
        },function(){
            clearTimeout(timeout);
        });

        function hide_main_megamenu(){
            // Reenable scrolling
            $body.css({ overflow: '', width: '' });
            $header.css({ width: '' });

            $('.has-megamenu').removeClass('is-active');
            $('.megamenu').removeClass('is-active');

            $('.megamenu-overlay').remove();
        }

        $(document).on('click','.megamenu-overlay', function(){ hide_main_megamenu(); });
        $(".megamenu-close, .megamenu a").click(function(){ hide_main_megamenu(); });

        // ****************************************************************************************************
        // ****************************************************************************************************
        // ****************************************************************************************************

    });
})(jQuery,console.log);