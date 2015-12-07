<?php
global $post;

$badge_home =		sprintf( '<a class="badge badge--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );
$badge_course = 	$post->post_title;

print( $badge_home.' / '.$badge_course );
?>