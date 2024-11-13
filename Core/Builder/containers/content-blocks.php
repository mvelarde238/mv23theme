<?php
use Ultimate_Fields\Container;
use Core\Builder\Blocks_Layout;
use Ultimate_Fields\Field;

Container::create('content_blocks')
    ->add_location('post_type', CONTENT_BUILDER_POSTTYPES )
    ->set_layout('grid')
    ->set_style('seamless')
    ->add_fields(array(
        Blocks_Layout::the_field(array( 
            'exclude' => array( 'inner_columns' ) 
        )),
        Field::create( 'layout_control', 'blocks_control', 'Layout Control' )
            ->set_field( 'blocks_layout' )
            ->set_attr( 'style', 'border-top:1px solid silver; background: #eeeeee;' )
    ));