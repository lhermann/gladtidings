<?php
	get_header();
	// var_dump( $post );
?>

	<header id="content-header" class="content-header page-hero shadow--receive t-header-image">

			<div class="wrapper">

				<?php get_template_part( 'templates/node', 'hero' ); ?>

			</div><!-- /.wrapper -->

	</header>


	<div id="content-body" class="content-body wrapper">

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

	</div><!-- /#content-body /.wrapper -->

<?php get_footer(); ?>
