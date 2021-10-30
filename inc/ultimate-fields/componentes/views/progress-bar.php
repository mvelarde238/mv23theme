<?php
$tipo = $componente['__type'];
$percentage = $componente['percentage']; 
$text = $componente['text']; 
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$classes_array = format_classes(array(
    'componente',
    'progress-bar',
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <div>
        <?php if ($text): ?>
            <?php echo do_shortcode(wpautop($text)); ?>
        <?php endif ?>
        <p>
            <span class="progress-bar__num"><?php echo $percentage ?></span>%
        </p>
    </div>
    <div class="progress-bar__bg">
        <div class="progress-bar__bar" style="width: <?=$percentage?>%" data-number="<?=$percentage?>"></div>
    </div>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div>