<?php
get_template_part( 'inc/posttypes/seccion-reusable' );
get_template_part( 'inc/posttypes/accordion' );
get_template_part( 'inc/posttypes/megamenu' );

if(USE_PORTFOLIO_CPT){
	get_template_part( 'inc/posttypes/portfolio' );
}

if (is_admin()) {
	get_template_part( 'inc/posttypes/mv23-library' );
}

if (!function_exists('mv23_include_posttypes')) {
	function mv23_include_posttypes(){ return; }
}