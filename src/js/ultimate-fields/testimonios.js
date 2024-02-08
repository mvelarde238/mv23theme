(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        var $testimonioModal = $('#testimonio-modal'),
            $testimonioModal_content = $testimonioModal.find('.modal-content');
        
        $(document).on('click', ".testimonio__open", function(ev){
            ev.preventDefault();
            var id = $(this).attr('data-id'),
                node = document.getElementById(id),
                clone = node.cloneNode(true);

            $testimonioModal_content.html(clone);
            $testimonioModal.modal('open')
        });
        
        // --------------------------------------------------------------------------------------------------------------
    });
})(jQuery,console.log); 