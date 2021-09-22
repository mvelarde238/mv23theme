<?php
$tipo = $componente['__type'];
$percentage = $componente['percentage']; 

$classes_array = format_classes(array(
    'componente',
    'progress-circle',
    get_color_scheme($componente),
    $componente['class'],
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <svg viewBox="0 0 36 36" class="circular-chart">
        <path class="circle-bg" d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"/>
        <path class="circle"
            stroke-dasharray="<?=$percentage?>, 100" d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"/>
        <text x="18" y="20.35" class="percentage"><?=$percentage?>%</text>
    </svg>
</div>