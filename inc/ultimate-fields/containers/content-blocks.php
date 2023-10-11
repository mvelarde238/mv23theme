<?php
use Ultimate_Fields\Container;

Container::create('content_blocks')
    ->add_location('post_type', CONTENT_BUILDER_POSTTYPES )
    ->set_layout('grid')
    ->set_style('seamless')
    ->add_fields(array(
        Content_Layout::the_field(array(
            'slug' => 'content_layout', 
            'title'=>'Layout', 
            'components' => CONTENT_BUILDER_COMPONENTS
            ))
    ));
    