<?php
use Core\Builder\Component\Aside;

$args = array(
	'components' => array(
		array(
			'type' => 'sidebar',
			'sidebar' => 'page_sidebar'
		)
	)
);

echo Aside::display($args);
?>