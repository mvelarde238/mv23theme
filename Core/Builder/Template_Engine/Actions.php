<?php
namespace Core\Builder\Template_Engine;

Class Actions{
    /**
     * Return html output
     */
	public static function get_code( $args ){
		$code = '';

    	if (
			isset($args['actions_settings']) && 
			is_array($args['actions_settings']) &&
			isset($args['actions_settings']['actions']) && 
			is_array($args['actions_settings']['actions']) 
		){
			$actions = $args['actions_settings']['actions'];

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
                    	$target = (isset($enlace['new_tab']) && $enlace['new_tab'] == 1) ? '_blank' : ''; 
                    	$code = '<a class="cover-all" href="'.$link.'" target="'.$target.'"></a>';
               		endif;
    			}
    			if ($action['trigger'] == 'click' && $action['action'] == 'open-image-popup') { 
    				$image_popup = ( isset($action['image_popup']) ) ? $action['image_popup'] : null;
    				if( $image_popup ){	
    					$image = $image_popup['internal_image'];
    					$link = wp_get_attachment_url($image);
    					if ($link) $code = '<a class="cover-all zoom" href="'.$link.'"></a>';
    				}
    			}
    			if ($action['trigger'] == 'click' && $action['action'] == 'open-video-popup') {
    				$video_popup = ( isset($action['video_popup']) ) ? $action['video_popup'] : null;
    				if( $video_popup ){	
    					$video_source = ( isset($video_popup['video_source']) ) ? $video_popup['video_source'] : '';
    					if( $video_source == 'selfhosted' ){
    						$videos = $video_popup['internal_video'];
    						$video_url = null;
    						if( is_array($videos) ){
    							$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
    							if ($video_id) $video_url = wp_get_attachment_url($video_id);
    						}
    						if( is_string($videos) ) $video_url = $videos;
    						if($video_url) echo '<a data-fancybox class="cover-all" href="'.$video_url.'"></a>';
    					}
    					if( $video_source == 'external' ){
    						$video_url = $video_popup['external_video'];
    						if($video_url){
    							echo '<a data-fancybox class="cover-all" href="'.$video_url.'"></a>';
    						}
    					}
    				}
    			}
    			if ($action['trigger'] == 'click' && $action['action'] == 'toggle-box') { 
    				$toggle_box_settings = (isset($action['toggle_box_settings'])) ? $action['toggle_box_settings'] : array( 'selector' => null );
    				$selector = $toggle_box_settings['selector'];
    				if($selector){
    					$scroll_to_box = (isset($toggle_box_settings['scroll_to_box'])) ? $toggle_box_settings['scroll_to_box'] : 0;
    					$code = '<a class="cover-all toggle-box" data-selector="'.$selector.'" data-scroll-to-box="'.$scroll_to_box.'" href="#"></a>';
    				}
    			}
    			if ($action['trigger'] == 'click' && $action['action'] == 'offcanvas-element') { 
    				$offcanvas_elements_settings = (isset($action['offcanvas_elements_settings'])) ? $action['offcanvas_elements_settings'] : array( 'id' => null );
    				$id = $offcanvas_elements_settings['id'];
    				if($id){
    					$code = '<a class="cover-all" data-offcanvas-element="'.str_replace('post_','',$id).'" href="#"></a>';
    				}
    			}
    		}
		};

    	return $code;
    }
}