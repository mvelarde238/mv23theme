<?php
$modulos_reusables = array( '0'=>'Elige' );
$secciones = get_posts( array('post_type' => 'seccion_reusable','posts_per_page' => -1, 'post_status' => 'publish') );

for ($i=0; $i < count($secciones); $i++) { 
	setup_postdata( $secciones[$i] );
	$modulos_reusables[$secciones[$i]->ID] = $secciones[$i]->post_title;
};
wp_reset_postdata();