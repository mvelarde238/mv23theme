<?php
function print_theme_gallery( $atts ) {
	$a = shortcode_atts( array(
        'ids' => '',
        'columns' => 4,
        'l_gap' => '4px',
        't_gap' => '4px',
        'm_gap' => '4px',
        'size' => 'large',
        'targetsize' => 'full',
        'link' => 'file',
        'aspectratio' => '',
        'display' => 'default',
        'wpmf_folder_id' => null,
        'gallery_id' => null
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
                data-mobile="2"
                data-tablet="3"
                data-laptop="<?=$a['columns']?>"
                data-desktop="<?=$a['columns']?>"
                data-speed="450"
                data-mobile-gutter="4"
                data-tablet-gutter="4"
                data-laptop-gutter="4"
                data-desktop-gutter="4">
            <?php
        } else if ( $a['display'] == 'masonry' ) {
            echo '<div class="theme-gallery theme-gallery--masonry" style="--d-columns:'.$a['columns'].'; --l-columns:'.$a['columns'].'; --t-columns:3; --m-columns:2; --aspect-ratio:'.$a['aspectratio'].'"">';
            echo '<div class="theme-gallery theme-gallery__item-sizer"></div>';
        } else {
            echo '<div class="theme-gallery has-columns" style="--d-gap:'.$a['l_gap'].'; --l-gap:'.$a['l_gap'].'; --t-gap:'.$a['t_gap'].'; --m-gap:'.$a['m_gap'].'; --d-columns:'.$a['columns'].'; --l-columns:'.$a['columns'].'; --t-columns:3; --m-columns:2; --aspect-ratio:'.$a['aspectratio'].'">';
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
                        echo '<img src="'.$url.'">';
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
                        $attachment_link = '#';
                        // TODO: CHECK HOW TO GET THIS METADATA EN WP MEDIA FOLDER
                        break;
                    
                    default:
                        $attachment_link = $url;
                        break;
                }
                $caption = ( wp_get_attachment_caption($attachment_id) ) ? wp_get_attachment_caption($attachment_id) : '';
                $attachment_link_html = '<a data-fancybox="'.$gallery_id.'" href="'.$attachment_link.'" class="'.$link_class.'" data-caption="'.$caption.'"';
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