<?php
$location = $componente['location'];

if (!is_array($location)) return;
$lat = $location['latLng']['lat'];
$lng = $location['latLng']['lng'];
$icono = $componente['icono'];
$icono = wp_get_attachment_url($icono);

$classes_array = format_classes(array(
    'componente',
    'mapa',
    get_color_scheme($componente),
    $componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if($lat && $lng) : ?>
        <div class="mapa__gmap" data-lat="<?=$lat?>" data-lng="<?=$lng?>" data-icon="<?=$icono?>"></div>
    <?php endif; ?>
</div>  
