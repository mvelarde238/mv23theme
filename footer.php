
			<?php get_template_part('partials/page-footer'); ?>
			<?php get_template_part('partials/modals/controlador'); ?>

		</div> <!--.global-wrapper-->
		
		<?php do_action( 'footer_code' ); ?>
		<?php wp_footer(); ?>
		<?php $footer_scripts = get_option( 'footer_scripts' ); ?>
		<?php if ($footer_scripts) echo $footer_scripts; ?>
	</body>
</html>