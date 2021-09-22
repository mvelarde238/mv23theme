<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$archive_location = new Ultimate_Fields\Location\Post_Type();
$archive_location->add_post_type( 'archive_page' );
$archive_location->context = 'side';
// $archive_location->templates = 'templates/archive-page.php';

Container::create( 'archive_page_setting' )
    ->set_title('Settings')
    ->add_location($archive_location)
    ->set_description('Shortcodes: [posts] [pagination]')
    ->set_layout( 'grid' )
    ->set_style( 'seamless' )
    ->add_fields(array(
        Field::create( 'select', 'appears_on' )->add_options(array(
            'taxonomy' => 'Taxonomy',
            'posttype' => 'Post Type',
        )),
        Field::create( 'radio', 'connected_taxonomy','Taxonomia')->set_orientation( 'horizontal' )->add_options(ARCHIVE_OPTIONS_TAXONOMIES)->add_dependency('appears_on','taxonomy','='),
        Field::create( 'select', 'connected_posttype' )->add_options(ARCHIVE_OPTIONS_POSTTYPES)->add_dependency('appears_on','posttype','=')
    ));