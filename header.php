<!doctype html>
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php wp_title(''); ?></title>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php do_action('body_id'); ?> <?php body_class(); ?> <?php do_action('body_attributes'); ?> >
		<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

		<a class="skip-link no-smooth-scroll" href="#content"><?php esc_html_e( 'Skip to content', 'mv23theme' ); ?></a>

		<div class="global-wrapper">
			<?php get_template_part('partials/header'); ?>