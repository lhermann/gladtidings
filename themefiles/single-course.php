<?php
	// Get Fields
	global $fields;
	$fields = get_fields();

	// Built Inline CSS Styles
	add_filter( 'theme_css', function( $css ){ 
		global $fields;
		$css['t-header-image']['background-image'] = $fields['img_course_header'] ? 'url('.$fields['img_course_header'].')' : 'url('.get_template_directory_uri().'/img/course-header-placeholder.jpg'.')';
		$css['t-main-text'] 		= array( 'color' => $fields['color_main'] );
		$css['t-main-border'] 		= array( 'border-color' => $fields['color_main'] );
		$css['t-main-bg'] 			= array( 'background-color' => $fields['color_main'] );
		$css['t-second-text'] 		= array( 'color' => $fields['color_secondary'] );
		$css['t-second-border']		= array( 'border-color' => $fields['color_secondary'] );
		$css['t-second-bg'] 		= array( 'background-color' => $fields['color_secondary'] );
		$css['t-comp-text'] 		= array( 'color' => $fields['color_comp'] );
		$css['t-comp-border'] 		= array( 'border-color' => $fields['color_comp'] );
		$css['t-comp-bg'] 			= array( 'background-color' => $fields['color_comp'] );
		$css['btn--theme'] 			= array( 'background-color' => $fields['color_main'],
											 'border-color' => adjust_color( $fields['color_main'], 40 ) );
		return $css;
	} );

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


	<main id="page-content">

		<div class="wrapper">
			<div class="layout layout--center">
				<div class="layout__item u-3/4-lap-and-up t-text--center lede t-second-text">
					<?= $fields['course_description'] ?>
				</div>
			</div>
		</div>

		<div class="wrapper">
			<div class="layout layout--center layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">

					<h2><?= __( 'Units', 'gladtidings' ); ?></h2>

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
				<aside class="layout__item u-1/3-lap-and-up">

					<div class="whitespace whitespace--h2"></div>
					<div class="panel">
						<h4 class="text-center">Progress</h4>
						<div class="progress">
							<span class="progress__meter" style="width: 24%">24%</span>
						</div>
						<p><a href="#">Log In</a> or <a href="#">Sign Up</a> to save your progress.</p>
					</div>
					<div class="panel">
						<p>This course consists of <strong>45 Vidos</strong> and <strong>6 Quizzes</strong></p>
					</div>
					<div class="panel">
						<p>These latin phrases are just placeholder text.</p>
						<p>Eius incidunt nostrum vero temporibus, consectetur ea est provident fugit.</p>
						<p>Recusandae nemo accusantium, reiciendis id voluptatum debitis cupiditate, distinctio ipsum.</p>
					</div>

				</aside>
			</div>

			<hr>

			<div class="breadcrumb">
				<?php get_template_part( 'templates/breadcrumbs', 'course' ); ?>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
