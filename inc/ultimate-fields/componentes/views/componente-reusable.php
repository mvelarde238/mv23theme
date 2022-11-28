<?php
$modulos = get_post_meta( $componente['seccion_reusable'],'v23_modulos', true);

if (is_array($modulos) && count($modulos) > 0) :
    foreach ($modulos as $modulo) :
        print_module_view($modulo);
    endforeach;
endif;