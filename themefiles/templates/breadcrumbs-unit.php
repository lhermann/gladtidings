<?php
global $unit;

$btn_home =		sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );
$btn_course = 	sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', get_permalink( $unit->course_object_id ), $unit->course_title );

print( $btn_home.' / '.$btn_course.' / Unit '.$unit->unit_order );
?>