<?php
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
						<?= h_continue_btn( $post ) ?>
					</div>

					<?php
						//get all the units
						$children = $post->children();

						// check if the repeater field has rows of data
						if( $children ) {

							print( '<ul class="nodelist nodelist--unit">' );

							// loop through the children
							foreach ( $children as $node ) {

								get_template_part( 'templates/node', $node->type );

							}

							print( '</ul><!-- /.nodelist -->' );

						} else {

							_e( 'No Lessons!' );

						}
					?>

				</section>
			</div>

		</div><!-- /.wrapper -->

	</main>

<?php get_footer(); ?>
