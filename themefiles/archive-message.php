<?php
	get_header();
	// global $user;
?>

	<div id="content-body" class="content-body wrapper">

		<div class="layout u-spacing--top">

			<div class="layout__item u-1/3 u-hidden-palm">

				<ul class="user-menu user-menu--touch panel owl--offall">

					<?php get_template_part( 'templates/user-menu' ); ?>

				</ul>

			</div><!--

			--><div class="layout__item u-2/3-lap-and-up">

				<h2>List of Messages</h2>

			</div>

		</div><!-- /.layout -->

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
