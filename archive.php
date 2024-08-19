<?php get_header(); 

$main_content_classes = array('main-content','container');
if(ARCHIVE_SIDEBAR) array_push($main_content_classes,ARCHIVE_MAIN_CONTENT_TEMPLATE);
?>

<div id="content">
	<?php get_template_part('partials/page-header');; ?>
	<div id="main-content" class="<?php echo implode(' ',$main_content_classes) ?>">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php
			$archive_page_id = archive_page()->get_archive_id();
			if ( !empty($archive_page_id) ) :
				echo Page::getInstance()->the_content( $archive_page_id );
			else : ?>
				<?php if (have_posts()) : ?>
					<div class="page-module">
						<div class="component">
						<div class="posts-listing has-columns" style="--d-gap:<?=LISTING_DESKTOP_GAP?>; --l-gap:<?=LISTING_LAPTOP_GAP?>; --t-gap:<?=LISTING_TABLET_GAP?>; --m-gap:<?=LISTING_MOBILE_GAP?>; --d-columns:<?=LISTING_DESKTOP_COLUMNS?>; --l-columns:<?=LISTING_LAPTOP_COLUMNS?>; --t-columns:<?=LISTING_TABLET_COLUMNS?>; --m-columns:<?=LISTING_MOBILE_COLUMNS?>;">
								<?php while (have_posts()) : the_post();	
									$queried_object = get_queried_object();
									get_template_part( 'inc/partials/minipost', $queried_object->name );
								endwhile; ?>
							</div>
						</div>
						<div class="component">
							<?php mv23_page_navi(); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>

		<?php if(ARCHIVE_SIDEBAR): ?>
        	<div class="sidebar">
				<div style="height:100%">
					<div class="pinned-block">
						<?php if (is_active_sidebar('page_sidebar')) : ?>
							<?php dynamic_sidebar('page_sidebar'); ?>
						<?php endif ?>
					</div>
				</div>
        	</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>