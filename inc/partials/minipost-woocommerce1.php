<?php
$id = get_the_ID();
echo do_shortcode('[products ids="'.$id.'" columns="1"]');