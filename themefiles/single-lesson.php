<?php get_header(); ?>

<?php
//var_dump($userdata);
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
				
				</div>
			</div>

		</div><!-- /.wrapper -->

		<div class="wrapper">

			<?php //var_dump($post); ?>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
