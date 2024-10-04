<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

Container::create( 'test_lel' ) 
    ->add_location( 'post_type', UF_POSTTYPES )
    ->set_layout( 'rows' )
    ->add_fields(array(
        Blocks_Layout::the_field(array( 
            'slug' => 'lel_content', 
            'components' => array( 'text_editor', 'image', 'spacer', 'button' )
        ))
    ));

add_action('admin_head', 'container_to_object');

function container_to_object() {
    $container_data = array();
    foreach( Container::get_registered() as $container ) {
        if( $container->get_id() == 'test_lel' ) {
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
    