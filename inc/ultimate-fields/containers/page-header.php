<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

if(!function_exists('custom_page_header_options')){
    function custom_page_header_options(){
        return array();
    }
}

if(!function_exists('custom_page_headers')){
    function custom_page_headers(){
        return null;
    }
}
$custom_page_headers = custom_page_headers();

$uf_posttypes = \array_diff(array_merge(UF_POSTTYPES,CONTENT_BUILDER_POSTTYPES), DISABLE_PAGE_HEADER_IN);
$page_header_elements = array(
    'default' => 'Título de la página',
    'slider' => 'Slider',
    'contenido' => 'Contenido',
    'ninguno' => 'Ninguno',
);
if($custom_page_headers){
    foreach ($custom_page_headers as $item) {
        $page_header_elements[$item['key']] = $item['title'];
    }
}

$tab_contenido = array(
    Field::create('tab', 'Contenido'),
    Field::create('radio', 'page_header_element', 'Seleccione que tipo de contenido se va mostrar:')->set_orientation('horizontal')->add_options($page_header_elements),
    // Field::create( 'wp_objects', 'posts', '' )->add( 'posts', 'posts' )->set_button_text( 'Selecciona los posts' )->add_dependency('page_header_element','siempre-hidden-mv23','='),
    Field::create('textarea', 'slider_desktop')->set_rows(1)->set_width(50)->add_dependency('page_header_element', 'slider', '='),
    Field::create('textarea', 'slider_movil')->set_rows(1)->set_width(50)->add_dependency('page_header_element', 'slider', '=')
);

if( PAGE_HEADER_CONTENT_BUILDER ){
    $content_type = Content_Layout::the_field(array( 
        'slug' => 'page_header_content2', 
        'components' => array( 'Editor de Texto', 'Imágen' ), 
        'dependencies' => array(
            array( 'field' => 'page_header_element', 'value' => 'contenido', 'compare' => '=' )
        )
    ));

} else {
    $content_type = Field::create('wysiwyg', 'page_header_content')->hide_label()->set_rows(1)->add_dependency('page_header_element', 'contenido', '=');
}
$tab_contenido[] = $content_type;

if($custom_page_headers){
    foreach ($custom_page_headers as $item) {
        $tab_contenido = array_merge($tab_contenido, $item['fields']);
    }
}

$tab_fondo = array(
    Field::create('tab', 'Fondo'),
    Field::create('image', 'page_header_bgi', 'Imágen de Fondo')->set_width(20)->set_default_value(PAGE_HEADER_BGI),
    Field::create('complex', 'page_header_video')->add_fields(array(
        Field::create( 'video', 'files', 'Video de Fondo' ),
        Field::create( 'number', 'opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )
    ))->set_width(20)->hide_label(),
    Field::create('color', 'page_header_bgc', 'Color de Fondo')->set_width(20)->set_default_value(PAGE_HEADER_BGC),
    Field::create('select', 'page_header_text_color', 'Color del texto')->set_width(20)->add_options(array(
        'text-color-2' => 'Blanco',
        'text-color-default' => 'Negro',
    ))->set_default_value(PAGE_HEADER_TEXT_COLOR),
    Field::create('radio', 'page_header_bgi_parallax', 'Parallax')->set_width(20)->add_options(array(
        '0' => 'Desactivar',
        '1' => 'Activar',
    ))->set_orientation('horizontal')->set_default_value('0'),
);
$tab_settings = array(
    Field::create('tab', 'Settings'),
    Field::create('text', 'page_header_id', 'ID')->set_width(50)->set_validation_rule('^[a-z][a-za-z0-9_-]+$')
        ->set_description('Identificador -ID- de la sección, usar solo minúsculas y guiones ( - )'),
    Field::create('text', 'page_header_class', 'Clases')->set_width(50)
        ->set_description('Clases de la sección, usar solo minúsculas y guiones ( -/_ )'),
    Field::create('checkbox', 'page_header_padding', 'Márgenes')->set_width(33)->set_text('Borrar Márgenes'),
    Field::create('select', 'page_header_layout')->set_width(33)->add_options(array(
        'layout2' => 'Fondo extendido / Contenido centrado',
        'layout1' => 'Estándar',
        'layout3' => 'Todo extendido',
    ))->set_default_value(PAGE_HEADER_LAYOUT),
);

$page_header_container = Container::create('page_header')
    ->add_location('post_type', $uf_posttypes)
    ->add_location('taxonomy', 'category')
    // ->add_location( 'taxonomy', 'product_cat')
    ->set_title('Page Title')
    ->set_layout('grid')
    ->set_style('seamless')
    ->add_fields(array(
        Field::create('section', 'page_header_section', 'Page Title')->set_color('blue'), 
    ));

$page_header_container->add_fields($tab_contenido);
$page_header_container->add_fields($tab_fondo);
$page_header_container->add_fields(custom_page_header_options());
$page_header_container->add_fields($tab_settings);