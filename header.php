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
		<?php $head_scripts = get_option( 'head_scripts' ); ?>
  		<?php if ($head_scripts) echo $head_scripts; ?>
	</head>
	<body <?php body_class(); ?> <?php do_action('body_style'); ?>>
		<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>
		<div class="global-wrapper">
			<?php get_template_part('partials/header'); ?>