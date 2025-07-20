(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var carousel = $('.carousel__slider');

        for (var i = 0; i < carousel.length; i++) {
            var slider = carousel[i];

            var tns_slider = create_tns_slider( slider ), 
                uniqueId = Math.random().toString(36).substring(2, 10);

            $(slider).attr('data-tns-uid',uniqueId);
            MV23_GLOBALS.carousels[uniqueId] = tns_slider;
        }
    });
})(jQuery,console.log); 