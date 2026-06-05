(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var carousel = $('.carousel__slider');

        for (var i = 0; i < carousel.length; i++) {
            var slider = carousel[i];

            var tns_slider = create_tns_slider( slider ),
                uniqueId = $(slider).data('slider-uid');

            if (!uniqueId) {
                uniqueId = Math.random().toString(36).substring(2, 10);
            }

            $(slider).attr('data-slider-uid',uniqueId);
            MV23_GLOBALS.carousels[uniqueId] = tns_slider;
        }

        function scroll_to_slide(sliderUid) {
            // Adjust scroll position if needed
            var target = MV23_GLOBALS.carousels[sliderUid].getInfo().container;
            let elementPosition = $(target).offset().top;
            let newPosition = elementPosition;
            if( !MV23_GLOBALS.disableHeaderHeightCalculationOnAnchors ){
                var bodyStyles = window.getComputedStyle(document.body);
                var headerHeight = bodyStyles.getPropertyValue('--sticky-header-height');
                newPosition = elementPosition - parseInt(headerHeight);
            }

            $("html, body").animate({ scrollTop: newPosition }, { duration: 800, queue: false });
        }

        // go to slide implementation
        // example <button class="go-to-slide" data-slide="8" data-slider-uid="uniqueId" data-scroll="true"></button>

        $('.go-to-slide').on('click', function(e) {
            e.preventDefault();
            var slide = $(this).data('slide') ?? 1;
            var scroll = $(this).data('scroll') ?? false;
            var sliderUid = $(this).data('slider-uid');

            if (MV23_GLOBALS.carousels[sliderUid]) {
                const slider = MV23_GLOBALS.carousels[sliderUid];
                slider.pause();
                slider.goTo(slide - 1); // -1 because TNS is 0-indexed
                slider.play();
                if (scroll) {
                    scroll_to_slide(sliderUid);
                }
            }
        });

        // go to next slide implementation
        $('.go-to-next-slide').on('click', function(e) {
            e.preventDefault();
            var sliderUid = $(this).data('slider-uid');
            var scroll = $(this).data('scroll') ?? false;

            if (MV23_GLOBALS.carousels[sliderUid]) {
                const slider = MV23_GLOBALS.carousels[sliderUid];
                slider.pause();
                slider.goTo('next');
                slider.play();
                if (scroll) {
                    scroll_to_slide(sliderUid);
                }
            }
        });

        // go to prev slide implementation
        $('.go-to-prev-slide').on('click', function(e) {
            e.preventDefault();
            var sliderUid = $(this).data('slider-uid');
            var scroll = $(this).data('scroll') ?? false;

            if (MV23_GLOBALS.carousels[sliderUid]) {
                const slider = MV23_GLOBALS.carousels[sliderUid];
                slider.pause();
                slider.goTo('prev');
                slider.play();
                if (scroll) {
                    scroll_to_slide(sliderUid);
                }
            }
        });

    });
})(jQuery,console.log); 