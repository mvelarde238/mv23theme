<?php get_header(); 
$main_content_classes = array('main-content','container');
?>
<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
					<?php echo ultimate_fields_page_content(get_the_ID()); ?>
				</article>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>