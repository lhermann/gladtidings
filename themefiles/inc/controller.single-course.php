<?php
/*------------------------------------*\
    Single Course Controller
\*------------------------------------*/

/**
 * 
 */
class GTSingleController extends GladTidingsMasterController
{

	function __construct( $object )
	{

		// call parent __contruct
		parent::__construct( $object );

		$existed = $this->touch( 'course', $this->course->ID );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );

		// get fields
		// $this->fields = get_fields( $post->ID );
		// var_dump( $this->fields );
	}

	public function get_units()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gt_relationships";

		$query = "	SELECT *
					FROM $wpdb->posts p
					INNER JOIN $table_name r
					ON r.child_id = p.ID
					WHERE r.parent_ID = {$this->course->ID};
				 ";
		$units = $wpdb->get_results( $query, OBJECT );
		return $units;
	}


	// Get total number of lessons|quizzes
	// public function course_lessons_total() { return $this->get_num_items_total( 'course', 'lessons' ); }
	// public function course_quizzes_total() { return $this->get_num_items_total( 'course', 'quizzes' ); }

	// Get number of completed lessons|quizzes
	// public function course_lessons_done() { return $this->get_num_items_done( 'course', 'lessons' ); }
	// public function course_quizzes_done() { return $this->get_num_items_done( 'course', 'quizzes' ); }

	// Get course progress percentage
	public function course_progress()
	{
		$total = $this->get_num_items_total( 'course' );
		$done = $this->get_num_items_done( 'course' );
		return $done == 0 ? $done : round( ( $done / $total ) * 100 );
	}


	/**
	 * Returns the url of the course badge
	 */
	public function get_course_badge_url()
	{
		return default_course_batch( get_field( 'img_course_badge' ) );
	}

	/**
	 * Returns the progress width in percent
	 */
	public function get_progress_width()
	{
		return $this->course_progress().'%';
	}

	/**
	 * Print the progress message
	 * eg.: Progress: Lessons watched: 2/4 • Quizzes passed: 0/1
	 */
	public function print_progress_message()
	{
		$message = sprintf( '%s <strong class="t-comp-text">%d/%d</strong> ',
			__( 'Lessons watched:', 'gladtidings' ),
			$this->get_num_items_done( 'course', 'lessons' ), // number of lessons watched
			$this->get_num_items_total( 'course', 'lessons' ) // total number of lessons in the course
		);

		if( $this->get_num_items_total( 'course', 'quizzes' ) ) {
			$message .= sprintf( '&bull; %s <strong class="t-comp-text">%d/%d</strong> ',
				__( 'Quizzes passed:', 'gladtidings' ),
				$this->get_num_items_done( 'course', 'quizzes' ), // number of quizzes passed
				$this->get_num_items_total( 'course', 'quizzes' ) // total number of quizzes in the course
			);
		}

		printf( '<strong class="t-comp-text">%s</strong> %s',
			__( 'Progress:', 'gladtidings' ),
			$message
		);
	}

	/**
	 * 
	 */
	public function get_description()
	{
		return get_field( 'course_description' );
	}

}