<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'global_options' ) 
    ->add_location( 'options', 'theme-options' )
    ->add_fields(array(
        Field::create( 'wp_object', 'theme_footer_post', 'Pie de página' )->add( 'posts', 'post_type=footer' ),
        Field::create( 'checkbox', 'activate_gm', 'Activar Google Maps' )->set_text('Activar'),
        Field::create( 'multiselect', 'gm_services','Google Map Services')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'places' => 'Places'
        ))->add_dependency('activate_gm'),

        Field::create( 'multiselect', 'show_editor_in','El theme oculta el editor de texto en páginas y productos, mostrarlo en los siguientes lugares')->set_input_type( 'checkbox' )->set_orientation( 'horizontal' )->add_options( array(
            'page' => 'Páginas',
            'product' => 'Productos'
        )),
        Field::create( 'complex', 'scroll_animations', 'Activar Animaciones Avanzadas' )->add_fields(array(
            Field::create( 'checkbox', 'activate' )->set_text('Activar')->hide_label()->set_width( 50 ),
            Field::create( 'checkbox', 'activate_indicators' )->set_text('Activar indicadores')->hide_label()->set_width( 50 )->add_dependency('activate'),
        )),
        Field::create( 'checkbox', 'disable_comments_styles', 'Desactivar theme styles en comentarios' )->set_text('Desactivar'),
    ));