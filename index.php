<?php 
use Theme_Custom_Fields\Theme_Options;

get_header(); 

$main_content_classes = array('main-content','container');

$theme_options = Theme_Options::getInstance();
$archive_page = $theme_options->get_pages_settings('archive');
if( !$archive_page['hide_sidebar'] ) array_push($main_content_classes, $archive_page['page_template']);
?>

<div id="content">
	<?php get_template_part('partials/page-header'); ?>
	
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php get_template_part('partials/archive'); ?>
		</main>

		<?php if( !$archive_page['hide_sidebar'] ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>