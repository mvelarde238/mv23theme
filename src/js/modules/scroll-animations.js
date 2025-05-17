(function($,c){      
    $(function() {
    	var animatedElements = $('[data-scroll-animations]');

    	if (MV23_GLOBALS.scrollAnimations && animatedElements.length > 0) {
            gsap.registerPlugin(ScrollTrigger); 

            for (var i = 0; i < animatedElements.length; i++) {
    			var elem = animatedElements[i],
                    scrollAnimations = JSON.parse(elem.dataset.scrollAnimations);

                if( scrollAnimations.length > 0 ){
                    scrollAnimations.forEach(group => {
                        var triggerElement = ( group['trigger_element'] != 'this' ) ? $(elem).find(group['trigger_element']) : elem;

                        if( triggerElement.length ){
                            for (let index = 0; index < triggerElement.length; index++) {
                                var _tgrEl = triggerElement[index];
                                addAnimation(_tgrEl, group); 
                            }
                        } else {
                            addAnimation(triggerElement, group);
                        }
                    });
                }
            }
    	}

        function addAnimation(triggerElement,group){
            var scrollTriggerOptions = {
                trigger: triggerElement,
                start: group['start'],
                end: group['end'],
                toggleActions: group['toggle_actions'],
                scrub: false
            };

            if( group['end'] != '+=0' ) scrollTriggerOptions.scrub = true;
            if( group['add_indicators'] == '1') scrollTriggerOptions.markers = true; 

            // pin settings
            if(group['set_pin']) {
                var pinned_element = null, 
                    pin_settings = group['pin_settings'],
                    push_followers = pin_settings['push_followers'] ?? true;

                if( pin_settings['pinned_el'] === 'trigger_el' ) pinned_element = triggerElement;
                if( pin_settings['pinned_el'] === 'selector' ) pinned_element = pin_settings['selector'];

                if( pinned_element ) {
                    scrollTriggerOptions.pin = pinned_element;
                    scrollTriggerOptions.pinSpacing = push_followers;
                }
            }

            if( group['trigger_carrusel'] ){
                scrollTriggerOptions.onUpdate = gsapScroll => { trigger_carrusel(gsapScroll, triggerElement) };
            } 

            var timeline = gsap.timeline({
                scrollTrigger: scrollTriggerOptions
            });

            if( group['timeline'].length ){
                group['timeline'].forEach(tween_obj => {
                    var _tweenElem = get_tweenElem(triggerElement, tween_obj[0]);
                    var from = normalize_properties(tween_obj[1]);
                    var to = normalize_properties(tween_obj[2]);
                    var position = (tween_obj[3] != '') ? tween_obj[3] : "+=0";

                    if( Object.keys(to).length > 0 && Object.keys(from).length > 0 ){
                        timeline.fromTo(_tweenElem, from, to, position);
                    } else {
                        if( Object.keys(from).length > 0 ){
                            timeline.from(_tweenElem, from, position);
                        }
                        if( Object.keys(to).length > 0 ){
                            timeline.to(_tweenElem, to, position);
                        }
                    }
                });
            }
        }

        function normalize_properties(obj){
            if( obj.hasOwnProperty('duration') ) obj.duration = parseFloat(obj.duration);
            if( obj.hasOwnProperty('delay') ) obj.delay = parseFloat(obj.delay);
            if( obj.hasOwnProperty('yoyo') ) obj.yoyo = true;
            if( obj.hasOwnProperty('repeat') ) obj.repeat = parseInt(obj.repeat);
            return obj;
        }

        function get_tweenElem(triggerElement, obj){
            var tweenElem = null;
            switch ( obj['el'] ) {
                case 'selector':
                    tweenElem = $(triggerElement).find(obj['selector']);
                    break;
                    
                case 'outer_selector':
                    tweenElem = $(obj['selector'])[0];
                    break;

                default:
                    tweenElem = triggerElement;
                    break;
            }
            return tweenElem;
        }

        function trigger_carrusel(gsapScroll, triggerElement){
            var progress = gsapScroll.progress;
            var carrusels = ( $(triggerElement).hasClass('carrusel') ) ? $(triggerElement) : $(triggerElement).find('.carrusel');
            if( carrusels.length ){
                for (var i = 0; i < carrusels.length; i++) {
                    var carrusel_uid = $(carrusels[i]).attr('data-tns-uid');
                    var carrusel = MV23_GLOBALS.carousels[carrusel_uid];
                    if( carrusel ){
                        var nth_slides = carrusel.getInfo().slideCount;
                        for (let i = 1; i <= nth_slides; i++) {
                            if( progress >= ( (1/nth_slides)*(i-1) ) && progress <= ( (1/nth_slides)*i ) ){
                                var slide_should_be_here = i - 1; // slide index starts in 0
                                carrusel.goTo(slide_should_be_here);
                            }
                        }
                    }
                }
            }
        }
 	});
})(jQuery,console.log);