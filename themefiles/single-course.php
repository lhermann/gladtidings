<?php get_header(); ?>

<?php
//var_dump($userdata);
?>

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
			
			<?php get_template_part( 'templates/nodelist', 'course' ); ?>

			<hr>

			<div class="breadcrumb">
				<?php get_template_part( 'templates/breadcrumbs', 'course' ); ?>
			</div>

			<?php //var_dump($post); ?>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
