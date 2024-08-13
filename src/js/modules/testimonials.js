(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
        var $testimonialModal = $('#testimonial-modal'),
            $testimonialModal_content = $testimonialModal.find('.modal-content');
        
        $(document).on('click', ".testimonial__open", function(ev){
            ev.preventDefault();
            var id = $(this).attr('data-id'),
                node = document.getElementById(id),
                clone = node.cloneNode(true);

            $testimonialModal_content.html(clone);
            $testimonialModal.modal('open')
        });
    });
})(jQuery,console.log); 