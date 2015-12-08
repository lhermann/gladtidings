<?php 
	get_header();

	global $meta, $unit;

	// get variables
	$meta 	= get_post_meta( $post->ID, '', true );
	$unit 	= get_unit( $post->ID );
?>

	<header id="page-header" class="shaddow--drop">
		
		<?php get_template_part( 'templates/navigation', 'lesson' ); ?>

	</header>

	<main id="page-content" class="t-margin-reset--top">
		
		<div class="wrapper wrapper--paddingless">

			<div class="layout layout--center">
				<div class="layout__item u-2/3-lap-and-up">

					<?php get_template_part( 'templates/content', 'embed' ); ?>

				</div>
			</div>

		</div><!-- /.wrapper -->

		<div class="wrapper">
			
			<div class="layout layout--center">	
				<div class="layout__item u-2/3-lap-and-up">

					<?php get_template_part( 'templates/content', 'lesson' ); ?>

					<hr>

					<div class="breadcrumb flex">
						<div class="flex__item">
							<?php get_template_part( 'templates/breadcrumbs', 'lesson' ); ?>
						</div>
						<div class="flex__item">
							<a class="btn btn--success" href="#" title="View Next Lesson">Continue <i class="fi fi-arrow-right"></i></a>
						</div>
					</div>
				
				</div>
			</div>

		</div><!-- /.wrapper -->

		<div class="wrapper">

			<?php //var_dump($post); ?>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
