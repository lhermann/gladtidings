<?php
global $meta, $unit;

$btn_home =			sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );
$btn_course = 		sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', get_permalink( $unit->course_object_id ), $unit->course_title );
$btn_unit = 		sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', get_term_link( $unit, TAX_UNIT ), 'Unit '.$unit->unit_order );

print( $btn_home.' / '.$btn_course.' / '.$btn_unit.' / Video '.$meta['order_nr'][0] );