<?php 
use Core\Posttype\Archive_Page;
$archive_page = Archive_Page::getInstance();

$columns = $archive_page->get_loop_columns();
$gap = $archive_page->get_columns_gap();
$postcard_settings = $archive_page->get_postcard_settings();

$posttype = get_post_type();
$postcard_template = ( !empty($postcard_settings['template']) ) ? $postcard_settings['template'] : $posttype;
?>
<div class="component">
	<div class="posts-listing has-columns" style="--d-gap:<?=$gap['desktop']?>px; --l-gap:<?=$gap['laptop']?>px; --t-gap:<?=$gap['tablet']?>px; --m-gap:<?=$gap['mobile']?>px; --d-columns:<?=$columns['desktop']?>; --l-columns:<?=$columns['laptop']?>; --t-columns:<?=$columns['tablet']?>; --m-columns:<?=$columns['mobile']?>;">
		<?php while (have_posts()) : the_post();
			get_template_part( 'partials/card/postcard', $postcard_template, array( 
                'on_click_post' => $postcard_settings['on_click_post'],
                'on_click_scroll_to' => $postcard_settings['on_click_scroll_to']
            ));
		endwhile; ?>
	</div>
</div>