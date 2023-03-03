(function($,c){      
    $(function() {
    	var animatedElements = $('[data-scroll-animations]');

    	if (animatedElements.length > 0) {
            var controller = new ScrollMagic.Controller();

            for (var i = 0; i < animatedElements.length; i++) {
    			var elem = animatedElements[i],
                    scrollAnimations = JSON.parse(elem.dataset.scrollAnimations);

                if( scrollAnimations.length > 0 ){
                    scrollAnimations.forEach(group => {
                        var triggerElement = ( group['trigger_element'] != 'this' ) ? $(elem).find(group['trigger_element']) : elem;           

                        if( triggerElement.length ){
                            for (let index = 0; index < triggerElement.length; index++) {
                                var _tgrEl = triggerElement[index];
                                var tweenElem = ( group['trigger_element'] != group['element']['el'] ) ? $(_tgrEl).find(group['element']['el']) : _tgrEl;
                                addAnimation(_tgrEl, tweenElem, group); 
                            }
                        } else {
                            var tweenElem = null;
                            switch ( group['element']['key'] ) {
                                case 'selector':
                                    tweenElem = $(elem).find(group['element']['el']);
                                    break;
                                    
                                case 'outer_selector':
                                    tweenElem = $(group['element']['el'])[0];
                                    break;

                                default:
                                    tweenElem = elem;
                                    break;
                            }
                            console.log(group['element']['el'],tweenElem);
                            if(tweenElem) addAnimation(triggerElement, tweenElem, group);
                        }
                    });
                }
            }
    	}

        function addAnimation(triggerElement,tweenElem,group){
            var from = JSON.parse(group['from']);
            var to = JSON.parse(group['to']);
            var addIndicators = group['add_indicators'];

            // var timeline = new TimelineMax();
            // timeline.from(tweenElem, 1, from);
            // timeline.to(tweenElem, 1, to);
            var tween = TweenMax.fromTo(tweenElem, 1, from, to );
            
            var scene = new ScrollMagic.Scene({
                triggerElement: triggerElement, 
                duration: group['duration'], 
                triggerHook: group['trigger_hook'], 
                offset: group['offset']
            })
            .setTween(tween)
            .addTo(controller);
            
            if(addIndicators == '1') scene.addIndicators();
        }
 	});
})(jQuery,console.log);