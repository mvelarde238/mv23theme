function create_theme_modal( el ){
    var closeOnClick = el.dataset['closeOnClick'];

    jQuery(el).attr('role','dialog').attr('aria-modal','true').attr('aria-hidden','true');

    jQuery(el).modal({ 
        dismissible:true, 
        opacity:.6, 
        inDuration:300,
        outDuration: MV23_GLOBALS.modal.outDuration, 
        startingTop: '2%', 
        endingTop: '5%', 
        onOpenStart: function(modal, trigger) { 
            jQuery(trigger).css('z-index','initial'); 
            jQuery(modal).attr('aria-hidden','false');
            jQuery(trigger).attr('aria-expanded','true');
        },
        onOpenEnd: function(modal, trigger) {
            // focus the first focusable element in the modal when it opens
            const focusableElements = modal.querySelectorAll('a, button, input, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        },
        onCloseEnd: function(modal, trigger) {
            // on close blur the active element to remove focus from any element inside the offcanvas
            if (document.activeElement) {
                document.activeElement.blur();
            }

            // return focus to the trigger element
            if (trigger && trigger instanceof HTMLElement && ally.is.focusable( trigger )) {
                trigger.focus();
                jQuery(trigger).attr('aria-expanded','false');
            }

            // Empty the modal content when the popup is closed
            var empty_on_close = jQuery(modal).hasClass('empty-on-close');
            if(empty_on_close) jQuery(modal).find('.modal-content').empty();

            modal.setAttribute('aria-hidden','true');
        } 
    });
    
    if( closeOnClick ){
        jQuery(el).find('a').click(function(){
            jQuery(el).modal('close');
        });
    }
}