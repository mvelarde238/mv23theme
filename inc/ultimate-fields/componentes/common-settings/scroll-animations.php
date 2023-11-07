<?php
use Ultimate_Fields\Field;

$css_properties = array(
    'opacity' => 'Opacity',
    'scale' => 'Scale',
    'x' => 'Eje Horizontal',
    'y' => 'Eje Vertical',
    'backgroundColor' => 'Color de fondo',
    'color' => 'Color de texto',
    'letterSpacing' => 'Espacio entre letras',
    'rotation' => 'Rotación',
    'repeat' => 'Repetir',
    'padding' => 'Padding',
    'border' => 'Border',
    'margin' => 'Margin',
    // 'width' => 'Width',
    // 'height' => 'Height',
    'boxShadow' => 'Box Shadow',
    'borderRadius' => 'Border Radius',
    'filter' => 'Image Filter',
    // 'fontSize' => 'Font Size',
    // 'yoyo' => 'YoYo',
    'className' => 'Toggle (+/-=class)',
    // 'toggleClass' => 'Toggle Class' // dosnt work
);

$scroll_animation_fields = array(
    Field::create( 'checkbox', 'add_scroll_animation' )->set_text( 'Agregar Animaciones Avanzadas' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
);

if( !SCROLL_ANIMATIONS ){
    array_push($scroll_animation_fields, 
        Field::create( 'message', 'Hint_1' )->set_description('Activar animaciones avanzadas en Theme Options -> Global Options')->hide_label()->add_dependency('add_scroll_animation')
    );
}

$scroll_animation_settings_fileds = array(
    Field::create( 'complex', 'trigger-element', 'Trigger Element' )->add_fields(array(
        Field::create( 'select', 'el' )->add_options( array(
            'this' => 'Componente',
            'selector' => 'Elemento interno',
        ))->hide_label()->set_width( 50 ),
        Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_width( 50 ),
    ))->set_width( 50 ),

    Field::create( 'complex', 'element', 'Element' )->add_fields(array(
        Field::create( 'select', 'el' )->add_options( array(
            'this' => 'Componente',
            'selector' => 'Elemento interno',
            'outer_selector' => 'Elemento externo',
        ))->hide_label()->set_width( 50 ),
        Field::create( 'text', 'selector' )->add_dependency('el','this','!=')->hide_label()->set_width( 50 ),
    ))->set_width( 50 ),

    Field::create( 'select', 'trigger-hook', 'Trigger Hook' )->add_options( array(
        'onEnter' => 'onEnter',
        'onCenter' => 'onCenter',
        'onLeave' => 'onLeave',
    ))->set_width( 25 ),
    Field::create( 'text', 'duration' )->set_default_value('200')->set_width( 25 ),
    Field::create( 'text', 'offset' )->set_default_value('100px')->set_width( 25 ),
    Field::create( 'checkbox', 'turn_off_in_mobile', 'Mobile' )->set_text('Desactivar en móviles')
);
if( SCROLL_INDICATORS ) array_push($scroll_animation_settings_fileds, Field::create( 'checkbox', 'add_indicators', 'Indicadores' )->set_text('Activar')->set_width( 25 ));

array_push($scroll_animation_fields, Field::create( 'repeater', 'scroll_animations' )
    ->set_add_text('Agregar')
    ->add_group('group1', array(
        'edit_mode' => 'popup',
        'title_template' => '<%= settings["element"]["selector"] %>',
        'fields' => array(
            Field::create( 'complex', 'settings' )->add_fields( $scroll_animation_settings_fileds )->hide_label()->rows_layout()->set_width( 50 ),

            Field::create( 'complex', 'animated_properties' )->add_fields(array(

                Field::create( 'repeater', 'from', 'Valores CSS iniciales (from)' )->set_add_text('Agregar')
                    ->set_layout( 'table' )
                    ->add_group('Property', array(
                        'fields' => array(
                            Field::create( 'select', 'property')->add_options( $css_properties )->set_width( 50 ),
                            Field::create( 'text', 'value' )->set_width( 50 )
                        )
                )),
        
                Field::create( 'repeater', 'to', 'Valores CSS finales (to)' )->set_add_text('Agregar')
                    ->set_layout( 'table' )
                    ->add_group('Property', array(
                        'fields' => array(
                            Field::create( 'select', 'property')->add_options( $css_properties )->set_width( 50 ),
                            Field::create( 'text', 'value' )->set_width( 50 )
                        )
                )),
                    
            ))->hide_label()->set_width( 50 ),
        )
    ))->add_dependency('add_scroll_animation')
);