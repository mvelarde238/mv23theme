<?php get_header(); 
$main_content_classes = array('main-content','container');
?>
<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>">
					<?php the_content(); ?>
				</article>
			<?php endwhile; endif; ?>
		</main>
	</div>
</div>

<?php get_footer(); ?>