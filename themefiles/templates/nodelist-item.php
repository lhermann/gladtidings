<?php
global $_gt;
?>
<ul class="nodelist nodelist--lesson">

<?php
	//get all the units
	$items = $_gt->get_siblings();
	// var_dump( $items );
	
	if( $items ) {

		// loop through the items
		foreach ( $items as $key => $post ) {
			
			$type = $post->post_type == 'headline' ? 'divider' : $post->post_type;

			get_template_part( 'templates/node', $type );
			
		}

		// restore the original post
		wp_reset_postdata();

	} else {

		_e( 'No Lessons!' );

	}
?>

</ul>
