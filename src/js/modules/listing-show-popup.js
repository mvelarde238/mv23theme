// GENERAL
(function($,c){      
    var $popupListing = $('.posts-listing--show-popup');

    if( $popupListing.length ){
        var $postModal = $('#post-modal'),
            $postModal_content = $postModal.find('.modal-content');

        $popupListing.each(function(i, e){
            var $listing = $(e);

            $listing.on('click', '.trigger-post-action', function(event){
                event.preventDefault();
                var url = this.getAttribute('href');

                $.ajax({
                    url: url,
                    beforeSend: function beforeSend() {
                        $postModal.modal('open').attr('data-status','loading');
                    },
                    success: function success(response) {
                        $postModal.attr('data-status','');
                        var content = $('.main', response);
                        if(response) {
                            $postModal_content.html( content.html() );
                        }
                    }
                });
            });
            
        });   
    }
})(jQuery,console.log);