// ****************************************************************************************************
// INIT GLOBAL VAR FOR ALL MODULES
// ****************************************************************************************************
var viewport = updateViewportDimensions(),
	$_GET = {},
	is_inicio = jQuery('body').hasClass('home'),
	is_checkout = jQuery('body').hasClass('woocommerce-checkout');

const DEBUG = MV23_GLOBALS.debug || false;

// Wait for GSAP to be loaded and register ScrollTrigger if needed
document.addEventListener('DOMContentLoaded', function() {
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
}); 

do_get_implementation();
remove_empty_paragraphs();

document.addEventListener("DOMContentLoaded", () => {
	if (MV23_GLOBALS.adjustScrollPosition){
		setTimeout(() => {
			const hash = window.location.hash;
			if (hash) {
				adjustScrollPosition(hash);
			}
		}, 400);
	}
});