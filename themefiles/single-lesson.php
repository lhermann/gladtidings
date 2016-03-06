<?php
	// prepare flyout
	add_filter( 'container_class', function( $classes ){
		return $classes + array( 'flyout', 'flyout--left' );
	});

	get_header();
?>

	<header id="page-header" class="shadow--drop">

		<?php get_template_part( 'templates/navigation', 'lesson' ); ?>

	</header>

	<div id="content" class="wrapper wrapper--desk no-owl">
		<div class="layout layout--flush layout--rev layout--spacehack">
			<main class="layout__item u-3/4-desk u-3/4-lap" role="main">


				<div class="wrapper wrapper--paddingless">

					<?php get_template_part( 'templates/content', 'embed' ); ?>

				</div><!-- /.wrapper -->
				<div class="wrapper">

					<?php get_template_part( 'templates/content', 'lesson' ); ?>

				</div>


			</main>
			<aside class="layout__item u-1/4-lap-and-up u-spacing--off u-flyout-palm" role="complementary">

				<?php get_template_part( 'templates/content', 'flyout' ); ?>

			</aside>
		</div><!-- /#page-content /.layout -->
	</div>

<?php get_footer(); ?>
