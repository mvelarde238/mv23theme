<?php
use Core\Builder\Template_Engine;
use Core\Builder\Template_Engine\Video as Video_Template_Engine;
use Core\Builder\Component\Gallery;

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
        'marquee_fade_width' => '100px',
        'marquee_direction' => 'left',
        'size_styles' => '',
        'use_placeholder_images' => false,
        'placeholders_quantity' => 8,
        'placeholders_source' => 'picsum',
        'grid_data_key' => '' // registry key for in-memory grid data (used when shortcode is generated and executed in the same request)
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

    } else if ( $a['use_placeholder_images'] ) {
        $placeholders_source = $a['placeholders_source'] ?? 'picsum';
        switch ($placeholders_source) {
            case 'picsum':
                for ($i = 0; $i < $a['placeholders_quantity']; $i++) {
                    array_push( $attachments, 'https://picsum.photos/600/500?random=' . $i );
                }
                break;
            
            case 'unsplash':
            default:
                for ($i = 0; $i < $a['placeholders_quantity']; $i++) {
                    array_push( $attachments, 'https://unsplash.it/600/500?sig=' . $i );
                }
                break;

            case 'placehold':   
                for ($i = 0; $i < $a['placeholders_quantity']; $i++) {
                    array_push( $attachments, 'https://placehold.co/600x500' );
                }
                break;
        }

        $a['link'] = 'placeholder'; // override link type since these are not real attachments
    } else {
        $attachments = explode(',',$a['ids']);
    }

	ob_start();
    if( is_array($attachments) && count($attachments) > 0 ){
        $rand_id = 'gallery_'.substr(md5(microtime()),rand(0,26),5);
        $gallery_id = ( $a['gallery_id'] ) ? : $rand_id;

        $carousel_styles = array();
        if( $a['aspectratio'] ) $carousel_styles[] = '--aspect-ratio:'.$a['aspectratio'];

        // if masonry display is selected, override gap values to ensure consistent spacing (since masonry layout isnt considering the gap values from css variables)
        if( $a['display'] == 'masonry'){
            $a['d_gap'] = 20;
            $a['l_gap'] = 20;
            $a['t_gap'] = 20;
            $a['m_gap'] = 20;
        }

        $carousel_styles[] = '--d-gap:'.$a['d_gap'].'px';
        $carousel_styles[] = '--l-gap:'.$a['l_gap'].'px';
        $carousel_styles[] = '--t-gap:'.$a['t_gap'].'px';
        $carousel_styles[] = '--m-gap:'.$a['m_gap'].'px';
        $carousel_styles[] = '--d-columns:'.$a['d_columns'];
        $carousel_styles[] = '--l-columns:'.$a['l_columns'];
        $carousel_styles[] = '--t-columns:'.$a['t_columns'];
        $carousel_styles[] = '--m-columns:'.$a['m_columns'];

        $item_attrs = array(
            'additional_classes' => ['theme-gallery__item']
        );

        if( $a['display'] == 'slider' ){ ?>
            <div class="theme-gallery carousel carousel--theme1 carousel-inside-component theme-gallery--slider" data-controls-position="center" style="<?=implode(';',$carousel_styles)?>">
            <div class="carousel__slider" 
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
                data-slider-uid="<?=$gallery_id?>"
                data-mobile-gutter="<?=$a['m_gap']?>"
                data-tablet-gutter="<?=$a['t_gap']?>"
                data-laptop-gutter="<?=$a['l_gap']?>"
                data-desktop-gutter="<?=$a['d_gap']?>">
            <?php
        } else if ( $a['display'] == 'masonry' ) {
            echo '<div class="theme-gallery has-masonry-columns" style="'.implode(';', $carousel_styles).'">';
            echo '<div class="masonry-grid-sizer"></div>';

        } else if ( $a['display'] == 'marquee' ) {
            $carousel_styles[] = '--fade-width: '.$a['marquee_fade_width'];
            echo '<div class="theme-gallery theme-gallery__marquee marquee" data-speed="'.$a['marquee_speed'].'" data-direction="'.$a['marquee_direction'].'" style="'.implode(';', $carousel_styles).'">';
            echo '<div class="marquee-track">';
            
        } else if ( $a['display'] == 'grid' ) {
            $item_attrs['additional_classes'][] = 'grid-stack-item';

            // recover grid data from shortcode attribute and convert it into an array
            $grid_data = array();
            if( !empty($a['grid_data_key']) ){
                $grid_data = Gallery::get_temp_data( $a['grid_data_key'] );
                $grid_data = is_array($grid_data) ? $grid_data : array();
                $a['grid_data'] = $grid_data;
            } else {
                $a['grid_data'] = array(
                    ['x'=>0,'y'=>0,'w'=>3,'h'=>3],
                    ['x'=>3,'y'=>0,'w'=>4,'h'=>2],
                    ['x'=>7,'y'=>0,'w'=>3,'h'=>3],
                    ['x'=>10,'y'=>0,'w'=>2,'h'=>2],
                    ['x'=>3,'y'=>2,'w'=>4,'h'=>3],
                    ['x'=>10,'y'=>2,'w'=>2,'h'=>3],
                    ['x'=>0,'y'=>3,'w'=>3,'h'=>2],
                    ['x'=>7,'y'=>3,'w'=>3,'h'=>2]
                );
            }

            echo '<div class="theme-gallery grid-stack theme-gallery--grid" style="'.implode(';', $carousel_styles).'">';
             
        } else if ( $a['display'] == 'default' ) {
            echo '<div class="theme-gallery has-columns theme-gallery--'.$a['display'].'" style="'.implode(';', $carousel_styles).'">';

        } else {
            echo '<div class="theme-gallery theme-gallery--'.$a['display'].'" style="'.implode(';', $carousel_styles).'">';
        }

        $item_counter = 0;
        foreach ($attachments as $attachment_id) :
            $type = $a['use_placeholder_images'] ? 'placeholder' : get_post_mime_type($attachment_id);
            $attachment_type = '';
            $is_remote_video = false;

            // handle grid data for grid display
            if( $a['display'] == 'grid' ){
                $item_attrs['additional_attributes'] = []; // reset per-item to avoid accumulating previous attrs
                if( isset($a['grid_data'][$item_counter]) ){
                    $item_grid_data = $a['grid_data'][$item_counter];
                    if(isset($item_grid_data['x'])) $item_attrs['additional_attributes']['gs-x'] = $item_grid_data['x'];
                    if(isset($item_grid_data['y'])) $item_attrs['additional_attributes']['gs-y'] = $item_grid_data['y'];
                    if(isset($item_grid_data['w'])) $item_attrs['additional_attributes']['gs-w'] = $item_grid_data['w'];
                    if(isset($item_grid_data['h'])) $item_attrs['additional_attributes']['gs-h'] = $item_grid_data['h'];
                }
            }

            // get the attachment output based on its type
            $the_attachment = '';
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
                        if( !empty($video_data['code']) ) $the_attachment = $video_data['code'];

                    } else { // is a normal attachment image
                        $url = $attach_url;
                        $image_attrs = array();
                        $image_attrs['additional_attributes']['src'] = $attach_url;
    
                        if( !empty($a['size_styles'])){
                            $image_attrs['additional_attributes']['style'] = $a['size_styles'];
                        }
                        $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
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
                    if( !empty($video_data['code']) ) $the_attachment = $video_data['code'];
                    
                    break;

                case 'application/pdf':
                    $attachment_type = 'pdf';
                    $url = wp_get_attachment_url($attachment_id);

                    $image_attrs = array();
                    $image_attrs['additional_attributes'] = array(
                        'src' => get_template_directory_uri().'/assets/images/pdf_poster.jpg'
                    );

                    if( !empty($a['size_styles'])){
                        $image_attrs['additional_attributes']['style'] = $a['size_styles'];
                    }
                    $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                    break;

                case 'placeholder':
                    $attachment_type = 'image';
                    $url = $attachment_id; // In this case, $attachment_id is actually the URL of the placeholder image
                    $image_attrs = array();
                    $image_attrs['additional_attributes'] = array('src' => $url);
    
                    if( !empty($a['size_styles'])){
                        $image_attrs['additional_attributes']['style'] = $a['size_styles'];
                    }
                    $the_attachment = '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                    break;
                
                default:
                    $url = wp_get_attachment_url($attachment_id);
                    $the_attachment = '<p>'.$type.'</p>';
            }

            // Atachment Link
            $attachment_link_start = '<a ';
            if($a['link'] != 'none') {
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
                $dont_use_fancybox = array('custom', 'post', 'none');
                if(!in_array($a['link'], $dont_use_fancybox)) $attachment_link_start .= 'data-fancybox="'.$gallery_id.'" ';
                if( $a['link'] == 'custom' || $a['link'] == 'post' ){
                    // get attachment target
                    $attachment_target = get_post_meta($attachment_id, '_gallery_link_target', true);
                    if( $attachment_target && $attachment_target == '_blank' ){
                        $attachment_link_start .= 'target="_blank" rel="noopener noreferrer" ';
                    } 
                }
                $attachment_link_start .= 'href="'.$attachment_link.'" data-caption="'.$caption.'"';
                if( 
                    ( $attachment_type === 'video' && !$is_remote_video ) ||
                    $attachment_type === 'pdf'
                ){
                    $imagen = get_the_post_thumbnail_url( $attachment_id, 'full' );
                    $thumb_url = ($imagen) ? $imagen : get_template_directory_uri().'/assets/images/'.$attachment_type.'_poster.jpg';
                    $attachment_link_start .= ' data-thumb="'.$thumb_url.'"';
                }
                $attachment_link_start .= '>';
            }
            $attachment_link_end = '</a>';
            // END Atachment Link

            if( $a['display'] == 'masonry' ) $item_attrs['additional_classes'][] = 'masonry-grid-item';

            echo '<div '.Template_Engine::generate_attributes($item_attrs).'>';
            if ( $a['display'] == 'grid' ) echo '<div class="grid-stack-item-content">';
            if ( $a['link'] != 'none') echo $attachment_link_start;
            echo $the_attachment;
            if ( $a['link'] != 'none') echo $attachment_link_end;
            if ( $a['display'] == 'grid' ) echo '</div>'; // close grid-stack-item-content
            echo '</div>';
            $item_counter++;
        endforeach;
        
        echo '</div>';
        if( $a['display'] == 'slider' || $a['display'] == 'marquee' ) echo '</div>';
    }
	return ob_get_clean();
}
add_shortcode( 'theme_gallery', 'print_theme_gallery' );