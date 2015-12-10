<?php
global $post, $meta, $unit;

// create $crumbs array
global $crumbs;
$crumbs = array(
			array( 
				'url' => home_url(), 
				'text' => __( 'Home', 'gladtidings' ) 
			),
			array( 
				'url' => ( $unit ? get_permalink( $unit->course_object_id ) : get_permalink() ), 
				'text' => ( $unit ? $unit->course_title : $post->post_title )
			),
	$unit ? array( 
				'url' => get_term_link( $unit, TAX_UNIT ), 
				'text' => __('Unit','gladtidings').' '.$unit->unit_order 
			) 
			: false,
	$meta ? array( 
				'url' => get_permalink(), 
				'text' => __('Lesson','gladtidings').' '.$meta['order_nr'][0] 
			) 
			: false
);

// Function for Array Walker
function print_crumbs( $crumb, $i ) {
	global $crumbs;
	if( !$crumb ) return;
	$classes = ( !isset($crumbs[$i+1]) || $crumbs[$i+1] == false ? 'crumb__item crumb__item--current' : 'crumb__item' );
	print( 
		( $crumb['url'] ? '<a class="'.$classes.'" href="'.$crumb['url'].'">' : '<span class="'.$classes.'">' ).
		spaces_to_nbsp( $crumb['text'] ).
		( $crumb['url'] ? '</a> ' : '</span>' )
	); 
}

// print breadcrump list
?>
<nav class="breadcrumbs">
	<?php array_walk( $crumbs, 'print_crumbs' ); ?>
</nav>