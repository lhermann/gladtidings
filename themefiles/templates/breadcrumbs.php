<?php
/**
 * Outputs the breadcrumbs
 *
 * Possible implementtions:
 * 
 * 	<nav class="breadcrumbs breadcrumbs--full">
 *			<?php get_template_part( 'templates/breadcrumbs' ); ?>
 *	</nav>
 *
 * 	<nav class="breadcrumbs breadcrumbs--inline panel">
 *			<?php get_template_part( 'templates/breadcrumbs' ); ?>
 *	</nav>
 */



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
);
if( $unit ) $crumbs[] = array(
		'url' => get_term_link( $unit, TAX_UNIT ), 
		'text' => __('Unit','gladtidings').' '.$unit->unit_order 
	);
if( !is_tax() && $post->post_type == 'lesson' ) $crumbs[] = array(
		'url' => get_permalink(), 
		'text' => __('Lesson','gladtidings').' '.$meta['order_nr'][0] 
	);
if( !is_tax() && $post->post_type == 'quizz' ) $crumbs[] = array(
		'url' => get_permalink(), 
		'text' => __('Quizz','gladtidings')
	);

// Function for Array Walker
if( !function_exists('print_crumbs') ) {
	function print_crumbs( $crumb, $i ) {
		global $crumbs;
		if( !$crumb ) return;
		$classes = !isset($crumbs[$i+1]) ? 'crumb__item crumb__item--current' : 'crumb__item';
		print( 
			( $crumb['url'] ? '<a class="'.$classes.'" href="'.$crumb['url'].'">' : '<span class="'.$classes.'">' ).
			spaces_to_nbsp( $crumb['text'] ).
			( $crumb['url'] ? '</a> ' : '</span>' )
		); 
	}
}

// print breadcrump list
array_walk( $crumbs, 'print_crumbs' );