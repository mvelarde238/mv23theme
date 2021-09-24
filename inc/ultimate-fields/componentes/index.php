<?php
use Ultimate_Fields\Field;
use Ultimate_Fields\Container;
$nth_columnas = 4;

require_once( 'common-settings/bordes.php' );
require_once( 'common-settings/box-shadow.php' );
require_once( 'common-settings/animation.php' );
require_once( 'common-settings/margenes.php' );
require_once( 'common-settings/fondo.php' );
require_once( 'common-settings/id-and-class.php' );
require_once( 'common-settings/row-settings.php' );
require_once( 'common-settings/acciones.php' );

require_once( 'common-settings/columns-settings.php' );
require_once( 'common-settings/settings-fields.php' );
require_once( 'common-settings/utils.php' );

require_once( 'fields/componente-reusable.php' );
require_once( 'fields/modulo-reusable.php' );
require_once( 'fields/text-editor.php' );
require_once( 'fields/separador.php' );
require_once( 'fields/accordion.php' );
require_once( 'fields/carrusel.php' );
require_once( 'fields/mapa.php' );
require_once( 'fields/progress-circle.php' );
require_once( 'fields/progress-bar.php' );
require_once( 'fields/icon-and-text.php' );
require_once( 'fields/slider.php' );
if(TEMPLATE_PART) require_once( 'fields/template-part.php' );
include( locate_template( 'inc/ultimate-fields/componentes/utils/more-components.php' ) );

$componentes = array(
	array('name' => 'Editor de Texto', 'variable'=>$text_editor),
	array('name' => 'Separador', 'variable'=>$separador),
	array('name' => 'Accordion', 'variable'=>$accordion),
	array('name' => 'Carrusel', 'variable'=>$carrusel),
	array('name' => 'Mapa', 'variable'=>$mapa),
	array('name' => 'Progress Circle', 'variable'=>$progress_circle),
	array('name' => 'Progress Bar', 'variable'=>$progress_bar),
	array('name' => 'Icono y texto', 'variable'=>$icon_and_text),
	array('name' => 'Slider', 'variable'=>$slider),
	array('name' => 'Componente Reusable', 'variable'=>$componente_reusable)
);

if(TEMPLATE_PART) $componentes[] = array('name' => 'Template Part', 'variable'=>$template_part);

include( locate_template( 'inc/ultimate-fields/componentes/utils/edit-components.php' ) );



///////////// LOS SIGUIENTES ARCHIVOS USAN LA VARIABLE $componentes

// se usa en card, modulo, row 
$componentes_field = Field::create( 'repeater', 'componentes', '' )
    ->set_chooser_type( 'dropdown' )
    ->set_add_text('Agregar')
    ->set_attr( 'style', 'background: #c8d6e4;' );
foreach ($componentes as $c) {
    $componentes_field->add_group( $c['variable'] );
}

// se usa en columnas y columnas internas
$columna = Field::create( 'repeater', 'columna', '' )->set_chooser_type( 'dropdown' )->set_width( 25 )->set_add_text('Agregar');
foreach ($componentes as $c) {
    $columna->add_group( $c['variable'] );
}

require_once( 'fields/card.php' );
$columna->add_group( $card );

if(COLUMNAS_SIMPLES) require_once( 'fields/columnas-simples.php' );
require_once( 'fields/columnas-internas.php' );
if(ITEMS_GRID) require_once( 'fields/items-grid.php' );
if(CONTENT_SLIDER) require_once( 'fields/content-slider.php' );
// require_once( 'fields/seccion-prediseniada.php' );
require_once( 'fields/columnas.php' );
if(ROW) require_once( 'fields/row.php' );
require_once( 'fields/modulo.php' );