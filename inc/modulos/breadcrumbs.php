<section class="breadcrumbs">
	<div class="container">
	<p class="truncate">
		<?php if ( is_front_page() ): ?>
			<a href="<?php echo home_url(); ?>">Home</a>
		<?php elseif (is_singular( 'abogados' )): ?>
			<a href="<?php echo home_url('abogados'); ?>">Abogados</a> <span class="sep">\</span> <?php the_title( '<b>', '</b>'); ?>
		<?php elseif (is_singular( 'post' )): ?>
			<a href="<?php echo home_url(); ?>">Home</a> <span class="sep">\</span> <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><?php echo get_the_title( get_option( 'page_for_posts' ) ) ?></a> <span class="sep">\</span> <?php the_title( '<b>', '</b>');; ?>
		<?php elseif (is_archive()): ?>
			<a href="<?php echo home_url(); ?>">Home</a> <span class="sep">\</span> <b>Archive</b>
		<?php elseif (is_home()): ?>
			<a href="<?php echo home_url(); ?>">Home</a> <span class="sep">\</span> <b><?php echo get_the_title( get_option( 'page_for_posts' ) ) ?></b>
		<?php else: ?>
			<a href="<?php echo home_url(); ?>">Home</a> <span class="sep">\</span> <?php the_title( '<b>', '</b>'); ?>
		<?php endif ?>
	</p>	
	</div>
</section>