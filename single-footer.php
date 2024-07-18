<?php get_header(); 
$main_content_classes = array('main-content','container');
?>
<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<footer id="footer" class="footer">
	<div class="footer__content container">
		<?php the_content(); ?>
	</div>
	<a class="subir-btn" href="#content" data-state="hidden"><i class="fa fa-angle-up"></i></a>
</footer>

<?php get_footer(); ?>