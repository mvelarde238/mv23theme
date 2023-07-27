<?php 
$logo_id = (LOGOS_QUANTITY == 1) ? get_option( 'main_logo' ) : get_option( MOBILE_MENU_LOGO );
$logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : array(); 
?>
<div id="menu-movil" class="menu-movil side-nav text-color-2">
	<?php if ($logo_id): ?>
		<header>
			<a href="<?php echo home_url(); ?>"><img class="responsive-img" src="<?php echo $logo_url[0]; ?>" alt="logo"></a>
		</header>
	<?php endif ?>
	<?php wp_nav_menu(array('theme_location' => 'movil-nav', 'walker' => new Theme_Nav_Walker()) );  ?>
	<?php echo do_shortcode( '[redes_sociales class="style3 center"]' ); ?>
</div>