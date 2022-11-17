(function($,c){      
    $(function() {
    	var animatedElements = $('[data-sa-trigger]');

    	if (animatedElements.length > 0) {
            var controller = new ScrollMagic.Controller();

            for (var i = 0; i < animatedElements.length; i++) {
    			var elem = animatedElements[i];

                var triggerElement = ( elem.dataset.saTrigger != 'this' ) ? elem.dataset.saTrigger : elem; 
                var duration = elem.dataset.saDuration;
                var triggerHook = elem.dataset.saHook;
                var offset = elem.dataset.saOffset;
                var tweenElem = ( elem.dataset.saElement != 'this' ) ? $(elem).find(elem.dataset.saElement) : elem;                     
                var from = JSON.parse(elem.dataset.saFrom);
                var to = JSON.parse(elem.dataset.saTo);
                var addIndicators = elem.dataset.saIndicators;

                // var timeline = new TimelineMax();
                // timeline.from(tweenElem, 1, from);
                // timeline.to(tweenElem, 1, to);
                var tween = TweenMax.fromTo(tweenElem, 1, from, to );

                var scene = new ScrollMagic.Scene({
                        triggerElement: triggerElement, 
                        duration: duration, 
                        triggerHook: triggerHook, 
                        offset: offset
                    })
                    .setTween(tween)
                    .addTo(controller);

                if(addIndicators == '1') scene.addIndicators();  
            }

    	}
 	});
})(jQuery,console.log);