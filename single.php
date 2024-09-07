<?php 
use Theme_Custom_Fields\Theme_options;

get_header(); 

$main_content_classes = array('main-content','container');

$theme_options = Theme_options::getInstance();
$single_page = $theme_options->get_pages_settings('single');
if( !$single_page['hide_sidebar'] ) array_push($main_content_classes, $single_page['page_template']);
?>

<div id="content">
	<?php
	$queried_object = get_queried_object();
	$posttype = $queried_object->post_type;
	if( in_array($posttype, PAGE_HEADER_IN) ) get_template_part('partials/page-header');
	?>
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<?php if($posttype == 'post') get_template_part('partials/post-title'); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>

				<?php get_template_part('partials/comments'); ?>
			<?php endwhile; endif; ?>
		</main>

		<?php if( !$single_page['hide_sidebar'] ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>