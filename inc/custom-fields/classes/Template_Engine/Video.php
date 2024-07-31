<?php
namespace Theme_Custom_Fields\Template_Engine;

Class Video{
    public static function get_video_data( $args ){
        $video_data = array( 'url' => null, 'code' => null, 'styles' => '' );

        $video_source = ( isset($args['video_source']) ) ? $args['video_source'] : 'selfhosted';
        $video_type = ( isset($args['video_type']) ) ? $args['video_type'] : 'popable';

        $video_settings = (isset($args['video_settings'])) ? $args['video_settings'] : array(
            'loop' => 0,
            'muted' => 0,
            'autoplay' => 0,
            'opacity' => 100
        );

        $video_opacity = (isset($args['video_opacity']) && $args['video_opacity'] ) ? $args['video_opacity'] : $video_settings['opacity'];
        $video_data['styles'] = ($video_opacity != 100) ? 'opacity:'.($video_opacity/100).';' : ''; 

        if( $video_source == 'selfhosted' ){
            $videos = ( isset($args['bgvideo']) ) ? $args['bgvideo'] : array();
            $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
            if($video_id) {
            	$video_url = wp_get_attachment_url($video_id);
                if($video_url){
                    $poster = ( $videos['poster'] ) ? wp_get_attachment_url( $videos['poster'] ) : null;
                    $video_data['url'] = $video_url;
                    $video_data['code'] = '<video ';
                    if( $video_type == 'playable' ) $video_data['code'] .= ' controls';
                    if( $video_settings['muted'] ) $video_data['code'] .= ' muted="muted"';
                    if( $video_settings['loop'] ) $video_data['code'] .= ' loop';
                    if( $poster ) $video_data['code'] .= ' poster="'.$poster.'"';
                    if( $video_settings['autoplay'] ) $video_data['code'] .= ' autoplay';
                    $video_data['code'] .= '><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
                }
            }
        }

        if( $video_source == 'external' ){
            $video_url = $args['external_url'];
            if( $video_url ){
                $video_data['url'] = $video_url;

                // $video_id = get_video_details_from_url($video_url)['id'];

                $video_args = array(
                    'width' => '100%',
                    'height' => '100%',
                    'noInfo' => '&showinfo=0&rel=0',
                    'autoplay' => '&autoplay=0'
                );
                if( $video_type != 'playable' ) $video_args['noControls'] = '&controls=0';
                if( $video_settings['muted'] ) $video_args['mute'] = '&mute=1';
                if( $video_settings['autoplay'] ) $video_args['autoplay'] = '&autoplay=1';
                if( $video_settings['loop'] ) $video_args['loop'] = '&loop=1';            

                $video_data['code'] = wp_oembed_get( $video_url, $video_args );
            }
        }

    	return $video_data;
    }

    /**
     * Add params to wp_oembed_get() function
     */
    public static function filter_oembed_result(){
        add_filter('oembed_result', function($html, $url, $args) {
            $add_params = 'embed/$1';
            if( isset($args['noControls']) ) $add_params .= $args['noControls'];
            if( isset($args['autoplay']) ) $add_params .= $args['autoplay'];
            if( isset($args['muted']) ) $add_params .= $args['muted'];
            if( isset($args['loop']) ) $add_params .= $args['loop'];
            if( isset($args['noInfo']) ) $add_params .= $args['noInfo'];
        
            $return = preg_replace('@embed/([^"&]*)@', $add_params, $html);
            return $return;
        }, 10, 3);
    }
}