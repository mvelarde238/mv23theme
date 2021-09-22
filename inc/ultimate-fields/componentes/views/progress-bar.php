<?php
$tipo = $componente['__type'];
$percentage = $componente['percentage']; 
$text = $componente['text']; 

$classes_array = format_classes(array(
    'componente',
    'progress-bar',
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <div>
        <?php if ($text): ?>
            <?php echo apply_filters('the_content', $text); ?>
        <?php endif ?>
        <p>
            <span class="progress-bar__num"><?php echo $percentage ?></span>%
        </p>
    </div>
    <div class="progress-bar__bg">
        <div class="progress-bar__bar" style="width: <?=$percentage?>%" data-number="<?=$percentage?>"></div>
    </div>
</div>