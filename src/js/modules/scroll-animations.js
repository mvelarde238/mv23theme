(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
    	var animatedElements = $('[data-scroll-animations]');

    	if (MV23_GLOBALS.scrollAnimations && animatedElements.length > 0) {
            gsap.registerPlugin(ScrollTrigger); 

            for (var i = 0; i < animatedElements.length; i++) {
    			var elem = animatedElements[i],
                    scrollAnimations = JSON.parse(elem.dataset.scrollAnimations);

                if( scrollAnimations.length > 0 ){
                    scrollAnimations.forEach(group => {
                        var triggerElement = ( group['trigger_element'] != 'this' ) ? $(elem).find(group['trigger_element']) : elem;

                        // set initial rules
                        if( group['initial_rules'] && group['initial_rules'].length ){
                            group['initial_rules'].forEach(initial_rule => {
                                var target = get_target(triggerElement, initial_rule[0]);
                                if( target.length || target instanceof Element ){
                                    gsap.set(target, initial_rule[1]);
                                }
                            });
                        }

                        // add animations
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

                elem.removeAttribute('data-scroll-animations');
            }
    	}

        function addAnimation(triggerElement,group){
            var scrollTriggerOptions = {
                trigger: triggerElement,
                start: group['start'],
                toggleActions: group['toggle_actions'],
                scrub: false
            };

            if( group['end'] ) scrollTriggerOptions.end = group['end'];
            if( group['end'] != '+=0' && group['end'] != '' ) scrollTriggerOptions.scrub = true;
            if( group['add_indicators'] == '1') scrollTriggerOptions.markers = true; 
            if( group['toggle_class'] ) scrollTriggerOptions.toggleClass = group['toggle_class'];

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
                scrollTriggerOptions.onUpdate = gsapScroll => { trigger_carousel(gsapScroll, triggerElement) };
            } 

            var timeline = gsap.timeline({
                scrollTrigger: scrollTriggerOptions
            });

            if( group['timeline'].length ){
                group['timeline'].forEach(tween_obj => {
                    var _tweenElem = get_target(triggerElement, tween_obj[0]);
                    if( _tweenElem.length || _tweenElem instanceof Element ){
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
                    }
                });
            }
        }

        function normalize_properties(obj){
            if( obj.hasOwnProperty('duration') ) obj.duration = parseFloat(obj.duration);
            if( obj.hasOwnProperty('delay') ) obj.delay = parseFloat(obj.delay);
            if( obj.hasOwnProperty('repeat') ) obj.repeat = parseInt(obj.repeat);
            if( obj.hasOwnProperty('stagger') ){
                let stagger_raw = obj.stagger;

                if( typeof stagger_raw === 'string' ) {
                    const stagger_obj = stagger_raw.split('|').reduce((acc, pair) => {
                        const [key, value] = pair.split(':');
                        acc[key] = isNaN(value) ? value : Number(value);
                        return acc;
                    }, {})

                    obj.stagger = stagger_obj;
                }
            }
            return obj;
        }

        function get_target(triggerElement, obj){
            var targetElem = null;
            switch ( obj['el'] ) {
                case 'selector':
                    targetElem = $(triggerElement).find(obj['selector']);
                    break;
                    
                case 'outer_selector':
                    targetElem = $(obj['selector'])[0];
                    break;

                default:
                    targetElem = triggerElement;
                    break;
            }
            return targetElem;
        }

        function trigger_carousel(gsapScroll, triggerElement){
            var progress = gsapScroll.progress;
            var carousels = $(triggerElement).find('.carousel__slider');
            if( carousels.length ){
                for (var i = 0; i < carousels.length; i++) {
                    var carousel_uid = $(carousels[i]).attr('data-tns-uid');
                    var carousel = MV23_GLOBALS.carousels[carousel_uid];
                    if( carousel ){
                        var nth_slides = carousel.getInfo().slideCount;
                        for (let i = 1; i <= nth_slides; i++) {
                            if( progress >= ( (1/nth_slides)*(i-1) ) && progress <= ( (1/nth_slides)*i ) ){
                                var slide_should_be_here = i - 1; // slide index starts in 0
                                carousel.goTo(slide_should_be_here);
                            }
                        }
                    }
                }
            }
        }       
 	});
})(jQuery,console.log);