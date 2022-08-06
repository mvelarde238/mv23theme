<?php
$tipo = $componente['__type'];
$columnas_simples = $componente['columnas_simples'];
$components_margin = (!empty($componente['components_margin'])) ? $componente['components_margin'] : null;
$components_margin_attrs = ( $components_margin && $components_margin != 20) ? 'data-setmargin='.$components_margin : '';

$classes_array = format_classes(array(
    'componente',
    $tipo,
    get_color_scheme($componente),
    $componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?> <?=$components_margin_attrs?>>
    <?php echo Content_Layout::the_content($columnas_simples); ?>
</div>