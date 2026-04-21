document.addEventListener('DOMContentLoaded', function () {

    // ****************************************************************************************************
    // STICKY HEADER IMPLEMENTATION
    // ****************************************************************************************************
    const header = document.querySelector('.header');
    stickyHeader.init(
        header, 
        window, 
        MV23_GLOBALS.stickyHeaderBreakpoint
    );

    var event = new CustomEvent('theme_document_ready');
    setTimeout(function () {
        document.body.dispatchEvent(event);

        // ****************************************************************************************************
        // Init Masonry Grid (wait for images/videos/background images)
        // ****************************************************************************************************

        if (MV23_GLOBALS.masonry_is_active) {
            setTimeout(function () {
                const $grids = $('.has-masonry-columns');

                function waitForMedia(container) {
                    const imgs = Array.from(container.querySelectorAll('img'));
                    const vids = Array.from(container.querySelectorAll('video'));

                    const imgPromises = imgs.map(img => {
                        if (img.complete && img.naturalWidth !== 0) return Promise.resolve();
                        return new Promise(resolve => {
                            img.addEventListener('load', resolve, { once: true });
                            img.addEventListener('error', resolve, { once: true });
                        });
                    });

                    const vidPromises = vids.map(video => {
                        if (video.readyState >= 3) return Promise.resolve();
                        return new Promise(resolve => {
                            video.addEventListener('loadeddata', resolve, { once: true });
                            video.addEventListener('error', resolve, { once: true });
                        });
                    });

                    // also resolve background-image URLs used inside the grid
                    const bgPromises = [];
                    const allEls = Array.from(container.querySelectorAll('*'));
                    allEls.forEach(el => {
                        const bg = getComputedStyle(el).backgroundImage;
                        if (bg && bg !== 'none' && bg.indexOf('url') !== -1) {
                            const matches = bg.match(/url\(["']?([^"')]+)["']?\)/g);
                            if (matches) {
                                matches.forEach(m => {
                                    const url = m.replace(/url\(["']?/, '').replace(/["']?\)/, '');
                                    const p = new Promise(resolve => {
                                        const i = new Image();
                                        i.onload = i.onerror = resolve;
                                        i.src = url;
                                    });
                                    bgPromises.push(p);
                                });
                            }
                        }
                    });

                    return Promise.all([...imgPromises, ...vidPromises, ...bgPromises]);
                }

                // initialize Masonry for each grid after its media finished loading
                $grids.each(function () {
                    const grid = this;
                    waitForMedia(grid).then(function () {
                        $(grid).masonry({
                            itemSelector: '.masonry-grid-item',
                            columnWidth: '.masonry-grid-sizer',
                            percentPosition: true,
                            gutter: 20
                        });
                    });
                });
            }, 100);
        }
    }, 100);
});