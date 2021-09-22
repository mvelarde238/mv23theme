(function($,c){      
    $(function() {
        $(".has-megamenu").removeClass('menu-item-has-children');
        // ****************************************************************************************************
        // MENÚ -- SUBMENÚS
        // ****************************************************************************************************

        if (viewport.width > 768) {
            $("li.menu-item-has-children").hover(function(){
                $(this).find('ul.sub-menu:first').show(268);
                hide_main_megamenu();
            },function(){
                $(this).find('ul.sub-menu:first').hide();
            });
        };

        // ****************************************************************************************************
        // MEGAMENÚ
        // ****************************************************************************************************
        $('.megamenu').appendTo('#megamenus');

        var $body = $('body'),
            $header = $('.header');

        $(".has-megamenu").hover(function(){ 
            var megamenu = $(this).find('a').eq(0).attr('data-activates');

            // Disable Scrolling
            var oldWidth = $body.innerWidth();
            $body.css('overflow', 'hidden');
            $body.width(oldWidth);
            $header.width(oldWidth);

            $('.megamenu').removeClass('is-active');
            $(this).addClass('is-active');
            $('#'+megamenu).addClass('is-active');

            if ( $('.megamenu-overlay').length < 1 ) {
                $body.append('<div class="megamenu-overlay"></div>');
            }
        },function(){});

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
        
        $('.menu-movil__btn').sideNav({ menuWidth:300, edge:'left', closeOnClick: true, draggable:false,});
        $('.menu-movil .sub-menu').css('display','none');
        $('.menu-movil li.menu-item-has-children').append('<button class="toogle-submenu"></button>');
        $('.menu-movil').on('click','.toogle-submenu',function(){
          $(this).parent().children('.sub-menu').slideToggle();
        });

        // ****************************************************************************************************
        // SCROLLSPY
        // ****************************************************************************************************

        var elems = document.querySelectorAll('.scrollspy'),
            options = { 
                activeClass:'is-inview', 
                scrollOffset: 0,
                throttle: 0,
                offsetTop: 100,
                offsetBottom: -500
            };
        $('.scrollspy').scrollSpy(options); 

        // ****************************************************************************************************
        // ****************************************************************************************************
        // ****************************************************************************************************

    });
})(jQuery,console.log);