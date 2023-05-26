<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'button' )
    ->add_location( 'shortcode', array(
        'template' => '<div style="text-align:<%= alignment %>;">
            <% 
            var btnclass = style
            if( fullwidth === "true" ) { btnclass += " btn-block" }
            %>
            <a href="#" class="<%= btnclass %>"><%= text %></a>
        </div>'
    ))
    ->set_layout('table')
    ->add_fields(array(
        Field::create( 'text', 'text', 'Texto del botón'),
        Field::create( 'radio', 'url_type','Seleccione que contenido se abrirá al hacer clic:')->set_orientation( 'horizontal' )->add_options( array(
            'interna' => 'Página Interna',
            'externa' => 'Página Externa',
        )),
        Field::create( 'wp_object', 'href', 'URL Interna' )->set_button_text( 'Selecciona la página' )->add_dependency('url_type','interna','='),
        Field::create( 'text', 'url', 'URL Externa' )->add_dependency('url_type','externa','='),
        Field::create( 'select', 'style', 'Estilo')->add_options( array(
            'btn' => 'Botón Simple',
            'btn btn--main-color' => 'Botón Corporativo 1',
            'btn btn--secondary-color' => 'Botón Corporativo 2'
        ))->set_default_value('btn btn--main-color'),
        Field::create( 'radio', 'alignment', 'Alineación')->add_options( array(
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha'
        ))->set_orientation( 'horizontal' ),
        Field::create( 'checkbox', 'fullwidth','Botón de ancho completo' )->set_text( 'Activar' ),
    ));