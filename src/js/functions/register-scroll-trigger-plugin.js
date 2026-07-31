function register_scroll_trigger_plugin() {
    // Wait for GSAP to be loaded and register ScrollTrigger if needed
	if (typeof window.gsap !== 'undefined' && MV23_GLOBALS.scrollAnimations) {
		if (window.ScrollTrigger) {
			if (DEBUG) console.log('GSAP ScrollTrigger already available');
		} else if (window.gsap.ScrollTrigger) {
			window.ScrollTrigger = window.gsap.ScrollTrigger;
			if (DEBUG) console.log('GSAP ScrollTrigger registered successfully');
		} else {
			if (DEBUG) console.warn('ScrollTrigger not found in GSAP');
		}
	} else {
		if (DEBUG) console.warn('GSAP not loaded or scroll animations disabled');
	}
}