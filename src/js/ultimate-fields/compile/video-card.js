(function($,c){      
    function youtube_parser(url){
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        var match = url.match(regExp);
        return (match&&match[7].length==11)? match[7] : false;
    }

    function vimeo_parser(url){
        var regExp = /:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
        var match = url.match(regExp);
        return (match)? match[2] : false;
    }

    function dailymotion_parser(url) {
        var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
        if (m !== null) {
            if(m[4] !== undefined) {
                return m[4];
            }
            return m[2];
        }
        return null;
    }

    document.addEventListener('DOMContentLoaded', function() {
    	var currentVideo = null;

        $('.has-video-background').hover(function(){
        	if (currentVideo != null) { currentVideo.trigger('pause') }
        	
        	currentVideo = $(this).find('video');
        	currentVideo[0].play();

        } , function(){
            currentVideo.trigger('pause');
        });

        $('body').on('click', '.zoom-video', function(ev){
            ev.preventDefault();

            var videoUrl = $(this).attr('href');
            var $videoWrapper = $('#video-modal').find('.video-responsive');
            var currentVideo = document.getElementById('video-modal__video');
            var source = document.createElement('source');

            var fuentes_externas = ['youtube','vimeo','dailymotion'];
            var fuente_del_video = '';
            
            for (var i = 0; i < fuentes_externas.length; i++) {
                if( videoUrl.indexOf(fuentes_externas[i]) != -1 ){
                    fuente_del_video = fuentes_externas[i];
                    break;
                }
            }

            switch( fuente_del_video ){
                case 'youtube':
                    var video_id = youtube_parser(videoUrl);
                    if(video_id){
                        $videoWrapper.html('<iframe src="https://www.youtube.com/embed/'+ video_id +'?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>');
                    }
                    $('#video-modal').modal('open');
                    break;
                case 'vimeo':
                    var video_id = vimeo_parser(videoUrl);
                    if(video_id){
                        $videoWrapper.html('<iframe src="https://player.vimeo.com/video/' + video_id + '?autoplay=1&loop=1&autopause=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
                    }
                    $('#video-modal').modal('open');
                    break;
                case 'dailymotion':
                    var video_id = dailymotion_parser(videoUrl);
                    if(video_id){
                            $videoWrapper.html('<iframe frameborder="0" src="//www.dailymotion.com/embed/video/'+video_id+'" allowfullscreen></iframe>');
                    }
                    $('#video-modal').modal('open');
                    break;
                default:
                    $videoWrapper.html('<video controls autoplay><source src="'+videoUrl+'"></video>');
                    $('#video-modal').modal('open');
                    break;
            }
        });

    });
})(jQuery,console.log); 