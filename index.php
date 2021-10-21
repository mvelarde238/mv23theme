<?php get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>
	<div class="main-content container">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php
			$archive_page_id = archive_page()->get_archive_id();
			if ( !empty($archive_page_id) ) :
				echo ultimate_fields_page_content($archive_page_id);
			else : ?>
				<?php if (have_posts()) : ?>
					<div class="page-module no-padding"><div class="componente">
						<div class="posts-listing posts-listing--style1">
							<?php while (have_posts()) : the_post();	
								$queried_object = get_queried_object();
								get_template_part( 'inc/partials/minipost', $queried_object->name );
							endwhile; ?>
						</div>
					</div></div>
					<?php mv23_page_navi(); ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>