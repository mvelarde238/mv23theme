<?php get_header(); ?>

<div id="content">
	<div class="container cf">
		<main class="main">
			<article <?php post_class( 'cf main-content' ); ?>>
				<section class="center" style="padding: 100px 0; margin: auto">
					<h2>ERROR<strong>404</strong></h2>
					<p><?php _e( 'La página que estás buscando no existe.', 'mv23' ); ?></p>
					<p><a class="btn" href="<?php echo home_url(); ?>"><?php _e( 'Volver al Inicio', 'mv23' ); ?></a></p>
				</section>
			</article>
		</main>
	</div>
</div>

<?php get_footer(); ?>