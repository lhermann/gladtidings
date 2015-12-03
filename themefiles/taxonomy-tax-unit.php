<?php get_header(); ?>

	<header id="page-header">

		<div class="page-hero page-hero--skinny u-header-image u-header-color">
			<div class="wrapper">
			
				<?php get_template_part( 'partials/nodelist', 'hero' ); ?>

			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">
			<p>taxonomy-tax-unit.php</p>
			<?php get_template_part( 'partials/breadcrumbs', 'unit' ); ?>
		</div><!-- /.wrapper -->

		<div class="wrapper">

			<?php

				if ( have_posts() ) :

					// this function will manipulate the global $posts array directly
					sort_objects_inside_unit();

					echo '<ul class="nodelist nodelist--unit">';

					while ( have_posts() ) : the_post();

						get_template_part( 'partials/nodelist', 'unit' );

					endwhile;

					echo '</ul>';

				else :
					_e( 'Sorry, no posts matched your criteria.' );
				endif;
			?>

			<?php //var_dump($wp_query); ?>

		</div><!-- /.wrapper -->

		<div class="wrapper">

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
