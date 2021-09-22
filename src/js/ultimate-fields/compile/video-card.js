(function($,c){      
    document.addEventListener('DOMContentLoaded', function() {
    	var currentVideo = null;

        $('.card.has-video-background').hover(function(){
        	if (currentVideo != null) { currentVideo.trigger('pause') }
        	
        	currentVideo = $(this).find('video');
        	currentVideo[0].play();

        } , function(){
            currentVideo.trigger('pause');
        });

        $('body').on('click', '.zoom-video', function(ev){
            ev.preventDefault();

            var videoUrl = $(this).attr('href');
            var source = document.createElement('source');
            var currentVideo = document.getElementById('video-modal__video');
                
            source.setAttribute('src', videoUrl);
            currentVideo.appendChild(source);
            $('#video-modal').modal('open');
            currentVideo.play();
        });

    });
})(jQuery,console.log); 