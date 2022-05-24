<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$listing_cpts = LISTING_CPTS;
$listing_taxonomies = LISTING_TAXONOMIES;

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
);

if( is_array($listing_taxonomies) && count($listing_taxonomies) > 0 ){
    foreach($listing_taxonomies as $tax){
        array_push($listing_fields_1, Field::create( 'multiselect', $tax['cpt_slug'].'_terms', 'Categoría' )->add_terms( $tax['slug'] )->add_dependency('show','auto','=')->add_dependency('posttype', $tax['cpt_slug'], '=')->set_width(50));
    }
}

$listing_fields_2 = array( 
    Field::create( 'tab', 'List Template'),
    Field::create( 'radio', 'list_template', 'Template' )->set_orientation( 'vertical' )->add_options(LISTING_TEMPLATES)->hide_label(),
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
    Field::create( 'radio', 'post_template', 'Template' )->set_orientation( 'vertical' )->add_options(LISTING_POST_TEMPLATE)->hide_label(),
    
    Field::create( 'tab', 'Paginado'),
    Field::create( 'select', 'pagination_type', 'Paginado' )->add_dependency('show','auto','=')->add_options(LISTING_PAGINATION_TYPES)->hide_label(),
);

$listing_fields = array_merge($listing_fields_1,$listing_fields_2);

$listing_args = array(
    'fields' => array_merge($listing_fields, $settings_fields, $margenes, $bordes, $box_shadow, $animation)
);
$listing = Repeater_Group::create( 'Listing', $listing_args );