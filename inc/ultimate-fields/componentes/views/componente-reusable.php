<?php
$modulos = get_post_meta( $componente['seccion_reusable'],'v23_modulos', true);

if (is_array($modulos) && count($modulos) > 0) :
    foreach ($modulos as $modulo) :
        // print_module_view($modulo);
        $componentes = $modulo['componentes'];
        foreach ($componentes as $c) {
			set_query_var('componente', $c);
			get_template_part('/inc/ultimate-fields/componentes/views/' . $c['__type']);
		}
    endforeach;
endif;