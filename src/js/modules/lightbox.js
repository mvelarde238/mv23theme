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

        window.mv23RefreshLightbox = function() {
            if (!window.mv23Lightbox || typeof window.mv23Lightbox.reload !== 'function') return;
            window.mv23Lightbox.reload();
        };
        
        function mv23UpdateIframeClass() {
            var container = document.querySelector('.glightbox-container');
            if (!container) return;
            // Check for an actual <iframe> element — external image URLs also get .gslide-external
            // but render an <img>, so we must not apply iframe sizing to them.
            var isIframe = !!container.querySelector('.gslide.current .gslide-external iframe');
            container.classList.toggle('mv23-iframe-slide', isIframe);
        }

        window.mv23Lightbox.on('open', function() { setTimeout(mv23UpdateIframeClass, 80); });
        window.mv23Lightbox.on('slide_changed', function() { setTimeout(mv23UpdateIframeClass, 80); });

        $(document).on('listingUpdated', function() {
            window.mv23RefreshLightbox();
        });

        // ****************************************************************************************************
    });
})(jQuery,console.log);