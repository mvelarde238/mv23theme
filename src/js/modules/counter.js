(function ($, c) {
    $(function () {

        if( !MV23_GLOBALS.scrollAnimations ) return;

        gsap.registerPlugin(ScrollTrigger);

        document.querySelectorAll('.counter').forEach(function (counter) {
            var start = parseInt(counter.getAttribute('data-start'), 10);
            var end = parseInt(counter.getAttribute('data-number'), 10);
            var duration = parseInt(counter.getAttribute('data-duration'), 10) / 1000; // GSAP usa segundos

            var timeline = gsap.timeline({
                scrollTrigger: {
                    trigger: counter,
                    start: 'top bottom',
                    toggleActions: "play none none reset"
                }
            });

            timeline.fromTo(counter,
                { innerText: start },
                {
                    innerText: end,
                    duration: duration,
                    delay: .5,
                    snap: { innerText: 1 },
                    ease: "power1.inOut"
                }
            );
        });

    });
})(jQuery, console.log);