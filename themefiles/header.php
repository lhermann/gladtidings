<?php
/**
 * Template Header
 */
?>
<!doctype html>
<html class="<?php html_class(); ?>" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title( '&laquo;', true, 'right' ); bloginfo('name'); ?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--<script src="js/modernizr.js"></script>-->
	<?php wp_head(); ?>
	<?php theme_css(); ?>
</head>
<body <?php body_class(); ?>>

	<?php get_template_part( 'templates/navigation' ); ?>

	<div id="container" class="container <?php container_class(); ?>">

		<main id="page-content" class="page-content" role="main">
