<?php
$sidebar = 'page_sidebar';
if( USE_PORTFOLIO_CPT && ( is_post_type_archive('portfolio-cat') || is_tax('portfolio-tag') || is_singular('portfolio') ) ) $sidebar = 'portfolio_sidebar';
?>
<div class="sidebar">
	<div style="height:100%">
		<div class="pinned-block">
			<?php if (is_active_sidebar($sidebar)) : ?>
				<?php dynamic_sidebar($sidebar); ?>
			<?php endif ?>
		</div>
	</div>
</div>