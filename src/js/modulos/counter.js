function animateValue(elem, start, end, duration) {
    // assumes integer values for start and end
    
    var obj = elem;
    var range = end - start;
    // no timer shorter than 50ms (not really visible any way)
    var minTimer = 50;
    // calc step time to show all interediate values
    var stepTime = Math.abs(Math.floor(duration / range));
    
    // never go below minTimer
    stepTime = Math.max(stepTime, minTimer);
    
    // get current time and calculate desired end time
    var startTime = new Date().getTime();
    var endTime = startTime + duration;
    var timer;
  
    function run() {
        var now = new Date().getTime();
        var remaining = Math.max((endTime - now) / duration, 0);
        var value = Math.round(end - (remaining * range));
        obj.innerHTML = new Intl.NumberFormat(["ban", "id"]).format(value);
        if (value == end) {
            clearInterval(timer);
        }
    }
    
    timer = setInterval(run, stepTime);
    run();
}


(function($,c){      
    $(function() {

        var counters = document.getElementsByClassName('counter');

        if (counters.length > 0) {
            for (var i = 0; i < counters.length; i++) {
                var elem = counters[i];

                $(elem).waypoint(function(direction) {
                    if (direction === 'down') {
                        var start = (this.element.dataset.start) ? parseInt(this.element.dataset.start) : 0,
                            end = (this.element.dataset.number) ? parseInt(this.element.dataset.number) : 100,
                            duration = (this.element.dataset.duration) ? parseInt(this.element.dataset.duration) : 1000;

                        animateValue(this.element, start, end, duration);
                    }
                }, {
                    offset: 'bottom-in-view'
                });
            }
        }

    });
})(jQuery,console.log);