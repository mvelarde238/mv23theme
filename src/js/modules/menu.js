(function($,c){      
    $(function() {
        $(".has-megamenu").removeClass('menu-item-has-children');
        // ****************************************************************************************************
        // MENÚ -- SUBMENÚS
        // ****************************************************************************************************

        if (viewport.width > 768) {
            $(".header li.menu-item-has-children").hover(function(){
                $(this).find('ul.sub-menu:first').show(268);
                hide_main_megamenu();
            },function(){
                $(this).find('ul.sub-menu:first').hide();
            });

            $(".header li.menu-item:not(.is-active)").hover(function(){
                hide_main_megamenu();
            },function(){});
        };

        // ****************************************************************************************************
        // MEGAMENÚ
        // ****************************************************************************************************
        $('.megamenu').appendTo('#megamenus');

        var $body = $('body'),
            $header = $('.header'),
            timeout = null;

        $(".has-megamenu").hover(function(){ 
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
        // MOBILE MENU
        // ****************************************************************************************************

        $('#menu-movil').sidenav({ 
            edge: MV23_GLOBALS.mobile_menu_position,
            draggable: false
        });
        $('.menu-movil .sub-menu').css('display','none');
        $('.menu-movil li.menu-item-has-children').append('<button class="toogle-submenu"></button>');
        $('.menu-movil li.menu-item a').addClass('sidenav-close');
        $('.menu-movil').on('click','.toogle-submenu',function(){
          $(this).parent().children('.sub-menu').slideToggle();
        });

        // ****************************************************************************************************
        // ****************************************************************************************************
        // ****************************************************************************************************

    });
})(jQuery,console.log);