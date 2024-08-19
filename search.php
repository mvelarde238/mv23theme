<?php get_header(); ?>

<div id="content">
	<?php get_template_part('partials/page-header');; ?>

	<div id="main-content" class="main-content  container main-content--sidebar-left">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php if (have_posts()) : ?>
				<div class="page-module">
					<div class="component">
						<div class="posts-listing">
							<?php while (have_posts()) : the_post();	
								get_template_part( 'inc/partials/minipost','searchresult');
							endwhile; ?>
						</div>
					</div>
					<div class="component">
						<?php mv23_page_navi(); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="page-module"><div class="component">
					<article id="post-not-found" class="hentry cf">
						<header class="article-header">
							<h3 class="center"><?php _e( 'No hubieron resultados', 'mv23' ); ?></h3>
						</header>
					</article>
				</div></div>
			<?php endif; ?>
			<?php get_template_part('inc/modulos/modals/controlador'); ?>
		</main>
		<div class="sidebar">
			<div style="height:100%">
				<div class="pinned-block">
					<?php if (is_active_sidebar('page_sidebar')) : ?>
						<?php dynamic_sidebar('page_sidebar'); ?>
					<?php endif ?>
				</div>
			</div>
        </div>
	</div>
</div>

<?php get_footer(); ?>