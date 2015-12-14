<?php get_header(); ?>

	<header id="page-header">

		<?php get_template_part( 'templates/navigation', 'course' ); ?>

		<div class="page-hero shaddow--receive u-header-image u-header-color">
			<div class="wrapper">
				<div class="page-hero__frame">
					<h1 class="page-hero__title"><?php the_title(); ?></h1>
				</div>
			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">
			
			<?php
				//get all the units
				$units = get_field( 'units_repeater' );

				// check if the repeater field has rows of data
				if( $units ) {

					print( '<ul class="nodelist nodelist--course">' );

					foreach ( $units as $key => $post ) {

						get_template_part( 'templates/nodelist', 'course' );
						
					}

					print( '</ul><!-- /.nodelist -->' );

					// restore the original post
					wp_reset_postdata();

				}
			?>

			<hr>

			<div class="breadcrumb">
				<?php get_template_part( 'templates/breadcrumbs', 'course' ); ?>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
