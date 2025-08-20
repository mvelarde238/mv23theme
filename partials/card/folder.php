<?php
$term = $args['term'];
$link = get_term_link($term);

echo '<div><a href="' . esc_url($link) . '">Folder for: ' . esc_html($term->name) . '</a></div>';