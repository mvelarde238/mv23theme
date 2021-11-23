<?php
$componentes = get_post_meta( $componente['seccion_reusable'],'componentes', true);

foreach ($componentes as $componente ) { 
	set_query_var( 'componente', $componente );
    get_template_part( 'inc/ultimate-fields/componentes/views/'.$componente['__type'] );
}