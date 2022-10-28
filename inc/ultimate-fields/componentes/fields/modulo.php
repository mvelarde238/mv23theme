<?php
use Ultimate_Fields\Container\Repeater_Group;
use Ultimate_Fields\Field;

$modulo_componentes_field = clone $componentes_field;
$modulo_componentes_field->add_group($columnas);
if (CONTENT_SLIDER) $modulo_componentes_field->add_group($content_slider);
if (COLUMNAS_SIMPLES) $modulo_componentes_field->add_group($columnas_simples);
if (ITEMS_GRID) $modulo_componentes_field->add_group($items_grid);
if (ROW) $modulo_componentes_field->add_group($row);

$fields = array(
    Field::create('tab', 'Contenido'),
    $modulo_componentes_field,
    Field::create('tab', 'Settings'),
);

$nobug_id_and_class = $id_and_class;
$fields = array_merge($fields, $nobug_id_and_class);

$fields[] = Field::create('select', 'visibility', 'Visibilidad')->add_options(array(
    '' => 'Visible para todos los usuarios',
    'user_is_logged_in' => 'Visible para usuarios registrados',
    'user_is_not_logged_in' => 'Visible para usuarios no registrados',
    'is_private' => 'Solo visible para usuarios admin.',
))->set_width(33);

$fields[] = Field::create('select', 'layout')->add_options(array(
    'layout2' => 'Fondo extendido / Contenido centrado',
    'layout1' => 'Estándar',
    'layout3' => 'Todo extendido',
))->set_width(33);

$fields[] = Field::create('checkbox', 'delete_margins')->set_text('Quitar Márgenes')->hide_label()->set_attr('style', 'background: #eeeeee; width: 100%');
$fields[] = Field::create('complex', 'padding', 'Borrar Márgenes')->hide_label()->add_fields(array(
    Field::create('checkbox', 'top')->set_width(25)->set_text('Superior')->hide_label(),
    Field::create('checkbox', 'bottom')->set_width(25)->set_text('Inferior')->hide_label(),
))->add_dependency('delete_margins');

// $fields[] = Field::create('tab', 'Fondo');

$others = array(
    Field::create('checkbox', 'edit_background')->set_text('Editar Fondo')->hide_label()->set_attr('style', 'background: #eeeeee; width: 100%'),
    Field::create('image', 'bgi', 'Imágen de Fondo')->set_width(20)->add_dependency('edit_background'),
    Field::create('complex', 'bgi_options', '')->set_width(20)->add_fields(array(
        Field::create('select', 'size', 'Tamaño')->add_options(array(
            'cover' => 'Cubrir Todo',
            'auto' => 'Automático',
        )),
        Field::create('select', 'repeat', 'Repetir')->add_options(array(
            'no-repeat' => 'No Repetir',
            'repeat' => 'Ambas direcciones',
            'repeat-x' => 'Solo horizontal',
            'repeat-y' => 'Solo en vertical',
        )),
        Field::create('select', 'position_x', 'Posición Eje Horizontal')->add_options(array(
            'center' => 'Centro',
            'left' => 'Izquierda',
            'right' => 'Derecha',
        )),
        Field::create('select', 'position_y', 'Posición Eje Vertical')->add_options(array(
            'center' => 'Centro',
            'top' => 'Arriba',
            'bottom' => 'Abajo',
        )),
    ))->add_dependency('bgi', '0', '>')->add_dependency('edit_background'),
    Field::create('checkbox', 'add_bgc', 'Usar color de fondo')->set_width(20)->set_text('Activar')->add_dependency('edit_background'),
    Field::create('color', 'bgc', 'Color de Fondo')->set_width(20)->set_default_value('#ffffff')->add_dependency('add_bgc')->add_dependency('edit_background'),
    Field::create('select', 'text_color', 'Color del texto')->add_options(array(
        'text-color-default' => 'Negro',
        'text-color-2' => 'Blanco',
    ))->set_default_value('text-color-default')->set_width(20)->add_dependency('edit_background'),
    Field::create('checkbox', 'parallax', 'Parallax')->set_width(20)->add_dependency('edit_background'),

    Field::create( 'checkbox', 'add_video_bg' )->set_text( 'Agregar video de fondo' )->hide_label()->set_attr( 'style', 'background: #eeeeee; width: 100%' ),
    Field::create( 'video', 'bgvideo', 'Video de Fondo' )->set_width( 25 )->add_dependency('add_video_bg'),
    Field::create( 'number', 'video_opacity', 'Transparencia del video' )->enable_slider( 0, 100 )->set_default_value(100)->set_step( 5 )->set_width( 75 )->add_dependency('add_video_bg')
);

$fields = array_merge($fields, $others);

$modulos = Repeater_Group::create('Módulos')
    ->set_title('Módulo')
    ->set_icon('dashicons dashicons-welcome-widgets-menus')
    ->add_fields($fields);
