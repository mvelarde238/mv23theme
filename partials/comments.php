<?php if ( comments_open() || get_comments_number() ) : ?>
	<div class="page-module pdt0">
        <div class="component">
		    <?php comments_template(); ?>
	    </div>
    </div>
<?php endif; ?>