<?php
use Core\Frontend\Nav_Walker;
$mobile_nav_classes = array_merge( array('mobile-header-buttons', 'menu-comp'), MOBILE_NAV_STYLE );
?>
<div class="<?php echo implode(" ", $mobile_nav_classes) ?>">
	<?php
	wp_nav_menu(array(
		'container' => false,                           
		'container_class' => '',
		'theme_location' => 'mobile-header-buttons',
		'walker' => new Nav_Walker(),
	));
	?>
</div>