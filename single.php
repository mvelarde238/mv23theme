<?php get_header(); 

$main_content_classes = array('main-content','container');
if(SINGLE_SIDEBAR) array_push($main_content_classes, SINGLE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( !in_array($posttype, DISABLE_PAGE_HEADER_IN) ) get_template_part('inc/modulos/page-header'); 
	?>
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
					<?php echo ultimate_fields_page_content(get_the_ID()); ?>
				</article>
				<div class="page-module pdt0"><div class="componente">
					<?php comments_template(); ?>
				</div></div>
			<?php endwhile; endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>

		<?php if(SINGLE_SIDEBAR): ?>
        	<div class="sidebar">
				<div style="height:100%">
					<div class="pinned-block">
						<?php if (is_active_sidebar('page_sidebar')) : ?>
							<?php dynamic_sidebar('page_sidebar'); ?>
						<?php endif ?>
					</div>
				</div>
        	</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>