<?php
	// Get Fields
	global $fields;
	$fields = get_fields();

	// Built Inline Theme CSS Styles
	add_filter( 'theme_css', 'add_theme_color', 10 );

	// Default course batch
	$fields['img_course_badge'] = $fields['img_course_badge'] ? $fields['img_course_badge'] : get_template_directory_uri().'/img/course-batch-placeholder.png';

	get_header();
?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

		<div class="page-hero shaddow--receive t-header-image">
			<div class="wrapper">
				<div class="hero-frame hero-frame--course">
					<div class="hero-frame__badge">
						<img src="<?= $fields['img_course_badge'] ?>" alt="<?= get_the_title().' '.__( 'Batch', 'gladtidings' ); ?>">
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
		
					<h2 class="t-second-text"><?= __( 'Units', 'gladtidings' ); ?></h2>
		
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
		
						}
					?>
			
				</section>
				<aside class="layout__item u-1/3-desk">

					<div>
						<a href="lesson.html" class="btn btn--theme btn--full">Start Course</a>
					</div>

					<div class="panel t-light-bg t-main-border">
						<h5 class="t-second-text">Description</h5>
						<?= $fields['course_description'] ?>
					</div>

				</aside>
			</div>
		
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
