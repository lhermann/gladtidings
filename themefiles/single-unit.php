<?php
	global $_gt;

	get_header();
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
				<section class="layout__item">

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
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
