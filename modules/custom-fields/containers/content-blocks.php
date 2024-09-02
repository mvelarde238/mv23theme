<?php
use Ultimate_Fields\Container;

Container::create('content_blocks')
    ->add_location('post_type', CONTENT_BUILDER_POSTTYPES )
    ->set_layout('grid')
    ->set_style('seamless')
    ->add_fields(array(
        Blocks_Layout::the_field(array( 
            'exclude' => array( 'inner_columns' ) 
            ))
    ));