<?php
$componentes_reusables = array( '0'=>'Elige' );
$modulos_reusables = array( '0'=>'Elige' );

$secciones = get_posts( array('post_type' => 'seccion_reusable','posts_per_page' => -1, 'post_status' => 'publish') );

for ($i=0; $i < count($secciones); $i++) { 
	setup_postdata( $secciones[$i] );
	$key = get_post_meta($secciones[$i]->ID,'section_type',true);
	if ($key == 'modulo') {
		$modulos_reusables[$secciones[$i]->ID] = $secciones[$i]->post_title;
	}
	if ($key == 'componente') {
		$componentes_reusables[$secciones[$i]->ID] = $secciones[$i]->post_title;
	}
};
wp_reset_postdata();