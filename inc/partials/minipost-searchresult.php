<?php
$id = get_the_ID();
$link = get_the_permalink($id);
?>
<div style="margin-bottom: 30px">
	<h5><a href="<?=$link?>"><?php the_title() ?></a></h5>
	<p><?php the_excerpt(); ?></p>
</div>