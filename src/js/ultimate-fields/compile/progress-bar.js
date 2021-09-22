function animateWidth(elem, start, end, duration, spanElem) {
    var value = start;
    function run() {
        elem.style.width = value+'%';
        spanElem.innerHTML = value;
        if (value == end) {
            clearInterval(timer);
        }
        value++;
    }
    
    var timer = setInterval(run, 23);
    run();
}


(function($,c){      
    $(function() {

        var progressBars = document.getElementsByClassName('progress-bar');

        if (progressBars.length > 0) {
            for (var i = 0; i < progressBars.length; i++) {
                var elem = progressBars[i];

                $(elem).waypoint(function(direction) {
                    if (direction === 'down') {
                        var bar = this.element.getElementsByClassName('progress-bar__bar')[0];
                        var spanElem = this.element.getElementsByClassName('progress-bar__num')[0];
                        var start = (bar.dataset.start) ? parseInt(bar.dataset.start) : 0,
                            end = (bar.dataset.number) ? parseInt(bar.dataset.number) : 100,
                            duration = (bar.dataset.duration) ? parseInt(bar.dataset.duration) : 1000;

                        animateWidth(bar, start, end, duration, spanElem);
                    }
                }, {
                    offset: 'bottom-in-view'
                });
            }
        }

    });
})(jQuery,console.log);