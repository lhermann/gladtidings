<?php 
	global $_gt;
	get_header(); 
	// var_dump($_gt);
?>

	<header id="page-header">
		
		<?php get_template_part( 'templates/navigation', 'unit' ); ?>

		<div class="page-hero page-hero--skinny shadow--receive t-header-image">
			<div class="wrapper">
			
				<?php get_template_part( 'templates/node', 'hero' ); ?>

			</div><!-- /.wrapper -->
		</div>

	</header>


	<main id="page-content">

		<div class="wrapper">

			<div class="layout layout--spacehack">
				<section class="layout__item u-2/3-lap-and-up">
					
					<div class="layout layout--auto">
						<h2 class="layout__item t-second-text"><?= __( 'Lessons', 'gladtidings' ); ?></h2>
						<?php $_gt->print_continue_btn() ?>
					</div>

					<?php
						//get all the units
						$items = $_gt->get_items();
						// var_dump( $items );
						
						if( $items ) {
					
							print( '<ul class="nodelist nodelist--unit">' );
					
							// loop through the items
							foreach ( $items as $key => $post ) {
								
								get_template_part( 'templates/node', get_post_type() );
								
							}
					
							print( '</ul><!-- /.nodelist -->' );
					
							// restore the original post
							wp_reset_postdata();
					
						} else {

							_e( 'No Lessons!' );

						}
					?>
					
				</section>
				<aside class="layout__item no-owl-lap-and-up u-1/3-lap-and-up">
					
					<div class="panel">
						<h2 class="t-second-text"><?= __( 'Progress', 'gladtidings' ) ?></h2>
						<p><strong class="b--shout t-main-text">103 min</strong> of video lessons in total.</p>
						<p>You completed 32 min and have <strong class="b--shout t-main-text">71 min</strong> left.</p>
						<p>You have completed <strong class="b--shout t-main-text">31%</strong> of this lesson.</p>
					</div>

				</aside>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
