<ul class="socialshare">
	<li><button data-sharer="facebook" data-hashtag="Wokandbox" data-url="<?php the_permalink(); ?>"><span class="fa fa-facebook"></span></button></li>
	<?php if (wp_is_mobile()) { ?>
		<li><button data-sharer="whatsapp" data-title="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>"><span class="fa fa-whatsapp"></span></li>
	<?php } else { ?>
		<li><button data-sharer="whatsapp" data-web data-title="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>"><span class="fa fa-whatsapp"></span></li>
	<?php } ?>
	<li><button data-sharer="twitter" data-title="<?php the_title(); ?>" data-hashtags="mv23" data-url="<?php the_permalink(); ?>"><span class="fa fa-twitter"></span></button></li>
</ul>