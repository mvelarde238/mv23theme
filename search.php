<?php get_header(); ?>

<div id="content">
	<?php get_template_part('inc/modulos/page-header'); ?>

	<div class="main-content container main-content--sidebar-left">
		<main class="main" itemtype="http://schema.org/Blog">
			<?php if (have_posts()) : ?>
				<div class="page-module"><div class="componente">
					<div class="posts-listing">
						<?php while (have_posts()) : the_post();	
							get_template_part( 'inc/partials/minipost','searchresult');
						endwhile; ?>
					</div>
				</div></div>
				<?php mv23_page_navi(); ?>
			<?php else : ?>
				<div class="page-module"><div class="componente">
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
			<div class="page-module">
				<div class="componente">
					<?php 
					$searchkey = $_GET['s'];
					echo '<h4>Resultados de búsqueda para: '.$searchkey.'</h4>';
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>