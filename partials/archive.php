<?php 
use Core\Posttype\Archive_Page;
use Core\Frontend\Page;

$archive_page = Archive_Page::getInstance();
$archive_page_id = $archive_page->get_archive_id();
if ( !empty($archive_page_id) ) :
	$page = new Page();
	echo $page->the_content( $archive_page_id );
else : ?>
	<div class="page-module">
		<?php get_template_part('partials/loop'); ?>
		<?php if (have_posts()) get_template_part('partials/pagination'); ?>
	</div>
<?php endif; ?>