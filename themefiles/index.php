<?php
	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'home' ); ?>

		<div class="page-hero shadow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--box owl--narrow">
					<h1 class="hero-frame__title shadow--strong-text">The Glad Tidings</h1>
					<hr class="hero-frame__hr">
					<!-- <div class="ero-frame__hr u-header-color"></div> -->
					<p class="ero-frame__subtitle shadow--strong-text">What the Prophets of old want you to know</p>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">
					<p class="lede u-text--center">A short description of the course so people know what this website is about. This is a course about the ancient writings of the prophets and applicability today.</p>
				</div>
			</div>
		</div><!-- /.wrapper -->

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">

					<?php if ( have_posts() ): ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<div class="layout__item u-1/2-lap u-1/3-desk no-owl">
								<?php get_template_part( 'templates/teaser', 'course' ); ?>
							</div>

						<?php endwhile; ?>
					<?php else : ?>

						<p>Sorry, no posts matched your criteria.</p>

					<?php endif;?>

			</div>
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
