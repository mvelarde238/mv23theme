<?php
use Core\FrontEnd\Page;

$theme_footer_post = get_option('theme_footer_post');

if ($theme_footer_post && !is_singular('footer')): 
	$theme_footer_post = str_replace('post_', '', $theme_footer_post);
	if( IS_MULTILANGUAGE && function_exists('pll_get_post') ) $theme_footer_post = pll_get_post($theme_footer_post);
	?>
	<footer id="footer" class="footer">
		<div class="footer__content container">
			<?php 
			$page = new Page();
			echo $page->the_content( $theme_footer_post ); 
			?>
		</div>
		<a class="subir-btn" href="#content" data-state="hidden"><i class="fa fa-angle-up"></i></a>
	</footer>
<?php endif ?>