<?php
$location = $componente['location'];
$info = (isset($componente['info'])) ? $componente['info'] : '';
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

if (!is_array($location)) return;
$lat = $location['latLng']['lat'];
$lng = $location['latLng']['lng'];
$icono = $componente['icono'];
$icono = wp_get_attachment_url($icono);

$height = (isset($componente['height']) && $componente['height']) ? $componente['height'] : array( 'height'=>280, 'unit'=>'px' );
$height_style = 'style="height:'.$height['height'].$height['unit'].'"';

$classes_array = format_classes(array(
    'componente',
    'mapa',
    get_color_scheme($componente),
    $componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <?php if($lat && $lng) : ?>
        <div class="mapa__gmap" <?=$height_style?>  data-lat="<?=$lat?>" data-lng="<?=$lng?>" data-icon="<?=$icono?>">
            <?php if($info) echo '<div class="infowindow">'.do_shortcode(wpautop(oembed($info))).'</div>'; ?>
        </div>
    <?php endif; ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div>  
