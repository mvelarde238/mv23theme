<?php
$componentes = get_post_meta( $componente['seccion_reusable'],'componentes', true);

foreach ($componentes as $componente ) { 
	$path = get_template_directory().'/inc/ultimate-fields/componentes/views/'.$componente['__type'].'.php';
	include $path;
}