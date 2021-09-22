<?php
$tipo = $componente['__type'];
$componentes = $componente['componentes'];
$layout = $componente['layout'];
$full_width_class = ($layout == 'layout2' || $layout == 'layout3') ? 'full-width' : '';
$parallax = ( isset($componente['parallax']) && $componente['parallax'] == 1 ) ? 'parallax' : '';

// video implementation
$video_url = null;
$videos = $componente['bgvideo'];
$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
if($video_id) {
    $video_url = wp_get_attachment_url($video_id);
}
$has_video = ($video_url) ? 'has-video-background' : '';
// end video implementation

$classes_array = format_classes(array(
    'fila',
    get_color_scheme($componente),
    $componente['class'],
    $full_width_class,
    $parallax,
    $has_video
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($video_id): ?>
        <video width="100%" autoplay loop muted="muted"><source src="<?=$video_url?>">Your browser does not support the video tag.</video>
    <?php endif ?>
    <?php 
    if ($layout == 'layout2') echo '<div class="container">';
    foreach ($componentes as $componente ) { 
        set_query_var( 'componente', $componente );
        get_template_part( 'inc/ultimate-fields/componentes/views/'.$componente['__type'] );
    }
    if ($layout == 'layout2') echo '</div>'; 
    ?>
</div>