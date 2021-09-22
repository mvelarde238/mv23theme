(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {

        var productsGallery = $('.products-gallery');

        if (productsGallery.length > 0) {
            for (var i = 0; i < productsGallery.length; i++) {
                var slider = $(productsGallery[i]).find('.products-gallery__slider');
    
                var slider_options = {
                    container: slider[0], speed: 450, autoplayButton: false, autoplay: false, autoplayButtonOutput: false,
                    autoHeight: true, mouseDrag: false, controls: true, nav: false, axis: 'horizontal',
                    controlsText: ['<i class="fa fa-arrow-circle-o-left"></i>','<i class="fa fa-arrow-circle-o-right"></i>'],
                };
    
                var daSlider = tns(slider_options);        
            }
        }

        $('.wb-product-card__share').click(function(ev){ 
            ev.preventDefault();
            $('#share-modal .modal-content').empty();
            var shareHtml = $(this).parents('.wb-product-card').find('.share-wrapper');
            $('#share-modal .modal-content').html(shareHtml.html());
            window.Sharer.init();
            $('#share-modal').modal('open');
        });

    });
})(jQuery,console.log);