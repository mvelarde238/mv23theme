<?php
use Ultimate_Fields\Container;

require_once( 'id-and-class.php' );
require_once( 'fondo.php' );
require_once( 'margenes.php' );
require_once( 'bordes.php' );
require_once( 'box-shadow.php' );
require_once( 'animation.php' );
require_once( 'scroll-animations.php' );
require_once( 'acciones.php' );

require_once( 'row-settings.php' );
require_once( 'columns-settings.php' );
require_once( 'settings-fields.php' );

$settings_fields_container = Container::create( 'settings_fields_container' )
    ->add_fields($settings_fields)
    ->add_fields($margenes)
    ->add_fields($bordes)
    ->add_fields($box_shadow)
    ->add_fields($animation)
    ->add_fields($scroll_animation_fields);