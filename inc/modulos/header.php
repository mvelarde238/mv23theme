<?php 
$page_ID;
$key = 'post';

if(is_home()) {
	$page_ID = get_option( 'page_for_posts' );
} else if (is_post_type_archive()) {
	$archive_page_id = archive_page()->get_archive_id();
	$page_ID = ( !empty($archive_page_id) ) ? $archive_page_id : 0;
	$key = 'post';
} else if (is_archive()) {
	$page_ID = get_queried_object()->term_id;
	$key = 'term';
} else {
	$page_ID = get_the_ID();
}

$header_theme = get_metadata($key, $page_ID,'header_theme', true);
$header_theme = ($header_theme) ? $header_theme : 'theme1';

$replace_logo = get_metadata($key, $page_ID,'replace_logo', true);
$header_logo = get_metadata($key, $page_ID,'header_logo', true);
if ($replace_logo && !empty($header_logo)) {
	$logo_id = $header_logo; 
} else {
	$logo_id = ($header_theme == 'theme4' || $header_theme == 'theme5' || $header_theme == 'theme6') ?  get_option( 'main_logo' ) : get_option( 'secondary_logo' );
}

$logo_url = ($logo_id) ? wp_get_attachment_image_src( $logo_id, 'full') : ''; 
$hide_logo = get_metadata($key, $page_ID,'hide_logo', true);
$hide_logo_class = ($hide_logo) ? 'hide-logo' : '';
$hide_menu = get_metadata($key, $page_ID,'hide_menu', true);
?>
<section class="header <?=$hide_logo_class?>" data-theme="<?=$header_theme?>">
	<?php if (is_active_sidebar( 'header_widgets_1' )): ?>
		<div class="header__widgets">
			<div class="container">
				<?php dynamic_sidebar( 'header_widgets_1' ); ?>
			</div>
		</div>
	<?php endif ?>
	<div class="header__content container">
		<div class="header__logo">
			<a href="<?php echo home_url(); ?>">
				<?php if (is_array($logo_url) && $logo_url[0]): ?>
					<img src="<?php echo $logo_url[0] ?>" alt="Logo">
				<?php else: ?>
					<?php bloginfo( 'name' ); ?>
				<?php endif ?>
			</a>
		</div>
			
		<?php if(!$hide_menu) get_template_part('inc/modulos/menu/menu'); ?>	
	</div>

	<div id="megamenus"></div>
</section>