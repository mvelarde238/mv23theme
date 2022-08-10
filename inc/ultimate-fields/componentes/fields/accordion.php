<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$fields = array(
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'repeater', 'accordion', '' )
        ->set_add_text('Agregar Item')
        ->add_group('Item', array(
            'edit_mode' => 'popup',
            'fields' => array(
                Field::create( 'tab', 'Título' ),
                Field::create( 'text', 'titulo', 'Título' )->set_width( 30 ),
                Field::create( 'radio', 'identificador','Seleccione que mostrar antes del título:')->set_orientation( 'horizontal' )->add_options( array(
                        '' => 'Nada',
                        'icono' => 'Icono',
                        'imagen' => 'Imagen',
                ))->set_width( 30 ),
                Field::create( 'icon', 'icon', 'Icono' )->add_set( 'font-awesome' )->set_width( 20 )->add_dependency('identificador','icono','='),
                Field::create( 'image', 'image', 'Imágen' )->set_width( 20 )->add_dependency('identificador','imagen','='),
                Field::create( 'select', 'image_size', 'Tamaño de la imágen' )->set_width( 20 )
                    ->add_dependency('identificador','imagen','=')
                    ->add_dependency('../tab_style','style1','=')
                    ->add_options(array(
                        'iconsize' => 'Pequeño',
                        'auto' => 'Automático'
                )),
                Field::create( 'tab', 'Contenido' ),
                Field::create( 'radio', 'content_element','Seleccione que mostrar como Contenido:')->set_orientation( 'horizontal' )->add_options( array(
                    'texto' => 'Texto',
                    'layout' => 'Editor',
                    'pagina' => 'Página',
                    'seccion_reusable' => 'Seccion Reusable',
                ))->set_default_value( ACCORDION_DEFAULT_CONTENT ),
                Field::create( 'wysiwyg', 'content', 'Contenido' )->add_dependency('content_element','texto','=')->hide_label()->set_rows( 30 ),
                Content_Layout::the_field(array( 'slug' => 'content_layout', 
                    'components' => array( 'Editor de Texto', 'Imágen', 'Componente Reusable', 'Mapa' )
                ))->add_dependency('content_element','layout','='),
                Field::create( 'wp_objects', 'page', 'Página' )->add( 'posts', 'page' )->set_button_text( 'Selecciona la página' )->add_dependency('content_element','pagina','=')->hide_label(),
                Field::create( 'select', 'seccion_reusable', 'Seleccionar' )->add_options($modulos_reusables+$componentes_reusables)->add_dependency('content_element','seccion_reusable','=')->hide_label(),
            )
        )
    ),
    Field::create( 'tab', 'Desktop' ),
    Field::create( 'select', 'desktop_template', 'Apariencia en Desktop' )->add_options( array(    
        'accordion' => 'Accordion',
        'tab' => 'Tab',
    ))->set_width(25),
    Field::create( 'image_select', 'tab_style', 'Apariencia' )->add_options(array(
        'style1'  => array(
            'label' => 'Estilo 1',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/tab-style-1.png'
        ),
        'style2'  => array(
            'label' => 'Estilo 2',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/tab-style-2.png'
        )
    ))->set_width(25)->add_dependency('desktop_template','tab','='),
    Field::create('complex','tab_settings')->add_fields(array(
        Field::create('checkbox','close_first_tab')->set_text('Cerrar primer tab')->hide_label()
    ))->set_width(25)->add_dependency('desktop_template','tab','='),
    Field::create( 'color', 'accent_color' )->set_width( 25 )->add_dependency('desktop_template','tab','=')->set_default_value(get_main_color()),

    Field::create( 'tab', 'Mobile' ),
    Field::create( 'select', 'mobile_template', 'Apariencia en Móviles' )->add_options( array(    
        'accordion' => 'Accordion',
        'tab' => 'Tab',
    ))->set_width(33)
);

$accordion_args = array(
    'fields' => array_merge($fields, $default_settings_fields)
);

$accordion = Repeater_Group::create( 'Accordion', $accordion_args );