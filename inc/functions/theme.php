<?php
/* 
  - Clean <head> meta tag 
  - A better <title> meta tag
  - remove WP version from RSS
  - remove WP version from scripts
  - remove injected CSS for recent comments widget
  - remove injected CSS from recent comments widget
  - injected CSS from gallery

  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt
*/

/**
 * Clean <head> meta tag 
 *
 * @return void
 */
function mv23_head_cleanup() {
	// category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	// add_filter( 'style_loader_src', 'mv23_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	// add_filter( 'script_loader_src', 'mv23_remove_wp_ver_css_js', 9999 );
} 


/**
 * A better <title> meta tag 
 *
 * @return title
 */
function mv23_title_meta_tag( $title, $sep, $seplocation ) {
  	global $page, $paged;

  	// Don't affect in feeds.
  	if ( is_feed() ) return $title;

  	// Add the web's name
  	if ( 'right' == $seplocation ) {
    	$title .= get_bloginfo( 'name' );
  	} else {
    	$title = get_bloginfo( 'name' ) .' |'. $title;
  	}

  	// Add the web description for the home/front page.
  	$site_description = get_bloginfo( 'description', 'display' );

  	if ( $site_description && ( is_home() || is_front_page() ) ) {
    	$title .= " {$sep} {$site_description}";
  	}

  	// Add a page number if necessary:
  	if ( $paged >= 2 || $page >= 2 ) {
    	$title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  	}

  	return $title;
} 


/**
 * remove WP version from RSS
 *
 * @return empty
 */
function mv23_rss_version() { return ''; }


/**
 * remove WP version from scripts
 *
 * @return src
 */
function mv23_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}


/**
 * remove injected CSS for recent comments widget
 *
 * @return src
 */
function mv23_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}


/**
 * remove injected CSS from recent comments widget
 *
 * @return src
 */
function mv23_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}


/**
 * remove injected CSS from gallery
 *
 * @return src
 */
function mv23_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}



/*********************
THEME SUPPORT
*********************/

/**
 * Adding WP 3+ Functions & Theme Support
 *
 * @return void
 */
function mv23_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	// set_post_thumbnail_size(230, 230, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	// add_theme_support( 'post-formats',
	// 	array(
	// 		'aside',             // title less blurb
	// 		'gallery',           // gallery of images
	// 		'link',              // quick link to other site
	// 		'image',             // an image
	// 		'quote',             // a quick quote
	// 		'status',            // a Facebook like status update
	// 		'video',             // video
	// 		'audio',             // audio
	// 		'chat'               // chat transcript
	// 	)
	// );


	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );
}


if ( !function_exists('add_nav_support') ){
	function add_nav_support(){
		add_theme_support( 'menus' );
		register_nav_menus(
			array(
				'main-nav' => __( 'Main nav', 'default' ),
				'mobile-nav' => __( 'Mobile nav', 'default' )
			)
		);
	}
}

/*********************
PAGE NAVI
*********************/

/**
 * Numeric Page Navi (built into the theme by default)
 *
 * @return string
 */
function mv23_page_navi($query=null, $paged=null) {
	if ($query) {
		$wp_query = $query;
	}else{
  		global $wp_query;
	}

	$paged = ($paged) ? $paged : get_query_var('paged');

  	$bignum = 999999999;
  	if ( $wp_query->max_num_pages <= 1 ) return;
  	echo paginate_links( array(
    	'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    	'format'       => '?paged=%#%',
    	'current'      => max( 1, $paged ),
    	'total'        => $wp_query->max_num_pages,
    	'prev_text'    => '<<',
    	'next_text'    => '>>',
    	'type'         => 'list',
    	'end_size'     => 3,
    	'mid_size'     => 3
  	));
}

/*********************
RANDOM CLEANUP ITEMS
*********************/

/**
 * remove the p from around imgs 
 * http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images
 *
 * @return string
 */ 
function mv23_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

/**
 * This removes the annoying [�K] to a Read More link
 *
 * @return string
 */
function mv23_excerpt_more($more) {
	global $post;
	// edit here if you like
	// return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Leer ', 'mv23' ) . esc_attr( get_the_title( $post->ID ) ).'"><strong>'. __( 'Seguir Leyendo &raquo;', 'mv23' ) .'</strong></a>';
	return '';
}

/**
 * Remove wp-embed.min.js
  * https://wordpress.stackexchange.com/questions/211701/what-does-wp-embed-min-js-do-in-wordpress-4-4
 *
 * @return string
 */
function mv23_remove_wp_embed_script(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'mv23_remove_wp_embed_script' );



/**
 * Indents the output of
 * a function
 *
 * @return void
 */
function mv23_print_indented($fn, $num_tabs=1, $params=null){

  ob_start();
  call_user_func($fn, $params);
  $html = ob_get_contents();
  ob_end_clean();
  $tabs="";
  for ($i=0 ; $i<$num_tabs ; $i++) $tabs.="\t";
    echo preg_replace("/\n/", "\n" . $tabs, substr($html, 0, - 1));
  echo "\n";
}