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
    <div>
        <?php for ($i=0; $i < count($columnas_simples); $i++) { 
            $fila_items = $columnas_simples[$i];
            for ($it=0; $it < count($fila_items); $it++){
                $componente = $fila_items[$it];
                $type = $componente['__type'];
                $width = $componente['__width'];
                ?>
                <div class="columnas-simples__item <?=$type?> width-<?=$width?>">
                    <?php 
                    $path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$type.'.php';
                    include $path; 
                    ?>
                </div>
                <?php }; ?>
        <?php }; ?>
    </div>
</div>