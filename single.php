<?php 
use Core\Theme_Options\Theme_Options;

get_header(); 

$main_content_classes = array('main-content','container');

$theme_options = Theme_Options::getInstance();
$single_page = $theme_options->get_page_template_settings('single');
$main_content_classes[] = $single_page['page_template'];
?>

<div id="content">
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if(!$single_page['hide_post_title']) get_template_part('partials/post-title'); ?>
					<?php the_content(); ?>
				</article>

				<?php if(!$single_page['hide_social_share']) get_template_part('partials/social-share'); ?>
				
				<?php if(!$single_page['hide_related_posts']) get_template_part('partials/related-posts'); ?>
				
				<?php get_template_part('partials/comments'); ?>
			<?php endwhile; endif; ?>
		</main>

		<?php if( $single_page['page_template'] !== 'main-content--sidebarless' ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>