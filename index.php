<?php 
get_header(); 

$main_content_classes = array('main-content','container');
if(ARCHIVE_SIDEBAR) array_push($main_content_classes,ARCHIVE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php get_template_part('partials/page-header'); ?>
	
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php get_template_part('partials/archive'); ?>
		</main>

		<?php if(ARCHIVE_SIDEBAR) get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>