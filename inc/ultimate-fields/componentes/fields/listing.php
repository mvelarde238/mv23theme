<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$listing_cpts = LISTING_CPTS;
$listing_taxonomies = LISTING_TAXONOMIES;
$listing_templates = LISTING_TEMPLATES;
$listing_post_template = LISTING_POST_TEMPLATE;

if(WOOCOMMERCE_IS_ACTIVE){
    $listing_cpts['product'] = 'Productos';
    array_push($listing_taxonomies, array(
        'cpt_slug' => 'product', 
        'slug' => 'product_cat'
    ));
    $listing_post_template['woocommerce1'] = 'WooCommerce Product Basic';
} 

if(USE_PORTFOLIO_CPT){
    $listing_cpts['portfolio'] = 'Portfolio';
    array_push($listing_taxonomies, array(
        'cpt_slug' => 'portfolio', 
        'slug' => 'portfolio-cat'
    ));
}

$listing_fields_1 = array( 
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'radio', 'show','Seleccione qué entradas se van a mostrar:')->set_orientation( 'horizontal' )->add_options( array(
        'auto'=>'Automático (últimos posts publicados)',
        'manual'=>'Manual',
    )),
    Field::create( 'wp_objects', 'posts', '' )->set_button_text( 'Selecciona los posts' )->add_dependency('show','manual','='),

    Field::create( 'number', 'qty', 'Cantidad de posts' )->add_dependency('show','auto','=')->set_default_value(3)->set_width(16),
    Field::create( 'select', 'posttype', 'Tipo de Posts' )->add_dependency('show','auto','=')->add_options($listing_cpts)->set_width(16),
);

if(WOOCOMMERCE_IS_ACTIVE){
    $woocommerce_keys_field = Field::create('select','woocommerce_key','WooCommerce Tag')->add_dependency('show','auto','=')->add_dependency('posttype', 'product', '=')->set_width(16)->add_options(array(
        '' => 'Ninguno',
        'featured' => 'Destacados',
        'on_sale' => 'En Oferta',
        'best_selling' => 'Más vendidos'
    ));
    array_push($listing_fields_1, $woocommerce_keys_field);
}

if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
    $taxonomies_field = Field::create( 'complex', 'taxonomies_field' )->set_width(50)->hide_label();
    foreach($listing_taxonomies as $tax){
        $taxonomies_field->add_fields( array(
            Field::create( 'multiselect', $tax['slug'] )->add_terms( $tax['slug'] )->add_dependency('../show','auto','=')->add_dependency('../posttype', $tax['cpt_slug'], '=')->set_width(20) 
        ));
    }
    array_push($listing_fields_1, $taxonomies_field);
}

