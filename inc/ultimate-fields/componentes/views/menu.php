<?php
$tipo = $componente['__type'];
$type = $componente['type'];
$menu = $componente['menu'];
$location = $componente['location'];
$layout = (isset($componente['layout'])) ? $componente['layout'] : 'layout1';
$style = ( $componente['style'] != 'unordered-list' ) ? $componente['style'] : '';
$orientation_nav_class = ( str_contains($style,'horizontal') ) ? 'horizontal-nav' : 'vertical-nav';

$classes_array = format_classes(array(
    'componente',
    'menu-comp',
    get_color_scheme($componente),
    $componente['class'],
    $style,
    $orientation_nav_class
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?>>
    <?php if ($layout == 'layout2') echo '<div class="container">'; ?>
        <?php 
        if( $type === 'menu' ){
            wp_nav_menu(array(
                'menu' => $menu,
                'container' => false,                           
                'container_class' => '',
                'walker' => new Theme_Nav_Walker(),
            ));
        }
        if( $type === 'location' ){
            wp_nav_menu( array('theme_location' => $location, 'walker' => new Theme_Nav_Walker()) );
        }
        ?>
    <?php if ($layout == 'layout2') echo '</div>'; ?>
</div> 