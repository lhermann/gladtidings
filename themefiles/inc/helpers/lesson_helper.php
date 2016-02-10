<?php
/*------------------------------------*\
    Lesson Helpers
\*------------------------------------*/

include_once( 'continue_button_helper.php' );

function h_attachment_link( $lesson )
{
	$attachment = get_field( 'attachment', $lesson );

	if( !$attachment ) return;

	return sprintf( '<a class="btn btn--small btn--primary" href="%1$s" target="_blank" title="%2$s"><span class="fi fi-download"></span> %3$s</a>',
		$attachment['url'],
		__( 'Download', 'gladtidings' ) . ' ' . $attachment['filename'],
		__( 'Download Manuscript', 'gladtidings' ) . ' [' . gt_get_filesize( $attachment['url'] ) . ']'
	);
}
