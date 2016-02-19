<?php
	get_header();
?>

	<header id="page-header" class="shadow--drop">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

	</header>

	<main id="page-content" role="main">

		<p class="lede u-text--center">Here go nice Wrap-Up information</p>

		<?php var_dump( $post ); ?>

	</main>

<?php get_footer(); ?>
