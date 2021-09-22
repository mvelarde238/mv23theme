<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Container\Layout_Group;
use Ultimate_Fields\Field;

add_action( 'uf.init', 'theme_register_product_options' );

function theme_register_product_options() {

    $opciones_multiples_group = Layout_Group::create( 'opciones_multiples' )
        ->set_title( 'Opciones Múltiples' )
        ->set_min_width( 3 )
        ->set_max_width( 12 )
        ->add_fields(array(
            Field::create( 'tab', 'settings' ),
            Field::create( 'text', 'title' ),
            Field::create( 'text', 'description' ),
            Field::create( 'radio', 'show_as','Seleccione como se va mostrar:')->set_orientation( 'horizontal' )->add_options( array(
                'select'=>'Selector Simple',
                'select_multiple'=>'Selector Múltiple',
                'radio'=>'Opciones Única',
                'checkbox'=>'Opciones Múltiples',
            )),
            Field::create( 'checkbox', 'required' )->set_text( '¿Requerido?' ),
            Field::create( 'tab', 'opciones' ),
            Field::create( 'repeater', 'options' )->set_add_text('Agregar')
            ->add_group('Opciones', array(
                'fields' => array(
                    Field::create( 'complex', 'option' )->hide_label()->add_fields(array(
                        Field::create( 'text', 'Texto')->set_width( 50 )->hide_label(),
                        // Field::create( 'number', 'precio', 'Valor agregado' )->set_width( 50 ),
                    ))
                )
            ))
        ));

    $texto_fields_group = Layout_Group::create( 'texto' )
        ->set_title( 'Cuadro de Texto' )
        ->set_min_width( 3 )
        ->set_max_width( 12 )
        ->set_layout( 'grid' )
        ->add_fields(array(
            Field::create( 'text', 'title' ),
            Field::create( 'text', 'description' ),
            Field::create( 'checkbox', 'required' )->set_text( '¿Requerido?' )->set_width( 50 ),
            // Field::create( 'checkbox', 'change_price' )->set_text( 'Ajustar Precio?' )->set_width( 50 ),
        ));


    Container::create( 'Configuración' )
        ->add_location( 'post_type', 'product_opt', array() )
        ->add_fields(array(
            Field::create( 'radio', 'apply_to','Seleccione donde se va mostrar:')->set_orientation( 'horizontal' )->add_options( array(
                'all'=>'Todos los productos',
                'products'=>'Ciertos Productos',
                'categories'=>'Ciertas Categorías',
            )),
            Field::create( 'wp_objects', 'products', '' )->add( 'posts', 'post_type=product' )->set_button_text( 'Seleccione los productos' )->add_dependency('apply_to','products','='),
            Field::create( 'wp_objects', 'categories', '' )->add( 'terms', 'taxonomy=product_cat' )->set_button_text( 'Seleccione las Categorías' )->add_dependency('apply_to','categories','='),
            Field::create( 'layout', 'product_options_layout', 'Distribución' )->set_columns(12)
                ->add_group( 'tab', array(
                    'title' => 'Tab',
                    'min_width' => 12,
                    'max_width' => 12,
                    'fields'    => array(
                        Field::create( 'tab', 'Contenido' ),
                        Field::create( 'repeater', 'items')->set_chooser_type( 'dropdown' )->set_add_text('Agregar Item')
                            ->add_group( $opciones_multiples_group )
                            ->add_group( $texto_fields_group),
                        Field::create( 'tab', 'Settings' ),
                        Field::create( 'select', 'desktop_template', 'Apariencia en Desktop' )->add_options( array(    
                            'tab' => 'Tab',
                            'accordion' => 'Accordion',
                        )),
                    )
                ))
                ->add_group( $opciones_multiples_group )
                ->add_group( $texto_fields_group),
        ));
}