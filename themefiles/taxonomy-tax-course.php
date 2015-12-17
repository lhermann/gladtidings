<?php get_header(); ?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero t-header-image">

		<div class="wrapper">
			<div class="page-hero__frame">
				<h1 class="page-hero__title">taxonomy-tax-ourse.php</h1>
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">

		<div class="wrapper">

			<?php
				if ( have_posts() ) :

					echo '<ul class="nodelist">';

					while ( have_posts() ) : the_post();

						get_template_part( 'templates/nodelist', 'course' );

					endwhile;

					echo '</ul>';

				else :
					_e( 'Sorry, no posts matched your criteria.' );
				endif;
			?>

			<?php var_dump($wp_query); ?>

		</div><!-- /.wrapper -->

		<div class="wrapper">

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
