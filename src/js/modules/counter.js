(function ($, c) {
    $(function () {

        if( !MV23_GLOBALS.scrollAnimations ) return;

        gsap.registerPlugin(ScrollTrigger);

        document.querySelectorAll('.counter').forEach(function (counter) {
            var numberString = counter.getAttribute('data-number');
            var end          = parseFloat(numberString);
            var duration     = parseInt(counter.getAttribute('data-duration'), 10) / 1000; // GSAP usa segundos
            var formatNumber = counter.getAttribute('data-format') === '1';
            var thousandsSep = counter.getAttribute('data-thousands-sep') || '.';
            var decimalSep   = counter.getAttribute('data-decimal-sep') || ',';
            var decimalPart  = (numberString.split('.')[1] || '').split(/[eE]/)[0];
            var decimals     = decimalPart.length;
            var counterElement = counter.querySelector('.counter-number');

            function formatValue(value) {
                var parts = Number(value).toFixed(decimals).split('.');
                if (formatNumber) {
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);
                }
                return parts[0] + (decimals > 0 ? (formatNumber ? decimalSep : '.') + parts[1] : '');
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