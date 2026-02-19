(function ($, c) {
    document.addEventListener('DOMContentLoaded', function() {
        if( !MV23_GLOBALS.scrollAnimations ) return;

        const marquees = document.querySelectorAll('.marquee');
        marquees.forEach(marquee => {
            const track = marquee.querySelector('.marquee-track');
            const words = [...track.children];
            const trackWidth = () => track.scrollWidth;
            const viewportWidth = () => window.innerWidth;
            const speed = parseInt(marquee.dataset.speed) || 40;
            const direction = marquee.dataset.direction || 'left';

            fillTrack( marquee, track, words, trackWidth, viewportWidth );

            // Crear una timeline en lugar de un tween individual para poder usar timeScale
            const marqueeTimeline = gsap.timeline({ repeat: -1 });
            marqueeTimeline.to(track, {
                x: () => direction === 'left' ? `-${trackWidth() / 2}px` : `${trackWidth() / 2}px`,
                duration: speed,
                ease: "none"
            });

            window.addEventListener('resize', () => {
                // reset animation on resize
                marqueeTimeline.kill();
                track.innerHTML = '';
                words.forEach(w => track.appendChild(w.cloneNode(true)));
                fillTrack( marquee, track, words, trackWidth, viewportWidth );
                marqueeTimeline.clear();
                marqueeTimeline.to(track, {
                    x: () => direction === 'left' ? `-${trackWidth() / 2}px` : `${trackWidth() / 2}px`,
                    duration: speed,
                    ease: "none"
                });
            });

            // stop marquee on hover and resume on mouse leave
            marquee.addEventListener('mouseenter', () => {
                marqueeTimeline.timeScale(0); // pause animation
            });
            marquee.addEventListener('mouseleave', () => {
                marqueeTimeline.timeScale(1); // resume animation
            });
        });

        // Rellenar el track con más palabras hasta que supere el ancho de pantalla
        function fillTrack( marquee, track, words, trackWidth, viewportWidth ) {
            const originalWidth = trackWidth();
            const repetitions = Math.ceil(viewportWidth() / originalWidth) + 1; // +1 para buffer
            for (let i = 0; i < repetitions; i++) {
                words.forEach(word => {
                    const clone = word.cloneNode(true);
                    track.appendChild(clone);
                });
            }
        }

    });
})(jQuery, console.log);