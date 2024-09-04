<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

$alignment = array(
    'flex-start'   => __('Start','default'),
    'center' => __('Center','default'),
    'space-between' => __('Space Between','default'),
    'flex-end' => __('End','default')
);

Container::create( 'blocks_layout_settings_container' ) 
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Field::create('select','layout')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options(array(
            'grid'   => 'Grid',
            'flex' => 'Flex'
        )),
        Field::create('message','hint_1')->set_description(__('The width of the components will be as configured','default'))->add_dependency('layout','grid')->hide_label(),
        Field::create('message','hint_2')->set_description(__('The width of the components will be determined by their content.','default'))->add_dependency('layout','flex')->hide_label(),
        Field::create('select','justify_content')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options($alignment),
        Field::create('select','align_items')->set_input_type( 'radio' )->set_orientation( 'horizontal' )->add_options($alignment)
    ));

add_action('admin_head', 'container_to_object');

function container_to_object() {
    $container_data = array();
    foreach( Container::get_registered() as $container ) {
        if( $container->get_id() == 'blocks_layout_settings_container' ) {
            $container_data = $container->export_fields_settings();
        }
    }
    $container_data = json_encode( $container_data );
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin inline script loaded');
            var containerData = <?php echo $container_data; ?>;
            console.log(containerData);
        });
    </script>
    <?php
}
    