<?php
global $unit;

$unit->unit_order = 	get_term_meta( $unit->term_id, 'unit_order', true ) + 1;

$badge_home =		sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );
$badge_course = 	sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', get_permalink( $unit->course_object_id ), $unit->course_title );
// $badge_unit =		sprintf( '<span>Unit %s: %s</span>', $unit->unit_order, $unit->name );
// $badge_unit =		sprintf( '<span class="badge badge--tiny">Unit %s: %s</span>', $unit->unit_order, $unit->name );
$badge_unit =		'Unit '.$unit->unit_order;

print( $badge_home.' / '.$badge_course.' / '.$badge_unit );
?>