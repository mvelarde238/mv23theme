<?php 
use Core\Posttype\Archive_Page;

get_header(); 

$main_content_classes = array('main-content','container');

$archive_page = Archive_Page::getInstance();
$page_template_settings = $archive_page->get_page_template_settings();
if( $page_template_settings['has_sidebar'] ) array_push($main_content_classes, $page_template_settings['class']);
?>
<div id="content">
	<?php get_template_part('partials/page-header'); ?>
	
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main">
			<?php get_template_part('partials/archive'); ?>
		</main>

		<?php if( $page_template_settings['has_sidebar'] ) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>