<?php 
use Core\Theme_Options\Theme_Options;
use Core\Builder\Component\Post_Content;
use Core\Builder\Component\Post_Title;
use Core\Builder\Component\Social_Share;
use Core\Builder\Component\Related_Posts;
use Core\Builder\Component\Comments_Area;

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
				<article id="post-<?php the_ID(); ?>" <?php post_class('components-wrapper'); ?>>
					<?php
					global $post;
					$page_content = get_post_meta($post->ID, 'page_content', true); 
					if( is_array($page_content) && !empty($page_content) ){
						the_content();
					} else {
						// fallback for pages without builder content:
						if(!$single_page['hide_post_title']) echo Post_Title::display();
						echo Post_Content::display();
						if(!$single_page['hide_social_share']) echo Social_Share::display();
						if(!$single_page['hide_related_posts']) echo Related_Posts::display();
						if(!$single_page['hide_comments_area']) echo Comments_Area::display();
					}
					?>
				</article>
			<?php endwhile; endif; ?>
		</main>

		<?php if( $single_page['page_template'] !== 'main-content--sidebarless' ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>