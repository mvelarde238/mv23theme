<?php
use Core\FrontEnd\Header;

$blog_title = get_bloginfo( 'name' ); 
$header = new Header(); 

$classes = $header->get_classes();
$styles = $header->get_styles();

$header_style = ( !empty($styles) ) ? 'style="'.implode(' ', $styles).'"' : '';
?>
<section id="header" class="<?php echo implode(' ', $classes) ?>" <?php echo $header_style ?>>
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
		
		<?php get_template_part('partials/main-nav'); ?>
			
		<?php if (is_active_sidebar( 'header_widgets_1' )): ?>
			<div class="header__widgets">
				<?php dynamic_sidebar( 'header_widgets_1' ); ?>
			</div>
		<?php endif ?>

		<?php get_template_part('partials/mobile-menu-button'); ?>
	</div>
	<div id="megamenus"></div>
</section>