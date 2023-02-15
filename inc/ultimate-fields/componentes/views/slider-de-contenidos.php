<?php
$tipo = $componente['__type'];
$items = $componente['content_slider'];
$extender_fondo = $componente['extender_fondo'];
$scroll_to_top = (isset($componente['scroll_to_top'])) ? $componente['scroll_to_top'] : 0;
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';

$classes_array = format_classes(array(
    'componente',
    'componente-'.$tipo,
    get_color_scheme($componente),
    $componente['class']
));

$show_nav = (isset($componente['show_nav']) && !empty($componente['show_nav'])) ? $componente['show_nav'] : 0;
$nav_position = (isset($componente['nav_position']) && !empty($componente['nav_position'])) ? $componente['nav_position'] : 0;
$nav_show_title = (isset($componente['nav_show_title']) && !empty($componente['nav_show_title'])) ? $componente['nav_show_title'] : 0;

$show_controls = (isset($componente['show_controls']) && !empty($componente['show_controls'])) ? $componente['show_controls'] : 0;
$controls_position = (isset($componente['controls_position']) && !empty($componente['controls_position'])) ? $componente['controls_position'] : 0;

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?> 
    data-extended-bgi="<?=$extender_fondo?>" 
    data-show-title="<?=$nav_show_title?>" 
    data-controls-position="<?=$controls_position?>"  
    data-scroll-to-top="<?=$scroll_to_top?>">
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
    <?php if (is_array($items) && count($items)>0): ?>
        <div class="slider-de-contenidos"
            data-nav-position="<?=$nav_position?>" 
            data-controls-position="<?=$controls_position?>" 
            data-show-title="<?=$nav_show_title?>" 
            data-show-controls="<?=$show_controls?>" 
            data-show-nav="<?=$show_nav?>">
            <?php foreach ($items as $item): 
                $componentes = $item['componentes'];

                $title = (array_key_exists('title', $item) && $item['title']) ? $item['title'] : '';

                $clases = '';
                $text_color = '';
                $color_scheme = (array_key_exists('color_scheme', $item) && $item['color_scheme']) ? $item['color_scheme'] : '';
                switch ($color_scheme) {
                    case 'dark-scheme':
                        $text_color = 'text-color-2';
                        break;
                    
                    case 'default-scheme':
                        $text_color = 'text-color-1';
                        break;
                }
                $clases = ($color_scheme != 'default-scheme') ? 'class="'.$text_color.'"' : '';
                
                $style = '';
                $bgi = wp_get_attachment_url($item['bgi']);
                if (array_key_exists('color_de_fondo', $item)) {
                    $style .= ($item['color_de_fondo']['add_bgc']) ? 'background-color: '.$item['color_de_fondo']['bgc'].';' : '';
                }
                $style .= ($bgi) ? 'background-image: url('.$bgi.');' : '';
                $style .= ($bgi && $item['bgi_options']['repeat'] != 'repeat') ? 'background-repeat: '.$item['bgi_options']['repeat'].';' : '';
                $style .= ($bgi && $item['bgi_options']['size'] != 'auto') ? 'background-size: '.$item['bgi_options']['size'].';' : '';
                $style .= ($bgi && $item['bgi_options']['position_x'] != 'left') ? 'background-position-x: '.$item['bgi_options']['position_x'].';' : '';
                $style .= ($bgi && $item['bgi_options']['position_y'] != 'top') ? 'background-position-y: '.$item['bgi_options']['position_y'].';' : '';

                if ($style && $extender_fondo) {
                    $style = 'data-style="'.$style.'"';
                } else {
                    $style = 'style="'.$style.'"';
                }
                ?>
                <div <?=$clases?> <?=$style?> data-title="<?=$title?>">
                    <?php 
                    foreach ($componentes as $componente ) { 
                        $path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
                        include $path;
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div> 