<?php
$slider_desktop = $componente['slider_desktop'];
$slider_movil = $componente['slider_movil'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

if (empty($slider_desktop) && empty($slider_movil)) return;

$classes_array = format_classes(array(
    'componente',
    'slider',
    get_color_scheme($componente),
    $componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
        <?php if(constant('IS_MOBILE')){ echo do_shortcode($slider_movil); }else{ echo do_shortcode($slider_desktop); } ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  
