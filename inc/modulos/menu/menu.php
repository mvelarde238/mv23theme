<div class="main-nav">
	<?php
	wp_nav_menu(array(
		'container' => false,                           
		'container_class' => '',
		'theme_location' => 'main-nav',
		'walker' => new Theme_Nav_Walker(),
	));
	?>
</div>

<div class="mobile-nav"> 
	<button data-activates="menu-movil" class="menu-movil__btn"><span></span><span></span><span></span></button>
</div>