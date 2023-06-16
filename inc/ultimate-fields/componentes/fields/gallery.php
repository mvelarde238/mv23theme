<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$gallery = Repeater_Group::create( 'Galeria', array(
    'edit_mode' => 'popup',
    // 'title_template' => '',
    'fields' => array( 
        Field::create( 'tab', 'Contenido' ),
        Field::create( 'select', 'wp_media_folder' )->add_terms( 'wpmf-category' )->fancy()->set_width(25),
        Field::create( 'message', 'wp_media_folder_message', 'Página de creación de galerías' )->set_description('<a href="'.admin_url().'upload.php" target="_blank">WP Media Folders</a>')->set_width(70),
        Field::create( 'complex', 'wp_media_folder_settings', 'Settings' )->add_fields(array(
            // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ), // the shortcode needs the attachments id's
            Field::create( 'select', 'display', 'Tipo')->add_options( array(
                'default' => 'Default',
                'slider' => 'Slider',
                'masonry' => 'Masonry',
                'porfolio' => 'Portfolio'
            ))->set_width(14),
            Field::create( 'number', 'columns', 'Columnas' )->set_default_value(4)->set_width(10),
            Field::create( 'select', 'link', 'Acción al hacer click')->add_options( array(
                'none' => 'Ninguna',
                'file' => 'Lightbox',
                'post' => 'Attachment Page',
                'custom' => 'Link Personalizado'
            ))->set_default_value('file')->set_width(14),
            Field::create( 'select', 'size', 'Image size')->add_options( array(
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large',
                'full' => 'Full',
            ))->set_default_value('medium')->set_width(14),
            Field::create( 'select', 'targetsize', 'Lightbox size')->add_options( array(
                'thumbnail' => 'Thumbnail',
                'medium' => 'Medium',
                'large' => 'Large',
                'full' => 'Full',
            ))->set_default_value('large')->set_width(14),
            Field::create( 'select', 'orderby', 'Ordenar por')->add_options( array(
                '' => 'Custom',
                'rand' => 'Random',
                'title' => 'Tìtulo',
                'date' => 'Fecha'
            ))->set_width(14),
            Field::create( 'select', 'order', 'Orden')->add_options( array(
                'DESC' => 'Descendente',
                'ASC' => 'Ascendente',
            ))->set_width(14),
        ))->add_dependency('wp_media_folder','0','!=')
    ),
))
->add_fields($settings_fields_container->get_fields());