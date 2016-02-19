<?php
	get_header();
	global $user;
?>

	<header id="page-header" class="shadow--drop">

		<?php get_template_part( 'templates/navigation', 'user' ); ?>

	</header>

	<main id="page-content" role="main">

		<p class="lede u-text--center">User Dashboard goes here</p>

		<?php var_dump( $user ); ?>

	</main>

<?php get_footer(); ?>
