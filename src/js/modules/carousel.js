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

        // go to slide implementation
        // example <button class="go-to-slide" data-slide="8" data-slider-uid="uniqueId"></button>

        $('.go-to-slide').on('click', function(e) {
            e.preventDefault();
            var slide = $(this).data('slide');
            if (typeof slide === 'undefined') slide = 1; // default to first slide if not specified
            var sliderUid = $(this).data('slider-uid');

            if (MV23_GLOBALS.carousels[sliderUid]) {
                if (DEBUG) console.log('Going to slide ' + slide + ' in carousel with UID ' + sliderUid);
                MV23_GLOBALS.carousels[sliderUid].goTo(slide - 1); // -1 because TNS is 0-indexed
            } else {
                if (DEBUG) console.warn('Carousel with UID ' + sliderUid + ' not found.');
            }
        });

    });
})(jQuery,console.log); 