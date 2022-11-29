<?php
use Ultimate_Fields\Field;
$nth_columnas = 4;
require_once( 'utils/get-secciones-reusables.php' );
require_once( 'common-settings/index.php' );

require_once( 'fields/text-editor.php' );
require_once( 'fields/image.php' );
require_once( 'fields/separador.php' );
require_once( 'fields/carrusel.php' );
require_once( 'fields/mapa.php' );
if(PROGRESS_CIRCLE) require_once( 'fields/progress-circle.php' );
if(PROGRESS_BAR) require_once( 'fields/progress-bar.php' );
require_once( 'fields/icon-and-text.php' );
require_once( 'fields/slider.php' );
require_once( 'fields/componente-reusable.php' );
require_once( 'fields/listing.php' );

$componentes = array(
	array('name' => 'Editor de Texto', 'variable'=>$text_editor),
	array('name' => 'Imágen', 'variable'=>$image),
	array('name' => 'Separador', 'variable'=>$separador),
	array('name' => 'Carrusel', 'variable'=>$carrusel),
	array('name' => 'Mapa', 'variable'=>$mapa),
	array('name' => 'Icono y texto', 'variable'=>$icon_and_text),
	array('name' => 'Slider', 'variable'=>$slider),
	array('name' => 'Componente Reusable', 'variable'=>$componente_reusable),
	array('name' => 'Listing', 'variable'=>$listing)
);

if(PROGRESS_CIRCLE) $componentes[] = array('name' => 'Progress Circle', 'variable'=>$progress_circle);
if(PROGRESS_BAR) $componentes[] = array('name' => 'Progress Bar', 'variable'=>$progress_bar);

include( locate_template( 'inc/ultimate-fields/componentes/utils/edit-components.php' ) );

// used by accordion and page_content:
require_once( 'fields/modulo-reusable.php' );

///////////// LOS SIGUIENTES ARCHIVOS USAN LA VARIABLE $componentes

require_once( 'utils/class-content-layout.php' );
new Content_Layout($componentes);

// se usa en card, modulo, row 
$components_repeater = Field::create( 'repeater', 'componentes', '' )
->set_chooser_type( 'dropdown' )
->set_add_text('Agregar')
->set_attr( 'style', 'background: #c8d6e4;' );
foreach ($componentes as $c) {
	$components_repeater->add_group( $c['variable'] );
}
require_once( 'fields/accordion.php' );
$components_repeater->add_group($accordion);

// se usa en columnas y columnas internas
$columna = clone $components_repeater;
$columna->set_name('columna');
// $columna->set_width(25); it's not working
$columna->set_attr( 'style', 'background: #ffffff; width:25%' );
if(CARD){
	require_once( 'fields/card.php' );
	$columna->add_group( $card );
}

if(COLUMNAS_SIMPLES) require_once( 'fields/columnas-simples.php' );
require_once( 'fields/columnas-internas.php' );
if(ITEMS_GRID) require_once( 'fields/items-grid.php' );
if(CONTENT_SLIDER) require_once( 'fields/content-slider.php' );
// require_once( 'fields/seccion-prediseniada.php' );
require_once( 'fields/columnas.php' );
if(ROW) require_once( 'fields/row.php' );
require_once( 'fields/modulo.php' );