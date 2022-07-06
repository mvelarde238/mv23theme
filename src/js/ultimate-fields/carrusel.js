(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var carrusel = $('.carrusel');

        for (var i = 0; i < carrusel.length; i++) {
            var slider = $(carrusel[i]).find('.carrusel__slider'),
                show_controls = $(slider[0]).attr('data-show-controls'),
                nav_position = $(slider[0]).attr('data-nav-position'),
                show_nav = $(slider[0]).attr('data-show-nav'),
                autoplay = $(slider[0]).attr('data-autoplay'),
                mobile = $(slider[0]).attr('data-mobile'),
                tablet = $(slider[0]).attr('data-tablet'),
                laptop = $(slider[0]).attr('data-laptop'),
                desktop = $(slider[0]).attr('data-desktop'),
                mobile_gutter = $(slider[0]).attr('data-mobile-gutter'),
                tablet_gutter = $(slider[0]).attr('data-tablet-gutter'),
                laptop_gutter = $(slider[0]).attr('data-laptop-gutter'),
                desktop_gutter = $(slider[0]).attr('data-desktop-gutter');

            show_controls = ( show_controls == '1' ) ? true : false;
            show_nav = ( show_nav == '1' ) ? true : false;
            autoplay = ( autoplay == '1' ) ? true : false;
            mobile = ( mobile != '' ) ? mobile : 1;
            tablet = ( tablet != '' ) ? tablet : 2;
            laptop = ( laptop != '' ) ? laptop : 3;
            desktop = ( desktop != '' ) ? desktop : 4;

            var slider_options = {
                container: slider[0], speed: 450, autoplayButton: false, autoplay: autoplay, autoplayButtonOutput: false, loop: true,
                controlsText: ['<i class="fa fa-caret-left"></i>','<i class="fa fa-caret-right"></i>'], rewind: true,
                mouseDrag: true, controls: show_controls, nav: show_nav, navPosition: nav_position, responsive : {
                    1100 : {items:desktop, gutter: desktop_gutter},
                    800 : {items:laptop, gutter: laptop_gutter},
                    470 : {items:tablet, gutter: tablet_gutter},
                    100 : {items:mobile, gutter: mobile_gutter},
                }
            };

            tns(slider_options);
        }

    });
})(jQuery,console.log); 