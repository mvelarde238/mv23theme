<?php
$tipo = $componente['__type'];
$items = $componente['items'];
$show_controls = (isset($componente['show_controls']) && !empty($componente['show_controls'])) ? $componente['show_controls'] : 0;
$show_nav = (isset($componente['show_nav']) && !empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
$nav_position = (isset($componente['nav_position']) && !empty($componente['nav_position'])) ? $componente['nav_position'] : 'bottom';

$items_in_mobile = $componente['items_in_mobile'];
$items_in_tablet = $componente['items_in_tablet'];
$items_in_laptop = $componente['items_in_laptop'];
$items_in_desktop = $componente['items_in_desktop'];

$classes_array = format_classes(array(
    'componente',
    'carrusel',
    get_color_scheme($componente),
    $componente['class'],
    'nav-position-'.$nav_position
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <div class="carrusel__slider" 
        data-show-controls="<?=$show_controls?>" 
        data-show-nav="<?=$show_nav?>"
        data-nav-position="<?=$nav_position?>"
        data-mobile="<?=$items_in_mobile?>"
        data-tablet="<?=$items_in_tablet?>"
        data-laptop="<?=$items_in_laptop?>"
        data-desktop="<?=$items_in_desktop?>"
        >
    <?php for ($i=0; $i < count($items); $i++) { 
        $imagen = $items[$i]['imagen'];
        $bgi = wp_get_attachment_url($imagen);

        $link = NULL;
        $enlace = $items[$i]['enlace'];
        switch ($enlace['url_type']) {
            case 'externa':
                $link = $enlace['url'];
                break;
            
            case 'interna':
                $link = get_permalink( str_replace('post_','',$enlace['post']) );
                break;
        
            case 'popup':
                $link = $bgi;
                break;
        }
        
        $lightbox_class = ( $enlace['url_type'] == 'popup' ) ? 'zoom' : '';
        ?>
            <div class="carrusel__item">
                <img src="<?=$bgi?>" alt="">
                <?php if ($link != NULL): ?>
                    <?php $target = ($enlace['new_tab'] == 1) ? '_blank' : '';  ?>
                    <a class="carrusel__item__link <?=$lightbox_class?>" href="<?=$link?>" target="<?=$target?>"></a>
                <?php endif ?>
            </div>
    <?php }; ?>
    </div>
</div> 
