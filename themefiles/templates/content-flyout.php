<div class="wrapper u-spacing--top">

	<h2 class="u-text--1rem">
		<span class="label label--small label--theme"><?= __( 'Unit', 'gladtidings' ).' '.$post->parent()->order ?></span>
		<?= $post->parent()->link_to() ?>
	</h2>
	<nav role="navigation">

		<ul class="nodelist nodelist--lesson">

			<?php
				//get all the units
				$siblings = $post->siblings();

				if( $siblings ) {

					// loop through the items
					global $node;
					foreach ( $siblings as $node ) {

						$type = $node->type == 'headline' ? 'divider' : $node->type;

						get_template_part( 'templates/node', $type );

					}

				} else {

					_e( 'No Elements!' );

				}
			?>

		</ul>

	</nav>

</div><!-- /.wrapper -->
