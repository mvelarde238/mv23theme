<?php
$tipo = $componente['__type'];
$nth_columnas = $componente['nth_columnas'];

$column_width_class = generate_class_attribute(format_classes(array(
    'columnas-'.$nth_columnas,
    $componente['special_widths'],
    $componente['special_widths_3'],
    $componente['mobile_widths'],
    $componente['mobile_widths_3'],
    $componente['tablet_widths'],
    $componente['tablet_widths_3'],
)));

$columnas = array();
array_push($columnas, $componente['columna_1']);
array_push($columnas, $componente['columna_2']);
array_push($columnas, $componente['columna_3']);
array_push($columnas, $componente['columna_4']);

$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$full_width_class = ($layout == 'layout2' || $layout == 'layout3' || $layout == 'layout4') ? 'full-width' : '';

// video implementation
$video_url = null;
$videos = (isset($componente['bgvideo'])) ? $componente['bgvideo'] : null;
$video_id = (is_array($videos['videos']) && count($videos['videos'])) ? $videos['videos'][0] : null;
if($video_id) {
    $video_url = wp_get_attachment_url($video_id);
    $video_opacity = (isset($componente['video_opacity']) && $componente['video_opacity'] ) ? $componente['video_opacity'] : 100;
    $video_style = ($video_opacity != 100) ? 'style="opacity:'.($video_opacity/100).';"' : ''; 
}
$has_video = ($video_url) ? 'has-video-background' : '';
// end video implementation

$classes_array = format_classes(array(
    'columnas',
    get_color_scheme($componente),
    $componente['class'],
    $full_width_class,
    $layout,
    $has_video
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($video_id): ?>
        <video <?=$video_style?> width="100%" autoplay loop muted="muted"><source src="<?=$video_url?>">Your browser does not support the video tag.</video>
    <?php endif; ?>
    <?php if ($layout == 'layout2' || $layout == 'layout4') echo '<div class="container">'; ?>
    <div <?=$column_width_class?>>
    <?php
    for ($i=0; $i < $nth_columnas ; $i++) { 
        $column_settings = $componente['columna_'.($i+1).'_settings'];

        $clases = array();
        if($column_settings['class']) array_push($clases, $column_settings['class']);

        if( $column_settings['content_alignment'] != 'flex-start' && !empty($column_settings['content_alignment']) ) array_push($clases, 'alignment-'.$column_settings['content_alignment']);

        $mobile_order = $column_settings['mobile_order'];
        if($mobile_order != 0) array_push($clases, 'mobile-order-'.$mobile_order);
        $tablet_order = $column_settings['tablet_order'];
        if($tablet_order != 0) array_push($clases, 'tablet-order-'.$tablet_order);

        $color_scheme = get_color_scheme($column_settings);
        if ($color_scheme!='') array_push($clases, $color_scheme); 

        $column_settings['__type'] = 'column'; 
        $column_attributes = generate_attributes($column_settings, $clases);
        ?>
        <div <?=$column_attributes?>>
            <?php if ($column_settings['content_alignment'] == 'pinned') echo '<div class="pinned-block">'; ?>
            <?php foreach ($columnas[$i] as $components_inside) {
                set_query_var( 'componente', $components_inside );
                get_template_part( 'inc/ultimate-fields/componentes/views/'.$components_inside['__type'] );
            }?>
            <?php if ($column_settings['content_alignment'] == 'pinned') echo '</div>'; ?>
        </div>
    <?php } ?>
    </div>
    <?php if ($layout == 'layout2' || $layout == 'layout4') echo '</div>'; ?>
</div>