<?php 
use Core\Posttype\Archive_Page;
$archive_page = Archive_Page::getInstance();

$columns = $archive_page->get_loop_columns();
$gap = $archive_page->get_columns_gap();
$postcard_settings = $archive_page->get_postcard_settings();

$posttype = get_post_type();
$postcard_template = ( !empty($postcard_settings['template']) ) ? $postcard_settings['template'] : $posttype;

do_action('before_loop');
?>
<div class="component">
	<div class="posts-listing has-columns" style="--d-gap:<?=$gap['desktop']?>px; --l-gap:<?=$gap['laptop']?>px; --t-gap:<?=$gap['tablet']?>px; --m-gap:<?=$gap['mobile']?>px; --d-columns:<?=$columns['desktop']?>; --l-columns:<?=$columns['laptop']?>; --t-columns:<?=$columns['tablet']?>; --m-columns:<?=$columns['mobile']?>;">
		<?php
		do_action('on_archive_listing_start');

		$count = 0;
		while (have_posts()) : the_post();

			$_postcard_template = apply_filters('filter_listing_postcard_template', $postcard_template, $count);

			get_template_part( 'partials/card/postcard', $_postcard_template, array( 
				'post_template' => $_postcard_template,
                'on_click_post' => $postcard_settings['on_click_post'],
                'on_click_scroll_to' => $postcard_settings['on_click_scroll_to']
            ));

			$count++;
		endwhile;

		do_action('on_archive_listing_end');
		?>
	</div>
</div>