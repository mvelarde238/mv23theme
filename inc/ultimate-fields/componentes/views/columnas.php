<?php
$tipo = $componente['__type'];
$nth_columnas = $componente['nth_columnas'];

$column_width_class = generate_class_attribute(format_classes(array(
    'columnas-'.$nth_columnas,
    $componente['special_widths'],
    $componente['special_widths_3'],
    $componente['mobile_widths'],
    $componente['mobile_widths_3'],
    (isset($componente['mobile_widths_4'])) ? $componente['mobile_widths_4'] : '',
    $componente['tablet_widths'],
    $componente['tablet_widths_3'],
    (isset($componente['tablet_widths_4'])) ? $componente['tablet_widths_4'] : '',
)));

$columnas = array();
array_push($columnas, $componente['columna_1']);
array_push($columnas, $componente['columna_2']);
array_push($columnas, $componente['columna_3']);
array_push($columnas, $componente['columna_4']);

$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$video_background = video_background($componente);

$classes_array = format_classes(array(
    'columnas',
    get_color_scheme($componente),
    $componente['class'],
    $layout,
    $video_background['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if($video_background['code']) echo $video_background['code'] ?>
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

        $column_video_background = video_background($column_settings);
        if($column_video_background['code']) array_push($clases, $column_video_background['class']);

        $column_settings['__type'] = 'column'; 
        $column_attributes = generate_attributes($column_settings, $clases);
        ?>
        <div <?=$column_attributes?>>
            <?php if($column_video_background['code']) echo $column_video_background['code'] ?>
            <?php if ($column_settings['content_alignment'] == 'pinned') echo '<div class="pinned-block">'; ?>
            <?php foreach ($columnas[$i] as $components_inside) {
                $components_inside['layout'] = 'layout1';
                set_query_var( 'componente', $components_inside );
                get_template_part( 'inc/ultimate-fields/componentes/views/'.$components_inside['__type'] );
            }?>
            <?php if ($column_settings['content_alignment'] == 'pinned') echo '</div>'; ?>
        </div>
    <?php } ?>
    </div>
    <?php if ($layout == 'layout2' || $layout == 'layout4') echo '</div>'; ?>
    <?php echo generate_actions_code($componente); ?>
</div>