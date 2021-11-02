<?php get_header(); ?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( !in_array($posttype, DISABLE_PAGE_HEADER_IN) ) get_template_part('inc/modulos/page-header'); 
	?>
	<div class="main-content container">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
					<?php echo ultimate_fields_page_content(get_the_ID()); ?>
				</article>
				<?php comments_template(); ?>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>