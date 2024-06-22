<?php
$tipo = $componente['__type'];
$testimonios = $componente['testimonios'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$items_in_desktop = $componente['cols_in_desktop'];
$items_in_tablet = $componente['cols_in_tablet'];
$items_in_mobile = $componente['cols_in_mobile'];

$classes_array = format_classes(array(
    'testimonios',
    'carrusel',
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
$comment_length = 110;
?>
<div <?=$attributes?> data-controls-position="center">
<?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <div class="testimonios-list carrusel__slider" 
        data-show-controls="1" 
        data-show-nav="1" 
        data-autoplay="0" 
        data-nav-position="bottom"
        data-mobile="<?=$items_in_mobile?>"
        data-tablet="<?=$items_in_tablet?>"
        data-laptop="<?=$items_in_desktop?>"
        data-desktop="<?=$items_in_desktop?>">
        <?php foreach($testimonios as $testimonio) : 
            $rand_id = 'id'.substr(md5(microtime()),rand(0,26),5);
            $type = $testimonio['type'];

            if($type == 'text'){
                $author = $testimonio['author'];
                $author_img = ( $testimonio['author_img'] ) ? wp_get_attachment_url($testimonio['author_img']) : null;
                $comment = $testimonio['comment'];
            }

            $has_video_background_class = ($type == 'video') ? 'has-video-background' : '';
            ?>  
            <div>
                <div id="<?=$rand_id?>" class="testimonio theme-border <?=$has_video_background_class?>">
                    <?php if( $type == 'text' ): ?>
                        <?php if( $author ): ?>
                            <div class="testimonio__header">
                                <?php 
                                echo '<div class="testimonio__author"';
                                if($author_img) echo ' style="background-image: url('.$author_img.')"';
                                echo '></div>'; 
                                ?>
                                <?php if($author) echo '<div>'.do_shortcode(wpautop(oembed($author))).'</div>'; ?>
                            </div>
                        <?php endif; ?> 
                        <?php if($comment): 
                            $string = strip_tags($comment);
                            if (strlen($string) > $comment_length) {
                            
                                // truncate string
                                $stringCut = substr($string, 0, $comment_length);
                                $endPoint = strrpos($stringCut, ' ');
                            
                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                $string .= '... <a class="testimonio__open" href="!#" data-id="'.$rand_id.'"><b>Mostrar más</b></a>';
                            }
                            ?>
                            <div class="testimonio__body">
                                <div class="testimonio__excerpt">
                                    <?php echo $string ?>
                                </div>
                                <div class="testimonio__comment">
                                    <?php echo '<div>'.do_shortcode(wpautop(oembed($comment))).'</div>'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if( $type == 'video' ):
                        $videos = ( isset($testimonio['video']) ) ? $testimonio['video'] : array();
                        $video_id = (isset($videos['videos']) &&  is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
                        if($video_id) {
                            $video_url = wp_get_attachment_url($video_id);
                            if($video_url){
                                echo '<video width="100%" loop muted="muted" autoplay><source src="'.$video_url.'">Your browser does not support the video tag.</video>';
                                echo '<a class="cover-all zoom-video" href="'.$video_url.'"></a>';
                            }
                        }
                    endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div> 