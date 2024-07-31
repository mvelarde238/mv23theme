<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'menu-item-megamenu' )
    ->set_title( 'Megamenú' )
    ->add_location( 'menu_item', array(
        'levels' => 1,
        'theme_locations' => __locations_multilingual_support( MENU_ITEM_MEGAMENU_LOCATIONS ),
        'popup_mode' => true
    ))
    ->add_fields(array(
        Field::create( 'checkbox', 'is_megamenu', 'Activar' )->set_text('¿Activar Megamenú?'),
        Field::create( 'wp_object', 'megamenu_post' )->add( 'posts', 'post_type=megamenu' )->set_button_text( 'Seleccione el megamenú' )->add_dependency('is_megamenu'),
    ));