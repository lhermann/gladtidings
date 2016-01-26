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



global $_gt;
return;
// var_dump($_gt->get_breadcrumbs());
?>


<nav class="breadcrumbs breadcrumbs--full t-light-bg t-main-border">
	<ul class="wrapper list-inline owl--off">

		<?php foreach( $_gt->get_breadcrumbs() as $crumb ): ?>

			<li class="crumb__item">

				<?php if( is_object( $crumb ) ): ?>

					<?php $_gt->print_crumb_link( $crumb ); ?>

				<?php else: ?>

					<a class="a--bodycolor" href="<?= home_url() ?>" title="<?= __( 'Home', 'gladtidings' ) ?>"><?= __( 'Home', 'gladtidings' ) ?></a>

				<?php endif; ?>

			</li>

		<?php endforeach; ?>

	</ul>
</nav>
















<?php
return;

// create $crumbs array
$crumbs = array(
	array(
		'url' => home_url(),
		'text' => __( 'Home', 'gladtidings' )
	),
	// array(
	// 	'url' => ( $unit ? get_permalink( $unit->course_object_id ) : get_permalink() ),
	// 	'text' => ( $unit ? $unit->course_title : $post->post_title )
	// ),
);
// if( $unit ) $crumbs[] = array(
// 		'url' => get_term_link( $unit, TAX_UNIT ),
// 		'text' => __('Unit','gladtidings').' '.$unit->unit_order
// 	);
// if( !is_tax() && $post->post_type == 'lesson' ) $crumbs[] = array(
// 		'url' => get_permalink(),
// 		'text' => __('Lesson','gladtidings').' '.$meta['order_nr'][0]
// 	);
// if( !is_tax() && $post->post_type == 'quizz' ) $crumbs[] = array(
// 		'url' => get_permalink(),
// 		'text' => __('Quizz','gladtidings')
// 	);

// Function for Array Walker
if( !function_exists('print_crumbs') ) {

	function print_crumbs( $crumb, $i ) {

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
