(function($,c){      
    $(function() {

    	var animatedElements = $('[data-animation]');

    	if (animatedElements.length > 0) {

    		for (var i = 0; i < animatedElements.length; i++) {
    			var elem = animatedElements[i];

    			$(elem).fadeOut(0).waypoint(function(direction) {
                    if (direction === 'down') {
                        var animation = this.element.dataset.animation,
                            delay = this.element.dataset.delay,
                            options = {};

                        // options.delay = (delay) ? parseInt(delay) : 0;

						$(this.element).velocity(animation, options);
    				}
  				}, {
    				offset: 'bottom-in-view'
  				});

                $(elem).fadeOut(0).waypoint(function(direction) {
                    if (direction === 'up') {
                        $(this.element).velocity("reverse");
                    }
                }, {
                    offset: '95%'
                });
    		}
    	}
 
 	});
})(jQuery,console.log);