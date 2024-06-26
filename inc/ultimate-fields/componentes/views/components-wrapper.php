<?php
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$class = (isset($componente['class']) && $componente['class'] != '') ? $componente['class'] : '';
$content_layout = $componente['content_layout'];

$classes_array = format_classes(array(
	'components-wrapper',
	get_color_scheme($componente)
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
        <div class="columnas-simples">
            <?php echo Content_Layout::the_content($content_layout); ?>
        </div>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div>