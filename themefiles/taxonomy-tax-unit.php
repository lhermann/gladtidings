<?php 
	global $unit;
	$unit = get_unit_meta( get_queried_object() );

	// var_dump($unit);

	// Built Inline Theme CSS Styles
	add_filter( 'theme_css', 'add_theme_color', 10 );

	get_header();
?>

	<header id="page-header">
		
		<?php get_template_part( 'templates/navigation', 'unit' ); ?>

		<div class="page-hero page-hero--skinny shadow--receive t-header-image">
			<div class="wrapper">
			
				<?php get_template_part( 'templates/nodelist', 'hero' ); ?>

			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">

			<div class="layout layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					
					<div class="layout layout--auto">
						<h2 class="layout__item t-second-text"><?= __( 'Lessons', 'gladtidings' ); ?></h2>
						<a class="layout__item u-pull--right btn btn--success" href="lesson.html"><?= __( 'Continue Lesson', 'gladtidings' ); ?></a>
					</div>

					<?php

						if ( have_posts() ) {

							// this function will manipulate the global $posts array directly
							sort_objects_inside_unit();

							echo '<ul class="nodelist nodelist--unit">';

							while ( have_posts() ) : the_post();

								get_template_part( 'templates/nodelist', 'unit' );

							endwhile;

							echo '</ul>';

						} else {

							_e( 'No Lessons!' );
							
						}
					?>
					
				</section>
				<aside class="layout__item no-owl-lap-and-up u-1/3-lap-and-up">
					
					<div class="panel">
						<h2 class="t-second-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>
						<p><strong class="b--shout t-main-text">103 min</strong> of video lessons in total.</p>
						<p>You completed 32 min and have <strong class="b--shout t-main-text">71 min</strong> left.</p>
						<p>You have completed <strong class="b--shout t-main-text">31%</strong> of this lesson.</p>
					</div>

				</aside>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
