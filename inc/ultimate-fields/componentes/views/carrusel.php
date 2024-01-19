<?php
$tipo = $componente['__type'];
$items = $componente['items'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$show_controls = (isset($componente['show_controls']) && !empty($componente['show_controls'])) ? $componente['show_controls'] : 0;
$show_nav = (isset($componente['show_nav']) && !empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
$nav_position = (isset($componente['nav_position']) && !empty($componente['nav_position'])) ? $componente['nav_position'] : 'bottom';
$autoplay = (isset($componente['autoplay']) && !empty($componente['autoplay'])) ? $componente['autoplay'] : 0;

$items_in_mobile = $componente['items_in_mobile'];
$items_in_tablet = $componente['items_in_tablet'];
$items_in_laptop = $componente['items_in_laptop'];
$items_in_desktop = $componente['items_in_desktop'];

$img_styles = '';
$imgs_height = $componente['imgs_height'] || 'auto';
if($imgs_height == 'custom') {
    $img_max_height = $componente['img_max_height'] . 'px';
    $img_styles = 'style="max-height:'.$img_max_height.'"'; 
}

$gutter_in_mobile = (isset($componente['gutter_in_mobile'])) ? $componente['gutter_in_mobile'] : 0;
$gutter_in_tablet = (isset($componente['gutter_in_tablet'])) ? $componente['gutter_in_tablet'] : 0;
$gutter_in_laptop = (isset($componente['gutter_in_laptop'])) ? $componente['gutter_in_laptop'] : 0;
$gutter_in_desktop = (isset($componente['gutter_in_desktop'])) ? $componente['gutter_in_desktop'] : 0;

$classes_array = format_classes(array(
    'componente',
    'carrusel',
    get_color_scheme($componente),
    $componente['class'],
    'nav-position-'.$nav_position
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?> data-controls-position="center">
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <div class="carrusel__slider" 
        data-show-controls="<?=$show_controls?>" 
        data-show-nav="<?=$show_nav?>"
        data-nav-position="<?=$nav_position?>"
        data-mobile="<?=$items_in_mobile?>"
        data-tablet="<?=$items_in_tablet?>"
        data-laptop="<?=$items_in_laptop?>"
        data-desktop="<?=$items_in_desktop?>"
        data-mobile-gutter="<?=$gutter_in_mobile?>"
        data-tablet-gutter="<?=$gutter_in_tablet?>"
        data-laptop-gutter="<?=$gutter_in_laptop?>"
        data-desktop-gutter="<?=$gutter_in_desktop?>"
        data-autoplay="<?=$autoplay?>"
        >
    <?php for ($i=0; $i < count($items); $i++) { 
        $type = $items[$i]['__type'];

        if( $type == 'item' ){
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
            <div class="carrusel__item carrusel__item--image">
                <img src="<?=$bgi?>" <?=$img_styles?> alt="Carrusel Item">
                <?php if ($link != NULL): ?>
                    <?php $target = ($enlace['new_tab'] == 1) ? '_blank' : '';  ?>
                    <a class="carrusel__item__link <?=$lightbox_class?>" href="<?=$link?>" target="<?=$target?>"></a>
                <?php endif ?>
            </div>
            <?php
        }

        if( $type == 'content' ){
            $content_layout = $items[$i]['content_layout'];
            if (is_array($content_layout) && count($content_layout) > 0) :

                $item_classes = array('columnas-simples');
                if( isset($items[$i]['class']) ) array_push( $item_classes, $items[$i]['class'] );
                $item_attributes = generate_attributes($items[$i], $item_classes);

                echo '<div class="carrusel__item carrusel__item--content">';
                echo '<div '.$item_attributes.'>';
                echo Content_Layout::the_content($content_layout);
                echo '</div>';
                echo '</div>';
            endif;
        }

    }; ?>
    </div>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div> 
