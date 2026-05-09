(function ($, c) {
    $(function () {

        if( !MV23_GLOBALS.scrollAnimations ) return;

        gsap.registerPlugin(ScrollTrigger);

        document.querySelectorAll('.counter').forEach(function (counter) {
            var end          = parseFloat(counter.getAttribute('data-number'));
            var duration     = parseInt(counter.getAttribute('data-duration'), 10) / 1000; // GSAP usa segundos
            var formatNumber = counter.getAttribute('data-format') === '1';
            var thousandsSep = counter.getAttribute('data-thousands-sep') || '.';
            var counterElement = counter.querySelector('.counter-number');

            function formatValue(value) {
                var rounded = Math.round(value).toString();
                if (!formatNumber) return rounded;
                return rounded.replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);
            }

            var proxy = { value: 0 };

            var timeline = gsap.timeline({
                scrollTrigger: {
                    trigger: counter,
                    start: 'top bottom',
                    toggleActions: "play none none reset",
                    onLeaveBack: function() {
                        proxy.value = 0;
                        counterElement.innerText = formatValue(0);
                    }
                }
            });

            timeline.fromTo(proxy,
                { value: 0 },
                {
                    value: end,
                    duration: duration,
                    delay: .5,
                    ease: "power1.inOut",
                    onStart: function() {
                        counterElement.innerText = formatValue(0);
                    },
                    onUpdate: function() {
                        counterElement.innerText = formatValue(proxy.value);
                    },
                    onComplete: function() {
                        counterElement.innerText = formatValue(end);
                    }
                }
            );
        });

    });
})(jQuery, console.log);