<?php
	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'home' ); ?>

		<div class="page-hero shadow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--box owl--narrow">
					<h1 class="hero-frame__title shadow--strong-text">404</h1>
					<hr class="hero-frame__hr">
					<!-- <div class="ero-frame__hr u-header-color"></div> -->
					<p class="ero-frame__subtitle shadow--strong-text">Page Not Found</p>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede u-text--center">Seems like we were not able to find your page.</p>
					<p>Please do not forget to add some usefull information here, so people know what to do next.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
