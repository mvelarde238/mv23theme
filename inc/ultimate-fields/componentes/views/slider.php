<?php
$slider_desktop = $componente['slider_desktop'];
$slider_movil = $componente['slider_movil'];

$layout = $componente['layout'];
$full_width_class = ($layout == 'layout2' || $layout == 'layout3') ? 'full-width' : '';

if (empty($slider_desktop) && empty($slider_movil)) return;

$classes_array = format_classes(array(
    'componente',
    'slider',
    get_color_scheme($componente),
    $componente['class'],
    $full_width_class
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
        <?php if(constant('IS_MOBILE')){ echo do_shortcode($slider_movil); }else{ echo do_shortcode($slider_desktop); } ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  
