<?php
namespace Core\Builder\Template_Engine;

Class Actions{
    /**
     * Return html output
     */
	public static function get_code( $args ){
		$code = array(
			'start' => '', 
			'end' => '',
			'attributes' => array(),
			'clickable_area' => 'inner_content'
		);

    	if ( isset($args['actions_settings']) && is_array($args['actions_settings']) && !empty($args['actions_settings']) ) {
			$action = $args['actions_settings'];

			if( isset($action['clickable_area']) ) $code['clickable_area'] = $action['clickable_area'];

			// if it's an icon and text component and the clickable area is set to inner content
			// force entire component to be clickable, because of the structure of the component
			if( $args['type'] == 'icon-and-text' && isset($action['clickable_area']) && $action['clickable_area'] == 'inner_content' ){
				$code['clickable_area'] = 'entire_component';
			}
    		
    		if ($action['trigger'] == 'click' && $action['action'] == 'open-page') {
    			$href = NULL;
            	$link = $action['link'];
            	switch ($link['url_type']) {
            	    case 'external':
            	        $href = $link['url'];
            	        break;
                    
            	    case 'internal':
            	        $href = get_permalink( str_replace('post_','',$link['post']) );
            	        break;
            	}
            	if ($href != NULL):
                	$target = (isset($link['new_tab']) && $link['new_tab'] == 1) ? '_blank' : null; 

					$code['attributes']['href'] = $href;
					$code['attributes']['target'] = $target;
            	endif;
    		}
    		if ($action['trigger'] == 'click' && $action['action'] == 'open-image-popup') { 
    			$image_popup = ( isset($action['image_popup']) ) ? $action['image_popup'] : null;
    			if( $image_popup ){	
    				$image = $image_popup['internal_image'];
    				$link = wp_get_attachment_url($image);
    				if ($link) {
						$code['attributes']['class'] = 'zoom';
						$code['attributes']['href'] = $link;
    				}
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
    					if($video_url) {
							$code['attributes']['data-fancybox'] = '';
							$code['attributes']['href'] = $video_url;
    					}
    				}
    				if( $video_source == 'external' ){
    					$video_url = $video_popup['external_video'];
    					if($video_url){
							$code['attributes']['data-fancybox'] = '';
							$code['attributes']['href'] = $video_url;
    					}
    				}
				}
    		}
    		if ($action['trigger'] == 'click' && $action['action'] == 'toggle-box') { 
    			$toggle_box_settings = (isset($action['toggle_box_settings'])) ? $action['toggle_box_settings'] : array( 'selector' => null );
    			$selector = $toggle_box_settings['selector'];
    			if($selector){
    				$scroll_to_box = (isset($toggle_box_settings['scroll_to_box'])) ? $toggle_box_settings['scroll_to_box'] : 0;

					$code['attributes']['class'] = 'toggle-box';
					$code['attributes']['data-selector'] = $selector;
					$code['attributes']['data-scroll-to-box'] = $scroll_to_box;
					$code['attributes']['href'] = '#';
    			}
    		}
    		if ($action['trigger'] == 'click' && $action['action'] == 'offcanvas-element') { 
    			$offcanvas_elements_settings = (isset($action['offcanvas_elements_settings'])) ? $action['offcanvas_elements_settings'] : array( 'id' => null );
    			$id = $offcanvas_elements_settings['id'];
    			if($id){
					$code['attributes']['data-offcanvas-element'] = str_replace('post_','',$id);
					$code['attributes']['href'] = '#';
    			}
    		}
			if ($action['trigger'] == 'click' && $action['action'] == 'next-post') { 
				$next_post = get_adjacent_post( false, '', false );
				$next_post_link = ($next_post) ? get_permalink( $next_post->ID ) : '';

				$code['attributes']['class'] = 'next-post';
				$code['attributes']['href'] = $next_post_link;
			}
			if ($action['trigger'] == 'click' && $action['action'] == 'previous-post') { 
				$previous_post = get_adjacent_post( false, '', true );
				$previous_post_link = ($previous_post) ? get_permalink( $previous_post->ID ) : '';

				$code['attributes']['class'] = 'previous-post';
				$code['attributes']['href'] = $previous_post_link;
			}
			if ($action['trigger'] == 'click' && $action['action'] == 'interact-slider') { 
				$interact_settings = (isset($action['interact_slider_settings'])) ? $action['interact_slider_settings'] : array( 'slider_uid' => null );
				$slider_uid = $interact_settings['slider_uid'];
				if($slider_uid){
					$interaction_type = (isset($interact_settings['interaction_type'])) ? $interact_settings['interaction_type'] : 'next';
					$slider_actions = array(
						'next' => 'go-to-next-slide',
						'previous' => 'go-to-prev-slide',
						'go_to_slide' => 'go-to-slide',
					);
					$code['attributes']['class'] = $slider_actions[$interaction_type];
					if( $interaction_type == 'go_to_slide' ){
						$code['attributes']['data-slide'] = (isset($interact_settings['slide_number'])) ? $interact_settings['slide_number'] : 1;
					}
					if( isset($interact_settings['scroll_to_slider']) && $interact_settings['scroll_to_slider'] ){
						$code['attributes']['data-scroll'] = '1';
					}
					$code['attributes']['data-slider-uid'] = $slider_uid;
					$code['attributes']['href'] = '#';
				}
			}
		};

		// if the clickable area is extra_layer, we will append a class to place the link over the entire component
		if( $code['clickable_area'] === 'extra_layer' ){
			if( isset($code['attributes']['class']) ){
				$code['attributes']['class'] .= ' cover-all';
			} else {
				$code['attributes']['class'] = 'cover-all';
			}
		}

		if( !empty($code['attributes']) && isset($code['attributes']['href']) && $code['attributes']['href'] != '' ){			
			$start = '<a';
			foreach ($code['attributes'] as $attr => $value) {
				if ($value !== null) {
					$start .= ' '.$attr.'="'.$value.'"';
				}
			}
			$start .= '>';
			$code['start'] = $start;
			$code['end'] = '</a>';
		}

		// if the clickable area is extra_layer, we will use just the start code
		if( $code['clickable_area'] === 'extra_layer' ){
			$code['start'] = $code['start'].$code['end'];
			$code['end'] = '';
		}

    	return $code;
    }
}