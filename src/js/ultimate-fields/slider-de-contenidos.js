(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var sliderDeContenidos = $('.componente-slider-de-contenidos');

        for (var i = 0; i < sliderDeContenidos.length; i++) {
            var slider = $(sliderDeContenidos[i]).find('.slider-de-contenidos'),
                nav_position = $(slider[0]).attr('data-nav-position'),
                controls_position = $(slider[0]).attr('data-controls-position'),
                nav_show_title = $(slider[0]).attr('data-show-title'),
                show_controls = $(slider[0]).attr('data-show-controls'),
                show_nav = $(slider[0]).attr('data-show-nav');

            show_controls = ( show_controls == '1' ) ? true : false;
            show_nav = ( show_nav == '1' ) ? true : false;

            var slider_options = {
                container: slider[0], speed: 450, autoplayButton: false, autoplay: false, autoplayButtonOutput: false,
                autoHeight: true, mouseDrag: false, controls: show_controls, nav: show_nav, axis: 'horizontal',
                controlsPosition: controls_position, navPosition: nav_position, loop: false,
                controlsText: ['<i class="fa fa-caret-left"></i>','<i class="fa fa-caret-right"></i>'],
                onInit: function(info){
                    if (nav_show_title == '1') {
                        $(info.navItems).each(function(i,e){
                            var title = $(info.slideItems).eq(i).attr('data-title');
                            $(this).html(title);
                        });  
                    }
                }
            };


            var daSlider = tns(slider_options);

            if ($(sliderDeContenidos[i]).attr('data-extended-bgi') == '1') { 

                $(sliderDeContenidos[i]).attr('style', $(sliderDeContenidos[i]).find('.tns-slide-active').attr('data-style') );

                daSlider.events.on('transitionEnd', function (info, eventName) {
                    $(info.container).parents('.componente-slider-de-contenidos').attr('style', $(info.slideItems[info.navCurrentIndex]).attr('data-style') );
                });
            }         
        }

    });
})(jQuery,console.log);