<?php
	global $_gt;
	// var_dump( $_gt );

	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

		<div class="page-hero shadow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--course">
					<div class="hero-frame__badge">
						<img src="<?= gt_get_course_batch( $post ) ?>" alt="<?= get_the_title().' '.__( 'Badge', 'gladtidings' ); ?>">
					</div>
					<h1 class="hero-frame__title"><?php the_title(); ?></h1>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content" role="main">


		<?php
		// Show content if user is logged in
		if( $_gt->user_can_study() ):
			?>
		
			<section id="progress" class="wrapper">

					<?php //get_template_part( 'templates/content', 'progress' ); ?>
					<h2 class="u-screen-reader-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>
					<div class="progress progress--meter" title="<?= __( 'Progress:', 'gladtidings' ).' '.$_gt->get_progress_width() ?>">
						<span class="progress__item t-comp-bg" style="width: <?= $_gt->get_progress_width() ?>"><?= $_gt->get_progress_width() ?></span>
					</div>
					<p class="u-spacing--narrow t-second-text"><?php $_gt->print_progress_message() ?></p>
			
			</section>

			<div class="wrapper">
				<div class="layout layout--spacehack">
					<section id="units" class="layout__item u-2/3-desk">
			
						<h2 class="t-second-text"><?= __( 'Units', 'gladtidings' ); ?></h2>
			
						<?php
							//get all the units
							$units = $_gt->get_units();
							// var_dump( $units );
			
							// check if the repeater field has rows of data
							if( $units ) {
			
								print( '<ul class="nodelist nodelist--course">' );
			
								// loop through the units
								foreach ( $units as $key => $post ) {
									
									get_template_part( 'templates/node', get_post_type() );
									
								}
			
								print( '</ul><!-- /.nodelist -->' );
			
								// restore the original post
								wp_reset_postdata();
			
							} else {

								_e( 'No Units!' );

							}
						?>
				
					</section>
					<aside class="layout__item no-owl-desk u-1/3-desk">
						
						<?php 
							$description = $_gt->get_description();
							if( $description ): ?>

							<h2 class="t-second-text"><?= __( 'Description', 'gladtidings' ) ?></h2>
							<p><?= $description ?></p>

						<?php endif; ?>

					</aside>
				</div>

				<?php
			// Show login prompt if user is not logged in
			else:
				?>
				
				<div class="wrapper u-text--center">
					<p>Please Log In to study</p>
					<?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
				</div>
				
				<?php
			endif;
			?>
		
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
