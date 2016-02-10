<ul class="nodelist nodelist--lesson">

<?php
	//get all the units
	$siblings = $post->siblings();
	var_dump( $siblings );

	if( $siblings ) {

		// loop through the items
		foreach ( $siblings as $node ) {

			$type = $node->type == 'headline' ? 'divider' : $node->type;

			get_template_part( 'templates/node', $type );

		}

	} else {

		_e( 'No Lessons!' );

	}
?>

</ul>
