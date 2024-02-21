<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container\Repeater_Group;

$sources = array(
    'manual' => 'Seleccionar imágenes'
);
if(WPMEDIAFOLDER_IS_ACTIVE) $sources = array_merge( array('wp-media' => 'Seleccionar folder'), $sources );

$gallery_fields_basic = array(
    Field::create( 'tab', 'Contenido' ),
    Field::create( 'radio', 'source', 'Fuente')->set_orientation('horizontal')->add_options( $sources )->set_width(100),
    Field::create( 'gallery', 'gallery' )->add_dependency('source', 'manual', '=')->hide_label()->set_width(100),
);

if(WPMEDIAFOLDER_IS_ACTIVE) {
    $gallery_fields_wp_media = array(
        Field::create( 'select', 'wp_media_folder' )->add_terms( 'wpmf-category' )->fancy()->set_width(25)->add_dependency('source', 'wp-media', '='),
        Field::create( 'message', 'wp_media_folder_message', 'Página de creación de galerías' )->set_description('<a href="'.admin_url().'upload.php" target="_blank">WP Media Folders</a>')->add_dependency('source', 'wp-media', '=')->set_width(70),
    );
}

$gallery_fields_settings = array(
    Field::create( 'tab', 'Gallery Settings' ),
    Field::create( 'complex', 'wp_media_folder_settings', 'Settings' )->add_fields(array(
        // Field::create( 'checkbox', 'autoinsert' )->set_text( '¿Autoinsertar las imágenes agregadas a la galerîa?' ), // the shortcode needs the attachments id's
        Field::create( 'select', 'display', 'Tipo')->add_options( array(
            'default' => 'Default',
            'slider' => 'Slider',
            'masonry' => 'Masonry',
            // 'porfolio' => 'Portfolio'
        ))->set_width(14),
        Field::create( 'number', 'columns', 'Columnas' )->set_default_value(4)->set_width(10),
        Field::create( 'select', 'link', 'Acción al hacer click')->add_options( array(
            'none' => 'Ninguna',
            'file' => 'Mostrar en Pop-up',
            'post' => 'Página de imágen',
            'custom' => 'Link Personalizado'
        ))->set_default_value('file')->set_width(14),
        Field::create( 'select', 'size', 'Image size')->add_options( array(
            'thumbnail' => 'Thumbnail',
            'medium' => 'Medium',
            'large' => 'Large',
            'full' => 'Full',
        ))->set_default_value('large')->set_width(14),
        Field::create( 'select', 'targetsize', 'Lightbox size')->add_options( array(
            'thumbnail' => 'Thumbnail',
            'medium' => 'Medium',
            'large' => 'Large',
            'full' => 'Full',
        ))->set_default_value('full')->add_dependency('link','file','=')->set_width(14),
        // Field::create( 'select', 'orderby', 'Ordenar por')->add_options( array(
        //     'custom' => 'Personalizado',
        //     'rand' => 'Random',
        //     'title' => 'Tìtulo',
        //     'date' => 'Fecha'
        // ))->add_dependency('../wp_media_folder','0','!=')->set_width(14),
        // Field::create( 'select', 'order', 'Orden')->add_options( array(
        //     'DESC' => 'Descendente',
        //     'ASC' => 'Ascendente',
        // ))->set_width(14),
    )),
    Field::create( 'image_select', 'aspect_ratio' )->add_options(array(
        '1/1'  => array(
            'label' => '1:1',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-1.png'
        ),
        '4/3'  => array(
            'label' => '4:3',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-4-3.png'
        ),
        '16/9'  => array(
            'label' => '16:9',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-16-9.png'
        ),
        '2/1'  => array(
            'label' => '16:9',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-2-1.png'
        ),
        '2.5/1'  => array(
            'label' => '2.5:1',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-2_5-1.png'
        ),
        '4/1'  => array(
            'label' => '4:1',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-4-1.png'
        ),
        '3/4'  => array(
            'label' => '3:4',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-3-4.png'
        ),
        '9/16'  => array(
            'label' => '9:16',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-9-16.png'
        ),
        '1/2'  => array(
            'label' => '1:2',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-2.png'
        ),
        '1/2.5'  => array(
            'label' => '1:2.5',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-1-2_5.png'
        ),
        'default'  => array(
            'label' => 'default',
            'image' => get_template_directory_uri() . '/inc/ultimate-fields/images/aspect-ratio-default-b.png'
        ),
    )),
    Field::create( 'tab', 'Advanced' ),
    Field::create( 'text', 'gallery_id' )->set_width(30),
    Field::create( 'message', 'gallery_id_usage', 'Usar la siguiente clase para abrir la galería:' )->set_description('show-gallery--{gallery_id}')->add_dependency('gallery_id','','!=')->set_width(70),
    Field::create( 'checkbox', 'hide_gallery','Ocultar la galería' )->set_text( 'Activar' ),
);

$gallery = Repeater_Group::create( 'Galeria', array(
    'edit_mode' => 'popup',
    // 'layout' => 'table',
));

$gallery->add_fields( $gallery_fields_basic );
if(WPMEDIAFOLDER_IS_ACTIVE) $gallery->add_fields( $gallery_fields_wp_media );
$gallery->add_fields( $gallery_fields_settings );
$gallery->add_fields($settings_fields_container->get_fields());