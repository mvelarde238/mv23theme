(function($,c){
    $(function() {
        // ****************************************************************************************************
        // Init GLightbox
        // ****************************************************************************************************

        window.mv23Lightbox = GLightbox({
            selector: '[data-gallery], [data-glightbox], .zoom',
            touchNavigation: true,
            loop: false,
            closeOnOutsideClick: true,
        });

        // ****************************************************************************************************
    });
})(jQuery,console.log);