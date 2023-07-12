<?php
function generate_actions_code($componente){
	$actions = (isset($componente['actions'])) ? $componente['actions'] : null;
	$code = '';

	if ( is_array($actions) && count($actions)>0 ):
			foreach ($actions as $action) {
				if ($action['trigger'] == 'click' && $action['action'] == 'open-page') {
					$link = NULL;
            		$enlace = $action['enlace'];
            		switch ($enlace['url_type']) {
            		    case 'externa':
            		        $link = $enlace['url'];
            		        break;
            		    
            		    case 'interna':
            		        $link = get_permalink( str_replace('post_','',$enlace['post']) );
            		        break;
            		}
            		if ($link != NULL):
                    	$target = ($enlace['new_tab'] == 1) ? '_blank' : ''; 
                    	$code = '<a class="cover-all" href="'.$link.'" target="'.$target.'"></a>';
               		endif;
				}
				if ($action['trigger'] == 'click' && $action['action'] == 'open-image-popup') { 
					if( $componente['__type'] == 'imagen' ){
						$link = wp_get_attachment_url($componente['image']);
						$link = (!empty($link)) ? $link : wp_get_attachment_url($componente['bgi']);
					} else {
						$link = wp_get_attachment_url($componente['bgi']);
					}
            		if ($link):
                    	$code = '<a class="cover-all zoom" href="'.$link.'"></a>';
               		endif;
				}
				if ($action['trigger'] == 'click' && $action['action'] == 'open-video-popup') {
					$video_source = ( isset($componente['video_source']) ) ? $componente['video_source'] : 'selfhosted';
					if( $video_source == 'selfhosted' ){
						$videos = $componente['bgvideo'];
						$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
						if ($video_id):
							$video_url = wp_get_attachment_url($video_id);
			        		echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
			        	endif;
					}
					if( $video_source == 'youtube' ){
						$video_url = $componente['youtube_url'];
						if($video_url){
							echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
						}
					}
				}
				if ($action['trigger'] == 'click' && $action['action'] == 'toggle-box') { 
					$toggle_box_settings = (isset($action['toggle_box_settings'])) ? $action['toggle_box_settings'] : array( 'selector' => null );
					$selector = $toggle_box_settings['selector'];
					if($selector){
						$code = '<a class="cover-all toggle-box" data-selector="'.$selector.'" href="#"></a>';
					}
				}
			}
	endif;

	return $code;
}