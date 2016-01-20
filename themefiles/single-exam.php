<?php 
	global $_gt;
	var_dump( $_gt );
	
	get_header();
?>

	<header id="page-header" class="shadow--drop">
		
		<?php get_template_part( 'templates/navigation', 'quizz' ); ?>

	</header>

	<div class="wrapper wrapper--desk t-margin-reset--top">
		<div id="page-content" class="layout layout--flush layout--rev layout--spacehack">
			<main class="layout__item u-3/4-desk u-3/4-lap" role="main">
			
				<div class="wrapper">
					
					<?php //get_template_part( 'templates/content', 'quizz' ); ?>

				</div>
			
			</main>
			<aside class="layout__item u-1/4-lap-and-up u-spacing--off u-flyout-palm" role="complementary">
				
				<div class="wrapper u-spacing--top">

					<h2 class="u-text--1rem">
						<span class="label label--small label--theme"><?= __( 'Course', 'gladtidings' ) ?></span>
						<?= $_gt->print_link_to( $_gt->get_object( 'course' ), array( 'title' => __('Course Overview', 'gladtidings') ) ) ?>
					</h2>
					<nav role="navigation">

						<?php //get_template_part( 'templates/nodelist', 'item' ); ?>

					</nav>
		
				</div><!-- /.wrapper -->
		
			</aside>
		</div><!-- /#page-content /.layout -->
	</div>

<?php get_footer(); ?>
