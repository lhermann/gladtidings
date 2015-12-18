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

		<div class="page-hero page-hero--skinny shaddow--receive t-header-image">
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

	</main>

<?php get_footer(); ?>
