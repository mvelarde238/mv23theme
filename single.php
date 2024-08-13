<?php get_header(); 

$main_content_classes = array('main-content','container');
if(SINGLE_SIDEBAR) array_push($main_content_classes, SINGLE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( in_array($posttype, PAGE_HEADER_IN) ) echo Theme\Page_Header::getInstance()->display();
	?>
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<div class="page-module pdt0"><div class="component">
						<?php comments_template(); ?>
					</div></div>
				<?php endif;?>
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