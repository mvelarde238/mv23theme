(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var carrusel = $('.carrusel');

        for (var i = 0; i < carrusel.length; i++) {
            var slider = $(carrusel[i]).find('.carrusel__slider');

            var tns_slider = create_tns_slider( slider[0] ), 
                uniqueId = Math.random().toString(36).substring(2, 10);

            $(carrusel[i]).attr('data-tns-uid',uniqueId);
            MV23_GLOBALS.carousels[uniqueId] = tns_slider;
        }
    });
})(jQuery,console.log); 