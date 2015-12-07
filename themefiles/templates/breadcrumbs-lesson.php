<?php
global $meta, $course, $unit;

$badge_home =			sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );
$badge_course = 		sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', get_permalink( $course->post_id ), $course->name );
$badge_unit = 			sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', get_term_link( $unit, TAX_UNIT ), 'Unit '.$unit->unit_order );

print( $badge_home.' / '.$badge_course.' / '.$badge_unit.' / Video '.$meta['order_nr'][0] );