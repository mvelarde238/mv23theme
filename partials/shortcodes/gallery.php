<?php
use Core\Builder\Template_Engine;

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
        'force_fullwidth_images' => false
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

        if( $a['display'] == 'slider' ){ ?>
            <div class="theme-gallery carrusel carrusel-inside-component" data-controls-position="center" style="--aspect-ratio:<?=$a['aspectratio']?>">
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
            echo '<div class="theme-gallery theme-gallery--masonry" style="--d-gap:'.$a['d_gap'].'px; --l-gap:'.$a['l_gap'].'px; --t-gap:'.$a['t_gap'].'px; --m-gap:'.$a['m_gap'].'px; --d-columns:'.$a['d_columns'].'; --l-columns:'.$a['l_columns'].'; --t-columns:'.$a['t_columns'].'; --m-columns:'.$a['m_columns'].'; --aspect-ratio:'.$a['aspectratio'].'"">';
            echo '<div class="theme-gallery theme-gallery__item-sizer"></div>';
        } else {
            echo '<div class="theme-gallery has-columns" style="--d-gap:'.$a['d_gap'].'px; --l-gap:'.$a['l_gap'].'px; --t-gap:'.$a['t_gap'].'px; --m-gap:'.$a['m_gap'].'px; --d-columns:'.$a['d_columns'].'; --l-columns:'.$a['l_columns'].'; --t-columns:'.$a['t_columns'].'; --m-columns:'.$a['m_columns'].'; --aspect-ratio:'.$a['aspectratio'].'">';
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
                    $url = wp_get_attachment_image_url($attachment_id, $a['size']);

                    // WP MEDIA FOLDER PLUGIN: REMOTE VIDEO SUPPORT
                    $remote_video = get_post_meta($attachment_id,'wpmf_remote_video_link',true);
                    if ( !empty($remote_video) ) {
                        $is_remote_video = true;
                        $attachment_type = 'video';
                        $url = $remote_video;
                        $media_class = ($a['link'] != 'none') ? 'popable' : 'playable';
                        ?>
                        <div class="media video has-video-background <?=$media_class?>">
                            <div class="media__element video-background" style="background-color:#000000;">
                                <?php echo wp_oembed_get($remote_video); ?>   
                            </div>
                        </div>
                        <?php
                    } else {
                        $image_attrs['additional_attributes'] = array('src="'.$url.'"');
                        if( $a['force_fullwidth_images'] ){
                            $image_attrs['additional_attributes'][] = 'style=width:100%;';
                        }
                        echo '<img '.Template_Engine::generate_attributes($image_attrs).'>';
                    }
                    break;

                case 'video/mpeg':
                case 'video/mp4': 
                case 'video/quicktime':
                    $attachment_type = 'video';
                    $url = wp_get_attachment_url($attachment_id);
                    $media_class = ($a['link'] != 'none') ? 'popable' : 'playable';
                    $video_attrs = ($a['link'] != 'none') ? '' : 'controls';
                    ?>
                    <div class="media video selfhosted has-video-background <?=$media_class?>">
                        <div class="media__element video-background" style="background-color:#000000;">
                            <video <?=$video_attrs?>>
                                <source src="<?=$url?>">
                                Your browser does not support the video tag.
                            </video>                            
                        </div>
                    </div>
                    <?php
                    break;

                case 'application/pdf':
                    $attachment_type = 'pdf';
                    $url = wp_get_attachment_url($attachment_id);
                    echo '<img src="'.get_template_directory_uri().'/assets/images/pdf_poster.jpg">';
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
                        // $link_class .= ($attachment_type === 'image') ? ' zoom' : ' zoom-video';
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
        if( $a['display'] == 'slider' ) echo '</div>';
    }
	return ob_get_clean();
}
add_shortcode( 'theme_gallery', 'print_theme_gallery' );