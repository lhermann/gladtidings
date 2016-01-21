<?php
/*------------------------------------*\
    Single Lesson Controller
\*------------------------------------*/

// require shared functions for lessons and quizzes
require( 'controller.shared.item.php' );


class GTView extends GTItem
{

	function __construct( &$object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// prepare flyout
		add_filter( 'container_class', function( $classes ){ $classes[] = 'flyout'; return $classes; } );

		// get siblings
		$this->siblings = $this->get_children( $this->unit );

		// on first touch: increment unit_{$ID}_lessons_done and recalculate progress
		if( $this->first_touch ) {
			$this->increment_items_done( 'unit', $this->unit->ID, 'lessons' );
			$this->update_value( 'unit', $this->unit->ID, 'progress', $this->calculate_progress( $this->unit ) );
		}

	}

	/*===========================*\
		Lesson Functiuons
	\*===========================*/

	public function get_description()
	{
		return get_field( 'description', $this->lesson );
	}

	public function print_attachment_link()
	{
		$attachment = get_field( 'attachment', $this->lesson );

		if( !$attachment ) return;

		printf( '<a class="btn btn--small btn--primary" href="%1$s" target="_blank" title="%2$s"><span class="fi fi-download"></span> %3$s</a>',
			$attachment['url'],
			__( 'Download', 'gladtidings' ) . ' ' . $attachment['filename'],
			__( 'Download Manuscript', 'gladtidings' ) . ' [' . gt_get_filesize( $attachment['url'] ) . ']'
		);
	}

}
