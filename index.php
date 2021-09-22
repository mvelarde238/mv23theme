<?php get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>
	<div class="main-content container">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php if (have_posts()) : ?>
				<div class="page-module no-padding">
					<div class="componente">
						<div class="posts-listing posts-listing--style1">
							<?php while (have_posts()) : the_post(); 
								get_template_part( 'inc/partials/minipost');
							endwhile; ?>
						</div>
					</div>
				</div>
				<?php mv23_page_navi(); ?>
			<?php endif; ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>