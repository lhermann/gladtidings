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

		// touch course
		$existed = $this->touch( 'course', $this->course->ID );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );
	}

	/*=======================*\
		Course Functions
	\*=======================*/

	public function get_units()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "gt_relationships";

		$query = "SELECT *
				  FROM $wpdb->posts p
				  INNER JOIN $table_name r
				  ON r.child_id = p.ID
				  WHERE r.parent_id = {$this->course->ID}
				  AND p.post_status IN ('publish', 'coming', 'locked')
				  ORDER BY r.position;
				 ";
		$units = $wpdb->get_results( $query, OBJECT );

		// replace post_type 'quizz' with 'exam'
		array_walk( $units, function(&$unit) {
			if( $unit->post_type == 'quizz' ) $unit->post_type = 'exam';
		});

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
		if( !$total || !$done ) return 0; 
		return round( ( $done / $total ) * 100 );
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


	/*=======================*\
		Node Functions
	\*=======================*/

	public $node;
	public $node_status_num;

	public function setup_node( $post )
	{
		$this->setup_context( $post );

		$this->node = $post;

		/**
		 * Get Status
		 *
		 * Note: Read the user meta to determine if he has started the lesson already or completed it
		 *       then write the information into the unit_status, so it is available in this loop
		 */
		$status = 0;
		switch ( $this->get_status( $post ) ) {
			case 'success':		$status++;			// 5
			case 'active':		$status++;			// 4
			case 'publish':		$status++;			// 3
			case 'locked':		$status++;			// 2
			case 'coming':		$status++; break;	// 1
			case 'draft':
			default:			return; break;		// 0
		}
		$this->node_status_num = $status;

	}

	/**
	 * CSS classes
	 */
	public function node_classes()
	{
		return sprintf( 'nl__item %s %s %s',
			'nl__item--'.$this->node->position,
			'nl__item--'.$this->node->post_type,
			'nl__item--'.$this->get_status( $this->node )
		);
	}

	/**
	 * Link coresponding to the node
	 */
	public function node_link()
	{
		return $this->node_status_num >= 3 ? '<a href="'.gt_get_permalink( $this->node ).'">' : '';
	}

	/**
	 * Button
	 */
	public function node_button()
	{
		$start_label = $this->node->post_type == 'unit' ? __('Start Unit', 'gladtidings') : __('Start Exam', 'gladtidings');

		$button = '';
		switch ( $this->node_status_num ) {
			case 1: $button = '<span class="label label--theme">'           . __('Coming Soon' , 'gladtidings') . '</span>'; break;
			case 2: $button = '<span class="label label--theme">'           . __('Locked'      , 'gladtidings') . '</span>'; break;
			case 3: $button = '<span class="btn btn--theme btn--small">'    . $start_label	                    . '</span>'; break;
			case 4: $button = '<span class="btn btn--success btn--small">'  . __('Continue'    , 'gladtidings') . '</span>'; break;
			case 5: $button = '<span class="btn btn--unstress btn--small">' . __('Review'      , 'gladtidings') . '</span>'; break;
		}
		return $button;
	}

	/**
	 * Meta States:
	 * <span class="color--success">Completed</span>
	 * <span class="color--locked">Locked: Complete "%s" first</span>
	 * <span class="color--primary">Coming soon: 01/01/2016</span>
	 */
	public function node_meta()
	{
		switch ( $this->node_status_num ) {
			case 1:  $output = '&bull; <span class="color--primary t-comp-text">' .          __('Coming soon', 'gladtidings') . ': ' . date( "F j, Y", strtotime( get_post_meta( $this->node->ID, 'release_date', true ) ) ) . '</span>'; break;
			case 2:  $output = '&bull; <span class="color--locked t-comp-text">'  . sprintf( __('Locked: Complete "%s" first', 'gladtidings'), $this->get_unlock_dependancy_title( $this->node->ID ) ) . '</span>'; break;
			case 5:  $output = '&bull; <span class="color--success">'             .          __('Completed', 'gladtidings') . '</span>'; break;
			default: $output = '';
		}
		return $output;
	}

	/**
	 * Node footer Paragraph
	 * -> get number of videos and lessons
	 */
	public function node_footer()
	{
		if( $this->node_status_num >= 2 ) {
			return sprintf( '%s %s',
				( $this->num_lessons() == 1 ? '<span class="fi fi-video"></span> 1 '._x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-video"></span> '.$this->num_lessons().' '._x( 'Lessons', 'Post Type General Name', 'gladtidings' ) ),
				( $this->num_quizzes() ? '&nbsp; '.( $this->num_quizzes() == 1 ? '<span class="fi fi-clipboard-pencil"></span> 1 '._x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-clipboard-pencil"></span> '.$this->num_quizzes().' '._x( 'Quizzes', 'Post Type General Name', 'gladtidings' ) ) : '' )
			);
		}
		return '';
	}

	/**
	 * Show Node Progress percentage
	 */
	public function node_progress()
	{
		if( $this->node_status_num === 4 ) {
			return sprintf( '<div class="nl__node__progress" style="width: %1$s%%"></div><div class="nl__node__progress-text">%1$s</div>',
				$this->get_progress()
			);
		} else {
			return '';
		}
	}

	/**
	 * Returns the title of the 'unlock_dependancy'
	 * that is, the unit | exam that needs to be completed before this one unlocks
	 */
	public function get_unlock_dependancy_title( $object_id )
	{
		global $wpdb;
		$pos = get_post_meta( $object_id, 'unlock_dependency', true ) - 1;
		$query = "SELECT post_title
				  FROM $wpdb->posts p
				  INNER JOIN $wpdb->gt_relationships r
				  ON r.child_id = p.ID
				  WHERE r.parent_id = {$this->course->ID}
				  AND r.position = $pos;
				 ";
		return $wpdb->get_var( $query );
	}

}