<?php
use Core\Frontend\Nav_Walker;
?>
<div class="mobile-header-buttons menu-comp horizontal-nav-1">
	<?php
	wp_nav_menu(array(
		'container' => false,                           
		'container_class' => '',
		'theme_location' => 'mobile-header-buttons',
		'walker' => new Nav_Walker(),
	));
	?>
</div>