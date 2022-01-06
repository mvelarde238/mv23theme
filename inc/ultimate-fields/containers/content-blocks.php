<?php
use Ultimate_Fields\Container;
use Ultimate_Fields\Field;

// use Ultimate_Fields\Container\Layout_Group;
// $texto_fields_group = Layout_Group::create('texto')
//     ->set_title('Editor de Texto')
//     ->set_min_width(3)
//     ->set_layout('rows')
//     ->set_max_width(12)
//     ->set_title_template('<%= content %>')
//     ->add_fields(array(
//         Field::create('wysiwyg', 'content')->hide_label()->set_rows( 20 ),
//     ));

$tea = $text_editor_args;
$tea['title_template'] = '';
$tea['min_width'] = '3';

Container::create('content_blocks')
    ->add_location('post_type', CONTENT_BUILDER_POSTTYPES )
    ->set_layout('grid')
    ->set_style('seamless')
    ->add_fields(array(
        Field::create('layout', 'content_layout', 'Layout')->set_columns(12)->hide_label()->set_placeholder_text( 'Arrastre un componente aquí' )
            // ->add_group($texto_fields_group)
            ->add_group( 'Editor de Texto', $tea ),
    ));
    