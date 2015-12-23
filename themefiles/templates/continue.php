<?php
global $unit;

$unit_ids = array();
foreach ($unit->lesson_order as $key => $item) {
	if( (int)end($item) > 0 ) {
		$unit_ids[] = (int)end($item);
	}
}

$next_lesson_id = $unit_ids[ array_search( $post->ID, $unit_ids ) + 1 ];
$next_link = esc_url( $next_lesson_id ? get_permalink( $next_lesson_id ) : get_term_link( $unit ).'?origin=continue' );

?>
<a class="btn btn--success btn--success" href="<?= $next_link ?>" title="<?= __( 'Continue to next Lesson', 'gladtidings' ); ?>"><?= __( 'Continue', 'gladtidings' ); ?> <i class="fi fi-arrow-right"></i></a>