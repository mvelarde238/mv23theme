(function($,c){      
    $(function() {
        var modals = document.getElementsByClassName('theme-modal');

        for (var i = 0; i < modals.length; i++) {
            const el = modals[i];
            create_theme_modal(el);
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