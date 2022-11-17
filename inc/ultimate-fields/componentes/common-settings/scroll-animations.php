<?php
use Ultimate_Fields\Field;

$css_properties = array(
    'opacity' => 'Opacity',
    'scale' => 'Scale',
    'x' => 'Eje Horizontal',
    'y' => 'Eje Vertical',
    'backgroundColor' => 'Color de fondo',
    'color' => 'Color de texto',
    // 'width' => 'Width',
    // 'height' => 'Height',
    // 'borderTop' => 'Border Top',
    // 'boxShadow' => 'Box Shadow',
    // 'rotation' => 'Rotation',
    // 'fontSize' => 'Font Size',
    // 'repeat' => 'Repeat',
    // 'yoyo' => 'YoYo',
    // 'className' => '+=fish',
    // 'toggle_class' => 'Toggle Class'
);

$scroll_animation_fields = array(
    Field::create( 'checkbox', 'add_scroll_animation' )->set_text( 'Agregar Animaciones Avanzadas' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
);

if( !SCROLL_ANIMATIONS ){
    array_push($scroll_animation_fields, 
        Field::create( 'message', 'Hint_1' )->set_description('Activar animaciones avanzadas en Theme Options -> Global Options')->hide_label()->add_dependency('add_scroll_animation'),
    );
}

if( SCROLL_ANIMATIONS ){
    array_push($scroll_animation_fields, 
        Field::create( 'complex', 'sa-settings' )->add_fields(array(

            Field::create( 'complex', 'trigger-element', 'Trigger Element' )->add_fields(array(
                Field::create( 'select', 'el' )->add_options( array(
                    'this' => 'Componente',
                    'selector' => 'Selector',
                ))->hide_label()->set_width( 50 ),
                Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_width( 50 ),
            ))->set_width( 50 ),

            Field::create( 'complex', 'element', 'Element' )->add_fields(array(
                Field::create( 'select', 'el' )->add_options( array(
                    'this' => 'Componente',
                    'selector' => 'Selector',
                ))->hide_label()->set_width( 50 ),
                Field::create( 'text', 'selector' )->add_dependency('el','selector','=')->hide_label()->set_width( 50 ),
            ))->set_width( 50 ),

            Field::create( 'select', 'trigger-hook', 'Trigger Hook' )->add_options( array(
                'onEnter' => 'onEnter',
                'onCenter' => 'onCenter',
                'onLeave' => 'onLeave',
            ))->set_width( 25 ),
            Field::create( 'text', 'duration' )->set_default_value('200')->set_width( 25 ),
            Field::create( 'text', 'offset' )->set_default_value('100px')->set_width( 25 ),
            Field::create( 'checkbox', 'add_indicators', 'Indicadores' )->set_text('Activar')->set_width( 25 ),

        ))->hide_label()
        ->rows_layout()->add_dependency('add_scroll_animation')
        ->set_width( 50 )
    );

    array_push($scroll_animation_fields, 
        Field::create( 'complex', 'sa-properties-settings' )->add_fields(array(

            Field::create( 'repeater', 'initial_values', 'Valores CSS iniciales (from)' )->set_add_text('Agregar')
                ->set_layout( 'table' )
                ->add_group('Property', array(
                    'fields' => array(
                        Field::create( 'select', 'property')->add_options( $css_properties )->set_width( 50 ),
                        Field::create( 'text', 'value' )->set_width( 50 )
                    )
            )),

            Field::create( 'repeater', 'properties', 'Valores CSS finales (to)' )->set_add_text('Agregar')
                ->set_layout( 'table' )
                ->add_group('Property', array(
                    'fields' => array(
                        Field::create( 'select', 'property')->add_options( $css_properties )->set_width( 50 ),
                        Field::create( 'text', 'value' )->set_width( 50 )
                    )
            )),

            // Field::create( 'checkbox', 'initial_values_check', 'Valores iniciales' )->set_text('Configurar valores iniciales')->hide_label(),

            // Field::create( 'select', 'initial_values_check', 'Valores iniciales' )
            // ->set_input_type( 'radio' )
            // ->set_orientation( 'horizontal' )
            // ->add_options(array(
    	    //     'default'   => 'Valores por defecto',
    	    //     'custom'  => 'Personalizar'
    	    // )),

            
        ))->hide_label()->add_dependency('add_scroll_animation')->set_width( 50 ),
    );
}