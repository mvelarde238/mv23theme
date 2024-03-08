<div id="minicart-sidenav" class="minicart-sidenav side-nav">
    <?php if (is_active_sidebar( 'minicart_sidebar' )): ?>
		<div class="minicart-sidenav__widgets">
			<?php dynamic_sidebar( 'minicart_sidebar' ); ?>
		</div>
	<?php endif ?>
	<a href="#" class="close-sidenav"></a>
</div>