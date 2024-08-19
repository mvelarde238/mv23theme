<?php
use Theme\Nav_Walker;

$main_nav_classes = array_merge( array('main-nav','menu-comp'), MAIN_NAV_STYLE );
?>
<div class="<?php echo implode(" ", $main_nav_classes) ?>">
	<?php
	wp_nav_menu(array(
		'container' => false,                           
		'container_class' => '',
		'theme_location' => 'main-nav',
		'walker' => new Nav_Walker(),
	));
	?>
</div>

<button class="mobile-menu-button">
	<i class="fa fa-navicon"></i>
</button>
