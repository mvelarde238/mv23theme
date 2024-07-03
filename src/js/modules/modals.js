(function($,c){      
    $(function() {
        var modals = document.getElementsByClassName('modal');

        for (var i = 0; i < modals.length; i++) {
            var el = modals[i],
                closeOnClick = el.dataset['closeOnClick'];

            $(el).modal({ dismissible:true, opacity:.6, inDuration:300,
                outDuration: MV23_GLOBALS.modal.outDuration, startingTop: '2%', endingTop: '5%', 
                onOpenStart: function(modal, trigger) { $(trigger).css('z-index','initial'); },
                onCloseEnd: function(modal, trigger) {
                    var empty_on_close = $(modal).hasClass('empty-on-close');
                    if(empty_on_close) $(modal).find('.modal-content').empty();
                    $('#video-modal .video-responsive').html(''); 
                } 
            });
    
            if( closeOnClick ){
                $(el).find('a').click(function(){
                    $(el).modal('close');
                });
            }
        }

        $('.modal-trigger').modal();
        $('.modal-trigger').css('z-index',25);
    });
})(jQuery,console.log);