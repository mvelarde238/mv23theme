			<?php $footer_modules = get_option( 'footer_modules' ); ?>

			<?php if ($footer_modules): ?>
				<footer id="footer" class="footer">
					<div class="footer__content container">
						<?php echo ultimate_fields_page_content(); ?>
					</div>
					<a class="subir-btn" href="#content" data-state="hidden"><i class="fa fa-angle-up"></i></a>
				</footer>
			<?php endif ?>
		</div> <!--fin .global-wrapper-->
		
		<?php get_template_part('inc/modulos/menu/menu-movil'); ?>
		<?php do_action( 'footer_code' ); ?>
		<?php wp_footer(); ?>
		<?php $footer_scripts = get_option( 'footer_scripts' ); ?>
		<?php if ($footer_scripts) echo $footer_scripts; ?>
	</body>
</html>