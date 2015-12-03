<?php get_header(); ?>

	<header id="page-header" class="page-hero u-header-image u-header-color">

		<div class="wrapper">
			<div class="page-hero__frame">
				<h1 class="page-hero__title">taxonomy-tax-unit.php</h1>
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">

		<div class="wrapper">

			<?php

				if ( have_posts() ) :

					// this function will manipulate the global $posts array directly
					sort_objects_inside_unit();

					echo '<ul class="nodelist nodelist--normal">';

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
