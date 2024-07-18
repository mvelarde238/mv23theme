<?php get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>
	<div id="main-content" class="main-content  container">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>