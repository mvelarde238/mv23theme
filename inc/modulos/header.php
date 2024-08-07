<?php
$blog_title = get_bloginfo( 'name' ); 
$header = new Header(); 

$classes = $header->get_classes();
$styles = $header->get_styles();
?>
<section id="header" 
	class="<?php echo implode(' ', $classes) ?>" 
	style="<?php echo implode(' ', $styles) ?>">
	<div class="header__content container">
		<div class="header__logo">
			<a class="header__logo__link" href="<?php echo home_url(); ?>">
				<?php if ( $header->get_logo() ): ?>
					<img src="<?php echo $header->get_logo() ?>" alt="Logo <?=$blog_title?>">
				<?php else: ?>
					<?php echo $blog_title; ?>
				<?php endif ?>
			</a>
		</div>
		<div class="header__blocks">
			<?php get_template_part('inc/modulos/main-nav'); ?>	
			
			<?php if (is_active_sidebar( 'header_widgets_1' )): ?>
				<div class="header__widgets">
					<?php dynamic_sidebar( 'header_widgets_1' ); ?>
				</div>
			<?php endif ?>
		</div>
	</div>
	<div id="megamenus"></div>
</section>