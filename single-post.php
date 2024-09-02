<?php get_header(); 

$main_content_classes = array('main-content','container');
if(SINGLE_SIDEBAR) array_push($main_content_classes, SINGLE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( in_array($posttype, PAGE_HEADER_IN) ) get_template_part('partials/page-header');
	?>
	<div class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                
				<?php get_template_part('partials/post-title'); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
				
				<?php get_template_part('partials/comments'); ?>
			<?php endwhile; endif; ?>
		</main>

		<?php if(SINGLE_SIDEBAR) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>