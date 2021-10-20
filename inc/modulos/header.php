<?php
$blog_title = get_bloginfo( 'name' ); 
$header = new Header(); 
?>
<section <?php echo $header->get_class() ?> <?php echo $header->get_style() ?>>
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
				<?php if ( $header->get_logo() ): ?>
					<img src="<?php echo $header->get_logo() ?>" alt="Logo <?=$blog_title?>">
				<?php else: ?>
					<?php echo $blog_title; ?>
				<?php endif ?>
			</a>
		</div>
		<?php get_template_part('inc/modulos/menu/menu'); ?>	
	</div>
	<div id="megamenus"></div>
</section>