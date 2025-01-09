<?php get_header(); ?>

<div id="content">
	<?php get_template_part('partials/page-header'); ?>
	<div id="main-content" class="container">
		<main class="main">
			<article <?php post_class( 'main-content' ); ?>>
				<section class="center" style="padding: 100px 0; margin: auto">
					<h2><?php _e( "404 ERROR. Sorry, this page isn't available", 'mv23theme' ); ?></h2>
					<p><?php _e( "The link you followed may be broken, or the page may have been removed.", 'mv23theme' ); ?></p>
					<p><a class="btn btn--main-color" href="<?php echo home_url(); ?>"><?php _e( 'Go back to Home', 'mv23theme' ); ?></a></p>
				</section>
			</article>
		</main>
	</div>
</div>

<?php get_footer(); ?>