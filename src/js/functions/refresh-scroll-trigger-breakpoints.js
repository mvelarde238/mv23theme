function refreshScrollTriggerBreakpoints() {
    // Refresh ScrollTrigger breakpoints after content update
    setTimeout(function() {
        if (typeof window.gsap !== 'undefined' && window.gsap.ScrollTrigger) {
            if (DEBUG) console.log('Refreshing ScrollTrigger');
            window.gsap.ScrollTrigger.refresh();
        } else if (typeof window.ScrollTrigger !== 'undefined') {
            if (DEBUG) console.log('Refreshing ScrollTrigger (global)');
            window.ScrollTrigger.refresh();
        } else {
            if (DEBUG) console.log('GSAP ScrollTrigger not available - gsap:', typeof window.gsap, 'ScrollTrigger:', typeof window.ScrollTrigger);
        }
    }, 100); // Small delay to ensure DOM updates are complete
}