<?php 
$options = get_option( 'mv23_general_options' );
$logo = $options['mv23_logo'];
$logo_url = ($logo) ? wp_get_attachment_image_src( $logo, 'full') : ''; 
?>
<div id="user-sidenav" class="user-sidenav side-nav">
	<header class="center-align">
		<div class="logo-wrapper">
			<img class="responsive-img" src="<?php echo $logo_url[0]; ?>" alt="logo">
		</div>
	</header>
	<?php echo do_shortcode('[user_panel]'); ?>
	<a href="#!" class="close-sidenav"></a>
</div>