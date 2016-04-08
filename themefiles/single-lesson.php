<?php get_header(); ?>

	<div id="content-body" class="content-body wrapper wrapper--desk">

		<div class="layout layout--flush layout--rev layout--spacehack">

			<section class="layout__item u-3/4-desk u-3/4-lap">

				<?php get_template_part( 'templates/content', 'embed' ); ?>

				<?php get_template_part( 'templates/content', 'lesson' ); ?>

			</section>

			<aside class="layout__item u-1/4-lap-and-up u-flyout-palm" role="complementary">

				<?php get_template_part( 'templates/content', 'flyout' ); ?>

			</aside>

		</div><!-- /.layout -->

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
