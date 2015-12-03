<?php get_header(); ?>

<?php
//var_dump($userdata);
?>

	<header id="page-header" class="page-hero u-header-image u-header-color">

		<div class="wrapper">
			<div class="page-hero__frame">
				<h1 class="page-hero__title">single-course.php</h1>
			</div>
		</div><!-- /.wrapper -->

	</header>


	<main id="page-content">

		<div class="wrapper">
			
			<?php get_template_part( 'partials/nodelist', 'course' ); ?>

			<?php //var_dump($post); ?>

		</div><!-- /.wrapper -->

		<div class="wrapper">

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
