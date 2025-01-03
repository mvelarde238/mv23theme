<?php
use Core\Frontend\Header;

$blog_title = get_bloginfo( 'name' ); 
$header = new Header(); 
$header_options = $header->get_options();
$header_style = ( !empty($header_options['styles']) ) ? 'style="'.implode(' ', $header_options['styles']).'"' : '';
?>
<section id="header" class="<?php echo implode(' ', $header_options['classes']) ?>" <?php echo $header_style ?>>
	<div class="header__content container">
		<div class="header__logo">
			<a class="header__logo__link" href="<?php echo home_url(); ?>">
				<?php if ( $header_options['logo'] ): ?>
					<img src="<?php echo $header_options['logo'] ?>" alt="Logo <?=$blog_title?>">
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

		<?php get_template_part('partials/mobile-header-buttons'); ?>
	</div>
	<div id="megamenus"></div>
</section>