<?php
global $post;

$btn_home =		sprintf( '<a class="btn btn--tiny" href="%s">%s</a>', home_url(), __( 'Home', 'gladtidings' ) );

print( $btn_home.' / '.$post->post_title );
?>