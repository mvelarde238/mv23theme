<?php
/**
 * Template Name: Archive Page
 *
 */
get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>
	<div class="main-content container">
		<main class="main">
			<?php echo ultimate_fields_page_content(get_the_ID()); ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>