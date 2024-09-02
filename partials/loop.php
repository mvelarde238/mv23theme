<div class="component">
	<div class="posts-listing has-columns" style="--d-gap:<?=LISTING_DESKTOP_GAP?>; --l-gap:<?=LISTING_LAPTOP_GAP?>; --t-gap:<?=LISTING_TABLET_GAP?>; --m-gap:<?=LISTING_MOBILE_GAP?>; --d-columns:<?=LISTING_DESKTOP_COLUMNS?>; --l-columns:<?=LISTING_LAPTOP_COLUMNS?>; --t-columns:<?=LISTING_TABLET_COLUMNS?>; --m-columns:<?=LISTING_MOBILE_COLUMNS?>;">
		<?php while (have_posts()) : the_post();	
			$queried_object = get_queried_object();
			get_template_part( 'partials/card/minipost', $queried_object->name );
		endwhile; ?>
	</div>
</div>