<?php
	// Get Fields
	global $fields;
	$fields = get_fields();

	// Built Inline Theme CSS Styles
	add_filter( 'theme_css', 'add_theme_color', 10 );

	// Default course batch
	$fields['img_course_badge'] = default_course_batch( $fields['img_course_badge'] );

	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

		<div class="page-hero shadow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--course">
					<div class="hero-frame__badge">
						<img src="<?= $fields['img_course_badge'] ?>" alt="<?= get_the_title().' '.__( 'Badge', 'gladtidings' ); ?>">
					</div>
					<h1 class="hero-frame__title"><?php the_title(); ?></h1>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content" role="main">
		
		<section id="progress" class="wrapper">

				<?php get_template_part( 'templates/content', 'progress' ); ?>
		
		</section>

		<div class="wrapper">
			<div class="layout layout--spacehack">
				<section id="units" class="layout__item u-2/3-desk">
		
					<div class="layout layout--auto">
						<h2 class="layout__item t-second-text"><?= __( 'Units', 'gladtidings' ); ?></h2>
						<a class="layout__item u-pull--right btn btn--success" href="lesson.html">Start or Continue Course</a>
					</div>
		
					<?php
						//get all the units
						$units = $fields['units_repeater'];
		
						// check if the repeater field has rows of data
						if( $units ) {
		
							print( '<ul class="nodelist nodelist--course">' );
		
							// llop through the units
							foreach ( $units as $key => $post ) {
		
								get_template_part( 'templates/nodelist', 'course' );
								
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
					
					<?php if( $fields['course_description'] ): ?>
						<h2 class="t-second-text"><?= __( 'Description', 'gladtidings' ) ?></h2>
						<?= $fields['course_description'] ?>
					<?php endif; ?>

				</aside>
			</div>
		
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
