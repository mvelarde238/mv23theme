(function($,c){
    $(function() {
        // ****************************************************************************************************
        // Init GLightbox
        // ****************************************************************************************************

        // GLightbox groups every matched element into a single shared gallery unless it has its
        // own data-gallery value, so give each ungrouped trigger a unique one to keep it isolated.
        function mv23IsolateUngroupedTriggers() {
            var triggers = document.querySelectorAll('.zoom:not([data-gallery]), [data-glightbox]:not([data-gallery])');
            triggers.forEach(function(el, index) {
                el.setAttribute('data-gallery', 'mv23-zoom-' + Date.now() + '-' + index);
            });
        }
        mv23IsolateUngroupedTriggers();

        window.mv23Lightbox = GLightbox({
            selector: '[data-gallery], [data-glightbox], .zoom',
            touchNavigation: true,
            loop: false,
            closeOnOutsideClick: true,
        });

        window.mv23RefreshLightbox = function() {
            if (!window.mv23Lightbox || typeof window.mv23Lightbox.reload !== 'function') return;
            mv23IsolateUngroupedTriggers();
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