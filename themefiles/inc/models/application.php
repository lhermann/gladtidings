<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

/**
 * Pass a post object or an ID
 */

class Application
{
	public $ID, $type, $date, $date_gmt, $title, $status, $slug;

	function __construct( $post )
	{
		$post = is_object( $post ) ? $post : get_post( $post );

		$this->ID         = (int)$post->ID;
		$this->type       = strtolower( get_class ( $this ) );
		$this->date       = $post->post_date;
		$this->date_gmt   = $post->post_date_gmt;
		$this->title      = $post->post_title;
		$this->slug       = $post->post_name;
		$this->status     = $this->init_status( $post->post_status );
		$this->status_num = $this->init_status_number( $this->status );

		return $post;
	}

	/*=======================*\
		Private Functions
	\*=======================*/



	/*=======================*\
		Protected Functions
	\*=======================*/

	/**
	 * Update the status of an object and return the updated object
	 * If the status is ...
	 *  - 'coming'  = date has passed ? set to 'publish' (also update post object and acf field)
	 *                note: change from 'coming' to 'publish' should only happen once
	 *  - 'locked'  = unlock condition has been met ? fall through to 'publish' : don't change
	 *  - 'publish' = user has started ? (active) or finished ? (success) the object : don't change
	 *
	 * Possible Status Flags
	 *  - 'success' - the unit is sucessfully finished (user specific)
	 *  - 'active'  - the unit was started, but is not finished (user specific)
	 *  - 'publish' - the unit is open, but not yet started (wp builtin)
	 *  - 'locked'  - the unit is visible, but not accessible
	 *  - 'coming'  - the unit is anounced for a future date, but visible (other than builtin 'future')
	 *  - 'draft'   - the unit is not visible (wp builtin)
	 */
	protected function init_status( $status )
	{
		global $user;

		switch ( $status ) {
			case 'coming':
				$date = strtotime( get_post_meta( $this, 'release_date', true ) );
				if( $date < time() ) {
					// update status to 'publish' and fall through
					$status = 'publish';
					wp_publish_post( $this->ID );
				} else {
					$this->release_date = date( "F j, Y", $date );
					break;
				}

			case 'publish':
				$progress = $user->get_progress( $this );
				if( $progress == 100 ) {
					$status = 'success';
				} elseif( $progress > 0 ) {
					$status = 'active';
					$this->progress = $progress;
				}
		}
		return $status;
	}

	/**
	 * Returns the status in an integer
	 */
	protected function init_status_number( $status )
	{
		$number = 0;
		switch ( $status ) {
			case 'success': $number++; // 5
			case 'active':  $number++; // 4
			case 'publish': $number++; // 3
			case 'locked':  $number++; // 2
			case 'coming':  $number++; // 1
			case 'draft':              // 0
			default:                   // 0
		}
		return $number;
	}

	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 *
	 */
	public function is_done()
	{
		return $this->status === 'passed' ? true : false;
	}

	/**
	 * The Progress as an integer from 0 to 100
	 */
	public function progress()
	{
		global $user;
		return $user->get_progress( $this );
	}
}
