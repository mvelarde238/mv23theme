<?php
use Core\Builder\Component\Sidebar;

$sidebar_id = 'page_sidebar';
if( USE_PORTFOLIO_CPT && ( is_post_type_archive('portfolio-cat') || is_tax('portfolio-tag') || is_singular('portfolio') ) ){
	$sidebar_id = 'portfolio_sidebar';
} 

echo Sidebar::display( array( 'sidebar_id' => $sidebar_id ) );
?>