<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

class Application
{
	public $ID, $type, $date, $date_gmt, $title, $status, $slug;

	function __construct( $post )
	{
		$this->ID        = $post->ID;
		$this->type      = strtolower( get_class ( $this ) );
		$this->date      = $post->post_date;
		$this->date_gmt  = $post->post_date_gmt;
		$this->title     = $post->post_title;
		$this->slug      = $post->post_name;
		$this->status    = $this->init_status( $post->post_status );

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

	/*=======================*\
		Public Functions
	\*=======================*/

	public function is_done()
	{
		return $this->status === 'passed' ? true : false;
	}

	public function progress()
	{
		global $user;
		return $user->get_progress( $this );
	}
}
