<?php
get_template_part('inc/modulos/modals/image-modal');
get_template_part('inc/modulos/modals/video-modal');
get_template_part('inc/modulos/modals/testimonio-modal');
get_template_part('inc/modulos/modals/post-modal');

if(WOOCOMMERCE_IS_ACTIVE){
    get_template_part('inc/modulos/modals/minicart-sidenav');
}