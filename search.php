<?php 
use Core\Builder\Component\Archive_Title;

get_header(); 
?>

<div id="content">
	<section class="page-module">
		<div class="container">
			<div class="main-content main-content--sidebar-right">
				<main class="main components-wrapper">
					<?php echo Archive_Title::display( array() ); ?>
		
					<?php if (have_posts()) : ?>
						<div class="posts-listing">
							<?php while (have_posts()) : the_post();	
								get_template_part( 'partials/card/postcard','searchresult');
							endwhile; ?>
						</div>

						<?php get_template_part('partials/pagination'); ?>

					<?php else : ?>
						<article id="post-not-found" class="hentry cf">
							<header class="article-header">
								<h3 class="center"><?php _e( 'No results', 'mv23theme' ); ?></h3>
							</header>
						</article>
					<?php endif; ?>
				</main>
		
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>