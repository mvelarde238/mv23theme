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

    Field::create( 'number', 'qty', 'Cantidad de posts' )->add_dependency('show','auto','=')->set_default_value(3)->set_width(25),
    Field::create( 'select', 'posttype', 'Tipo de Posts' )->add_dependency('show','auto','=')->add_options($listing_cpts)->set_width(25),
    Field::create( 'multiselect', 'post_terms', 'Categoría' )->add_terms( 'category' )->add_dependency('show','auto','=')->add_dependency('posttype','post','=')->set_width(50),
    Field::create( 'select', 'order', 'Orden' )->add_dependency('show','auto','=')->add_options(array(
        'DESC' => 'Descendente',
        'ASC' => 'Ascendente'
    ))->set_width(25),
);

if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
    foreach($listing_taxonomies as $tax){
        array_push($listing_fields_1, Field::create( 'multiselect', $tax['cpt_slug'].'_terms', 'Categoría' )->add_terms( $tax['slug'] )->add_dependency('show','auto','=')->add_dependency('posttype', $tax['cpt_slug'], '=')->set_width(50));
    }
}

$listing_fields_2 = array( 
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
    Field::create( 'number', 'd_gap', 'En desktop' )->set_width( 25 )->enable_slider( 0, 100 ),
    Field::create( 'number', 'l_gap', 'En laptop' )->set_width( 25 )->enable_slider( 0, 100 ),
    Field::create( 'number', 't_gap', 'En tablet' )->set_width( 25 )->enable_slider( 0, 100 ),
    Field::create( 'number', 'm_gap', 'En móviles' )->set_width( 25 )->enable_slider( 0, 100 ),
    
    Field::create( 'tab', 'Post Template'),
    Field::create( 'radio', 'post_template', 'Template' )->set_orientation( 'vertical' )->add_options($listing_post_template)->hide_label(),
    
    Field::create( 'tab', 'Paginado')->add_dependency('list_template','carrusel','!='),
    Field::create( 'select', 'pagination_type', 'Paginado' )->add_dependency('show','auto','=')->add_options(LISTING_PAGINATION_TYPES)->hide_label(),
    
    Field::create( 'tab', 'Filter')->add_dependency('list_template','carrusel','!='),
    Field::create( 'checkbox', 'filter', 'Filtro' )->set_text( 'Mostrar Filtros' )->set_width(33),
    Field::create( 'checkbox', 'filter_show_tax', 'Categoría' )->set_text( 'Mostrar Categoría' )->add_dependency('filter')->set_width(33),
    Field::create( 'number', 'filter_first_year', 'Primer Año en el selector' )->set_minimum(2012)->set_maximum(date('Y'))->add_dependency('filter')->set_default_value(2012)->set_width(33),
);

$listing = Repeater_Group::create( 'Listing', array())
->add_fields($listing_fields_1)
->add_fields($listing_fields_2)
->add_fields($settings_fields_container->get_fields());