<?php 
use Core\FrontEnd\Page;
get_header(); 
?>

<footer id="footer" class="footer">
	<div class="footer__content container">
		<?php 
		$page = new Page();
		echo $page->the_content(); 
		?>
	</div>
	<a class="subir-btn" href="#content" data-state="hidden"><i class="fa fa-angle-up"></i></a>
</footer>

<?php get_footer(); ?>