$listing_fields_2 = array( 
    Field::create( 'select', 'order', 'Orden' )->add_dependency('show','auto','=')->add_options(array(
        'DESC' => 'Descendente',
        'ASC' => 'Ascendente'
    ))->set_width(25),
    Field::create( 'select', 'orderby', 'Ordenar por:' )->add_dependency('show','auto','=')->add_options(array(
        'date' => 'Fecha',
        'title' => 'Título',
        'name' => 'Slug',
        'rand' => 'Random',
        'menu_order' => 'Personalizado',
        // 'comment_count' => 'Comentarios'
    ))->set_width(25),
    Field::create( 'number', 'offset', 'Offset' )->add_dependency('show','auto','=')->set_width(25),
    
    Field::create( 'tab', 'List Template'),
    Field::create( 'radio', 'list_template', 'Template' )->set_orientation( 'horizontal' )->add_options($listing_templates)->hide_label(),
    
    Field::create( 'checkbox', 'show_controls' )->set_width( 33 )->set_text('Mostrar Flechas')->add_dependency('list_template','carrusel','='),
    Field::create( 'checkbox', 'show_nav' )->set_width( 33 )->set_text('Mostrar indicadores de página')->add_dependency('list_template','carrusel','='),
    Field::create( 'checkbox', 'autoplay' )->set_width( 33 )->set_text('Empezar automáticamente')->add_dependency('list_template','carrusel','='),

    Field::create( 'section', 'Cantidad de columnas' ),
    Field::create( 'number', 'items_in_desktop', 'Columnas en desktop' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(3),
    Field::create( 'number', 'items_in_laptop', 'Columnas en laptop' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(3),
    Field::create( 'number', 'items_in_tablet', 'Columnas en tablet' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(2),
    Field::create( 'number', 'items_in_mobile', 'Columnas en móviles' )->set_width( 25 )->enable_slider( 1, 12 )->set_default_value(1),

    Field::create( 'section', 'Espacio entre las columnas' ),
    Field::create( 'number', 'd_gap', 'En desktop' )->set_default_value(intval(LISTING_DESKTOP_GAP))->set_width( 25 ),
    Field::create( 'number', 'l_gap', 'En laptop' )->set_default_value(intval(LISTING_LAPTOP_GAP))->set_width( 25 ),
    Field::create( 'number', 't_gap', 'En tablet' )->set_default_value(intval(LISTING_TABLET_GAP))->set_width( 25 ),
    Field::create( 'number', 'm_gap', 'En móviles' )->set_default_value(intval(LISTING_MOBILE_GAP))->set_width( 25 ),
    
    Field::create( 'tab', 'Post Template'),
    Field::create( 'radio', 'post_template', 'Template' )->set_orientation( 'vertical' )->add_options($listing_post_template)->hide_label(),
    Field::create( 'section', 'Acciones' ),
    Field::create( 'select', 'on_click_post', 'Al hacer click en el post:' )->add_options(array(
        'redirect' => 'Redirigir a la página del post',
        'show-expander' => 'Mostrar el post en la misma página',
        'show-popup' => 'Mostrar el post en un popup',
        'none' => 'Ninguna'
    ))->set_width( 50 ),
    Field::create( 'select', 'on_click_scroll_to', 'Al hacer click mover el scroll a:' )->add_options(array(
        '' => 'No mover el scroll',
        'postcard' => 'Al post card',
        'expander' => 'Al expander'
    ))->add_dependency( 'on_click_post', 'show-expander', '=' )->set_width( 50 ),
    
    Field::create( 'tab', 'Paginado'),
    Field::create( 'select', 'pagination_type', 'Paginado' )->add_dependency('show','auto','=')->add_options(LISTING_PAGINATION_TYPES)->hide_label()->set_width( 25 ),
    Field::create( 'checkbox', 'scrolltop' )->set_text('Scroll to top')->add_dependency('pagination_type','classic','=')->hide_label()->set_width( 25 ),
);

$listing_fields_filter = array(
    Field::create( 'tab', 'Filter'),
    Field::create( 'checkbox', 'filter', 'Filtro' )->set_text( 'Mostrar Filtros' )->set_width(10)
);

if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
    foreach($listing_taxonomies as $tax){
        array_push($listing_fields_filter, 
            Field::create( 'complex', $tax['slug'].'-filter', $tax['slug'] )->add_fields(array(
                Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label(),
                Field::create( 'select', 'default_value' )->add_terms( $tax['slug'] )->add_dependency('show')
            ))->add_dependency('filter')->add_dependency('show','auto','=')->add_dependency('posttype', $tax['cpt_slug'], '=')->set_width(10)
        );
    }
}

array_push($listing_fields_filter, Field::create( 'complex', 'month-filter', 'Mes' )->add_fields(array(Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label()))->add_dependency('filter')->set_width(10) );

array_push($listing_fields_filter, Field::create( 'complex', 'year-filter', 'Año' )->add_fields(array(
    Field::create( 'checkbox', 'show' )->set_text( 'Mostrar' )->hide_label(),
    Field::create( 'number', 'first_year', 'Primer Año' )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value(2012)->set_width(50),
    Field::create( 'number', 'default', 'Default value' )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('show')->set_default_value('')->set_width(50),
))->add_dependency('filter')->set_width(10) );

$listing = Repeater_Group::create( 'Listing', array())
->add_fields($listing_fields_1)
->add_fields($listing_fields_2)
->add_fields($listing_fields_filter)
->add_fields($settings_fields_container->get_fields());