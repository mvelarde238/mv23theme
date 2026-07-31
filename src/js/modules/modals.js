(function($,c){      
    $(function() {
        var modals = document.getElementsByClassName('theme-modal');

        for (var i = 0; i < modals.length; i++) {
            var el = modals[i],
                closeOnClick = el.dataset['closeOnClick'];

            $(el).attr('role','dialog').attr('aria-modal','true').attr('aria-hidden','true');

            $(el).modal({ 
                dismissible:true, 
                opacity:.6, 
                inDuration:300,
                outDuration: MV23_GLOBALS.modal.outDuration, 
                startingTop: '2%', 
                endingTop: '5%', 
                onOpenStart: function(modal, trigger) { 
                    $(trigger).css('z-index','initial'); 
                    $(modal).attr('aria-hidden','false');
                    $(trigger).attr('aria-expanded','true');
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
                    if (trigger) {
                        trigger.focus();
                        $(trigger).attr('aria-expanded','false');
                    }

                    var empty_on_close = $(modal).hasClass('empty-on-close');
                    if(empty_on_close) $(modal).find('.modal-content').empty();

                    modal.setAttribute('aria-hidden','true');
                } 
            });
    
            if( closeOnClick ){
                $(el).find('a').click(function(){
                    $(el).modal('close');
                });
            }
        }

        $('.modal-trigger')
            .modal()
            .css('z-index',25)
            .attr('aria-haspopup','true')
            .attr('aria-expanded','false')
            .attr('aria-controls',function(){
                return $(this).attr('data-target');
            });
    });
})(jQuery,console.log);