<?php get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>
	<div class="main-content container">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php $content = get_the_content(); ?>
					<?php if ($content): ?>
						<div class="page-module"><div class="componente"><?php the_content(); ?></div></div>
					<?php endif ?>
					<?php echo ultimate_fields_page_content(get_the_ID()); ?>
				</article>
				<?php comments_template(); ?>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>