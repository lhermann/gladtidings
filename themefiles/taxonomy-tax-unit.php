<?php 
	get_header();

	global $unit;
	$unit = get_unit_meta( get_queried_object() );
?>

	<header id="page-header">
		
		<?php get_template_part( 'templates/navigation', 'unit' ); ?>

		<div class="page-hero page-hero--skinny shaddow--receive u-header-image u-header-color">
			<div class="wrapper">
			
				<?php get_template_part( 'templates/nodelist', 'hero' ); ?>

			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">

			<?php

				if ( have_posts() ) :

					// this function will manipulate the global $posts array directly
					sort_objects_inside_unit();

					echo '<ul class="nodelist nodelist--unit">';

					while ( have_posts() ) : the_post();

						get_template_part( 'templates/nodelist', 'unit' );

					endwhile;

					echo '</ul>';

				else :
					_e( 'No Units!' );
				endif;
			?>

			<?php //var_dump($wp_query); ?>

		</div><!-- /.wrapper -->

		<div class="wrapper">
			<hr>
			<div class="breadcrumb">
				<?php get_template_part( 'templates/breadcrumbs', 'unit' ); ?>
			</div>
		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
