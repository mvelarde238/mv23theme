<?php
/**
 * Contact form 7
 * custom tag: [posts show:12]
 * show parameter is optional
 */
add_action('wpcf7_init', 'custom_add_form_tag_posts');
 
function custom_add_form_tag_posts()
{
    wpcf7_add_form_tag('posts', 'custom_posts_form_tag_handler');
}
 
function custom_posts_form_tag_handler($tag)
{

    // Structure
   		$html = 'lel';
   // 		$cart_items = WC()->cart->get_cart();

   // 		if (count($cart_items)) {
   // 			$html .= '<ul>';
   // 			foreach( $cart_items as $cart_item ) {
			// 	$item_name = $cart_item['data']->get_title();
			// 	$html .= '<li>'.$item_name.'</li>';
			// }
   // 			$html .= '</ul>';
   // 		}
    
    return $html;
}

/**
 * When saving, change the array to a comma, separated list, just to make it easier 
 */
add_filter("wpcf7_posted_data", function ($posted_data) {
    //'posts' is the name that you gave the field in the CF7 admin.
    if (isset($posted_data['posts'])) {
        $posted_data['posts'] = implode(", ", $posted_data['posts']);
    }

    return $posted_data;
});

/**
 * A tag to be used in "Mail" section so the user receives the special tag
 * [posts]
 */
add_filter('wpcf7_special_mail_tags', 'wpcf7_tag_post', 10, 3);
function wpcf7_tag_post($output, $name, $html)
{
    $name = preg_replace('/^wpcf7\./', '_', $name); // for back-compat

    $submission = WPCF7_Submission::get_instance();

    if (! $submission) {
        return $output;
    }
    
    if ('posts' == $name) {
        return $submission->get_posted_data("posts");
    }

    return $output;
}