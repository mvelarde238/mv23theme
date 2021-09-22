(function($,c){      
    $(function() {

        var fortunataSliders = [],
            count = 0,
            fortunataSlider = $('.componente-fortunata-slider');

        for (var i = 0; i < fortunataSlider.length; i++) {
            
            var fortunataSlider1 = $(fortunataSlider[i]).find('.fortunata-slider1'),
                format = $(fortunataSlider[i]).attr('data-format');

            if(fortunataSlider1.length){
                var slider_options = {
                    container: fortunataSlider1[0], speed: 450, autoplayButton: false, autoplay: true, autoplayButtonOutput: false,
                    autoHeight: true, nav: false, mouseDrag: false, controls: false, axis: 'horizontal'
                };

                if (format=='simple') {
                    slider_options.controls = true;
                    slider_options.nav = true;
                    slider_options.controlsPosition = 'bottom';
                    slider_options.navPosition = 'bottom';
                    slider_options.controlsText = ['<i class="material-icons">arrow_backward</i>','<i class="material-icons">arrow_forward</i>']
                }

                fortunataSliders[count] = tns(slider_options);

                $(fortunataSlider[i]).find('.next').attr('slides', count);
                $(fortunataSlider[i]).find('.prev').attr('slides', count);

                count++;  
            }

            var fortunataSlider2 = $(fortunataSlider[i]).find('.fortunata-slider2');

            if(fortunataSlider2.length){
                fortunataSliders[count] = tns({
                    container: fortunataSlider2[0], speed: 450, autoplayButton: false, autoplay: true, autoplayButtonOutput: false,
                    autoHeight: false, nav: false, mouseDrag: false, controls: false, axis: 'vertical'
                });    

                $(fortunataSlider[i]).find('.next').attr('slides', count+','+(count-1));
                $(fortunataSlider[i]).find('.prev').attr('slides', count+','+(count-1));

                count++;  
            }
        }

        if (fortunataSlider.length) {
            $('.next').click(function(){
                var slides = $(this).attr('slides');
                var slides_indexes = slides.split(',')

                for (var i = 0; i < slides_indexes.length; i++) {
                    fortunataSliders[parseInt(slides_indexes[i])].goTo('next');
                }
            });
            $('.prev').click(function(){
                var slides = $(this).attr('slides');
                var slides_indexes = slides.split(',')

                for (var i = 0; i < slides_indexes.length; i++) {
                    fortunataSliders[parseInt(slides_indexes[i])].goTo('prev');
                }
            });
        }


    });
})(jQuery,console.log);