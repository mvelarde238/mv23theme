<?php
use Core\Builder\Template_Engine;
use Core\Builder\Template_Engine\Video as Video_Template_Engine;

function print_theme_gallery( $atts ) {
	$a = shortcode_atts( array(
        'ids' => '',
        'd_columns' => 4,
        'l_columns' => 3,
        't_columns' => 2,
        'm_columns' => 2,
        'd_gap' => 4,
        'l_gap' => 4,
        't_gap' => 4,
        'm_gap' => 4,
        'size' => 'large',
        'targetsize' => 'full',
        'link' => 'file',
        'aspectratio' => '',
        'display' => 'default',
        'wpmf_folder_id' => null,
        'gallery_id' => null,
        'marquee_speed' => 18,
        'fade_color' => '#ffffff',
        'size_styles' => ''
    ), $atts );

    $attachments = array();

    if( $a['wpmf_folder_id'] ){

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'meta_key' => 'wpmf_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'wpmf-category',
                    'field' => 'term_id',
                    'terms' => array($a['wpmf_folder_id']),
                    'include_children' => true
                )
            )
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                array_push( $attachments, get_the_ID() );
            endwhile;
            wp_reset_query();
        endif;

    } else {
        $attachments = explode(',',$a['ids']);
    }

	ob_start();
    if( is_array($attachments) && count($attachments) > 0 ){
        $rand_id = 'gallery_'.substr(md5(microtime()),rand(0,26),5);
        $gallery_id = ( $a['gallery_id'] ) ? : $rand_id;

        $carrusel_styles = array();
        if( $a['aspectratio'] ) $carrusel_styles[] = '--aspect-ratio:'.$a['aspectratio'];
        if( $a['display'] != 'slider' ) {
            $carrusel_styles[] = '--d-gap:'.$a['d_gap'].'px';
            $carrusel_styles[] = '--l-gap:'.$a['l_gap'].'px';
            $carrusel_styles[] = '--t-gap:'.$a['t_gap'].'px';
            $carrusel_styles[] = '--m-gap:'.$a['m_gap'].'px';
            $carrusel_styles[] = '--d-columns:'.$a['d_columns'];
            $carrusel_styles[] = '--l-columns:'.$a['l_columns'];
            $carrusel_styles[] = '--t-columns:'.$a['t_columns'];
            $carrusel_styles[] = '--m-columns:'.$a['m_columns'];
        }
        if( $a['display'] === 'marquee' ) {
            $carrusel_styles[] = '--fade-color:'.$a['fade_color'];
        }

        if( $a['display'] == 'slider' ){ ?>
            <div class="theme-gallery carrusel carrusel-inside-component" data-controls-position="center" style="<?=implode(';',$carrusel_styles)?>">
            <div class="carrusel__slider" 
                data-show-controls="1" 
                data-show-nav="1" 
                data-autoplay="0" 
                data-nav-position="bottom"
                data-mobile="<?=$a['m_columns']?>"
                data-tablet="<?=$a['t_columns']?>"
                data-laptop="<?=$a['l_columns']?>"
                data-desktop="<?=$a['d_columns']?>"
                data-speed="450"
                data-touch="1"
                data-mobile-gutter="<?=$a['m_gap']?>"
                data-tablet-gutter="<?=$a['t_gap']?>"
                data-laptop-gutter="<?=$a['l_gap']?>"
                data-desktop-gutter="<?=$a['d_gap']?>">
            <?php
        } else if ( $a['display'] == 'masonry' ) {
            echo '<div class="theme-gallery theme-gallery--masonry" style="'.implode(';', $carrusel_styles).'">';
            echo '<div class="theme-gallery theme-gallery__item-sizer"></div>';

        } else if ( $a['display'] == 'marquee' ) {
            echo '<div class="theme-gallery theme-gallery__marquee" data-speed="'.$a['marquee_speed'].'" style="'.implode(';', $carrusel_styles).'">';
            echo '<div class="theme-gallery__marquee-track">';

        } else {
            echo '<div class="theme-gallery has-columns" style="'.implode(';', $carrusel_styles).'">';
        }

        foreach ($attachments as $attachment_id) :
            $type = get_post_mime_type($attachment_id);
            $attachment_type = '';
            $is_remote_video = false;

            echo '<div class="theme-gallery__item">';
    
            switch ($type) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/gif':
                    $attachment_type = 'image';
                    $attach_url = wp_get_attachment_image_url($attachment_id, $a['size']);

                    // WP MEDIA FOLDER PLUGIN: REMOTE VIDEO SUPPORT
                    $remote_video = get_post_meta($attachment_id,'wpmf_remote_video_link',true);
                    if ( !empty($remote_video) ) {
                        $is_remote_video = true;
                        $attachment_type = 'video';
                        $url = $remote_video;

                        $video_args = array(
                            'video_source' => 'external',
                            'external_url' => $url,
                            'video_settings' => array()
                        );
                        if( !empty($a['size_styles'])){
                            $video_args['video_settings']['styles'] = $a['size_styles'];
                        }

                        $video_data = Video_Template_Engine::get_video_data( $video_args );
                        if( !empty($video_data['code']) ) echo $video_data['code'];

                    } else { // is a normal attachment image
                        $url = $attach_url;
                        $image_attrs = array();
                        $image_attrs['additional_attributes'] = array('src="'.$attach_url.'"');
    
                        if( !empty($a['size_styles'])){
                            $image_attrs['additional_attributes'][] = 'style="'.$a['size_styles'].'"';
                        }
                        echo '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                    }

                    break;

                case 'video/mpeg':
                case 'video/mp4': 
                case 'video/quicktime':
                    $attachment_type = 'video';
                    $url = wp_get_attachment_url($attachment_id);

                    $video_args = array(
                        'video' => array(
                            'videos' => array($attachment_id),
                            'poster' => null
                        ),
                        'video_settings' => array()
                    );

                    if ($a['link'] != 'none'){
                        $video_args['video_settings']['controls'] = 0;
                        $video_args['video_settings']['autoplay'] = 1;
                        $video_args['video_settings']['muted'] = 1;
                        $video_args['video_settings']['loop'] = 1;
                    } 

                    if( !empty($a['size_styles'])){
                        $video_args['video_settings']['styles'] = $a['size_styles'];
                    }

                    $video_data = Video_Template_Engine::get_video_data( $video_args );
                    if( !empty($video_data['code']) ) echo $video_data['code'];
                    
                    break;

                case 'application/pdf':
                    $attachment_type = 'pdf';
                    $url = wp_get_attachment_url($attachment_id);

                    $image_attrs = array();
                    $image_attrs['additional_attributes'] = array(
                        'src="'.get_template_directory_uri().'/assets/images/pdf_poster.jpg"'
                    );

                    if( !empty($a['size_styles'])){
                        $image_attrs['additional_attributes'][] = 'style="'.$a['size_styles'].'"';
                    }
                    echo '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                    break;
                
                default:
                    $url = wp_get_attachment_url($attachment_id);
                    // echo '<p>'.$type.'</p>';
            }

            // Atachment Link
            if($a['link'] != 'none') {
                $link_class = 'cover-all';
                switch ($a['link']) {
                    case 'file':
                        $attachment_link = ($attachment_type === 'image') ? wp_get_attachment_image_url($attachment_id, $a['targetsize']) : $url;
                        break;

                    case 'post':
                        $attachment_link = get_attachment_link($attachment_id);
                        break;

                    case 'custom':
                        $wpmf_link = get_post_meta($attachment_id, '_wpmf_gallery_custom_image_link', true);
                        $attachment_link = $wpmf_link ? $wpmf_link : '#';
                        break;
                    
                    default:
                        $attachment_link = $url;
                        break;
                }
                $caption = ( wp_get_attachment_caption($attachment_id) ) ? wp_get_attachment_caption($attachment_id) : '';
                $attachment_link_html = '<a ';
                $dont_use_fancybox = array('custom', 'post', 'none');
                if(!in_array($a['link'], $dont_use_fancybox)) $attachment_link_html .= 'data-fancybox="'.$gallery_id.'" ';
                if( $a['link'] == 'custom' || $a['link'] == 'post' ){
                    // get attachment target
                    $attachment_target = get_post_meta($attachment_id, '_gallery_link_target', true);
                    if( $attachment_target && $attachment_target == '_blank' ){
                        $attachment_link_html .= 'target="_blank" rel="noopener noreferrer" ';
                    } 
                }
                $attachment_link_html .= 'href="'.$attachment_link.'" class="'.$link_class.'" data-caption="'.$caption.'"';
                if( 
                    ( $attachment_type === 'video' && !$is_remote_video ) ||
                    $attachment_type === 'pdf'
                ){
                    $imagen = get_the_post_thumbnail_url( $attachment_id, 'full' );
                    $thumb_url = ($imagen) ? $imagen : get_template_directory_uri().'/assets/images/'.$attachment_type.'_poster.jpg';
                    $attachment_link_html .= ' data-thumb="'.$thumb_url.'"';
                }
                $attachment_link_html .= '></a>';
                echo $attachment_link_html;
            }
            // END Atachment Link

            echo '</div>';
        endforeach;
        
        echo '</div>';
        if( $a['display'] == 'slider' || $a['display'] == 'marquee' ) echo '</div>';
    }
	return ob_get_clean();
}
add_shortcode( 'theme_gallery', 'print_theme_gallery' );