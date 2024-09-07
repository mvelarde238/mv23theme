<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;
use Ultimate_Fields\Location\Post_Type;

$archive_location = new Post_Type();
$archive_location->add_post_type( 'archive_page' );
$archive_location->context = 'side';
// $archive_location->templates = 'templates/archive-page.php';

$post_types   = array();
$excluded = array( 'attachment', 'page' );
foreach( get_post_types( array('public'=>true), 'objects' ) as $id => $post_type ) {
	if( in_array( $id, $excluded ) ) {
		continue;
	}
	$post_types[ $id ] = __( $post_type->labels->name );
}
// hardcoded posttype:
if(USE_PORTFOLIO_CPT) $post_types['portfolio'] = 'Portfolio';

Container::create( 'archive_page_settings' )
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
        Field::create( 'select', 'connected_posttype' )->add_options($post_types)->add_dependency('appears_on','posttype','=')
    ));