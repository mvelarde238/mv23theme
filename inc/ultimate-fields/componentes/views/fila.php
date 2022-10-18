<?php
$tipo = $componente['__type'];
$componentes = $componente['componentes'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$parallax = ( isset($componente['parallax']) && $componente['parallax'] == 1 ) ? 'parallax' : '';

$video_background = video_background($componente);

$classes_array = format_classes(array(
    'fila',
    get_color_scheme($componente),
    $componente['class'],
    $parallax,
    $video_background['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if($video_background['code']) echo $video_background['code'] ?>
    <?php 
    if ($layout == 'layout2') echo '<div class="container">';
    foreach ($componentes as $componente ) { 
        $componente['layout'] = 'layout1';
        set_query_var( 'componente', $componente );
        get_template_part( 'inc/ultimate-fields/componentes/views/'.$componente['__type'] );
    }
    if ($layout == 'layout2') echo '</div>'; 
    ?>
</div>