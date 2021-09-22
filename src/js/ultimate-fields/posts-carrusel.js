(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var carrusel = $('.posts-carrusel');

        for (var i = 0; i < carrusel.length; i++) {
            var slider = $(carrusel[i]).find('.posts-carrusel__slider'),
                show_controls = $(slider[0]).attr('data-show-controls'),
                nav_position = $(slider[0]).attr('data-nav-position'),
                show_nav = $(slider[0]).attr('data-show-nav'),
                mobile = $(slider[0]).attr('data-mobile'),
                tablet = $(slider[0]).attr('data-tablet'),
                laptop = $(slider[0]).attr('data-laptop'),
                desktop = $(slider[0]).attr('data-desktop');

            show_controls = ( show_controls == '1' ) ? true : false;
            show_nav = ( show_nav == '1' ) ? true : false;
            mobile = ( mobile != '' ) ? mobile : 1;
            tablet = ( tablet != '' ) ? tablet : 2;
            laptop = ( laptop != '' ) ? laptop : 3;
            desktop = ( desktop != '' ) ? desktop : 4;

            var slider_options = {
                container: slider[0], speed: 450, autoplayButton: false, autoplay: true, autoplayButtonOutput: false, loop: true,
                controlsText: ['<i class="fa fa-arrow-circle-o-left"></i>','<i class="fa fa-arrow-circle-o-right"></i>'], rewind: true,
                mouseDrag: true, controls: show_controls, nav: show_nav, navPosition: nav_position, responsive : {
                    1100 : {items:desktop},
                    800 : {items:laptop},
                    470 : {items:tablet},
                    100 : {items:mobile},
                }
            };

            tns(slider_options);
        }

    });
})(jQuery,console.log); 