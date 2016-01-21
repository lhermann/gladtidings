<?php
/*------------------------------------*\
    Shared Controller: Item
\*------------------------------------*/

/**
 * Shared functions for Lessons and Quizzes
 */
class GTItem extends GTGlobal
{

	function __construct( &$object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// setup parent
		$this->setup_object( $object->parent_id );

		// if parent is unit: setup course
		if( $this->unit ) {
			$this->setup_object( $this->unit->parent_id );
		}

		// get siblings
		$this->siblings = $this->get_children( $object->parent_id );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );

	}

	/*====================================*\
		Lesson & Quizz Functions
	\*====================================*/

	/**
	 * Get the siblings, optionally limit them by post type
	 * INPUT: array of post types to limit by
	 */
	public function get_siblings( $limit = null )
	{
		if( $limit ) {
			$return = array();
			foreach ( $this->siblings as $object) {
				if( in_array( $object->post_type, $limit ) ) $return[] = $object;
			}
		}

		return $return ? $return : $this->siblings;
	}

	/**
	 * Get a sibling by ID
	 * INPUT: object ID
	 * OUPUT: post object | false
	 */
	// public function get_sibling( $ID )
	// {
	// 	array_walk( $this->siblings, function( $object, $key, $ID ) {
	// 	var_dump( $object->ID, $ID );
	// 		if( $object->ID == $ID ) return $object;
	// 	}, $ID);
	// 	return false;
	// }

	public function unit( $value = null )
	{
		return $value ? $this->unit->{$value} : $this->unit;
	}

	/**
	 * Return the continue button
	 *
	 * Labeling:
	 *   sibling lesson|quizz -> 'Next'
	 *   sibling unit|exam    -> 'Go to Unit %d' | 'Take the Exam'
	 *   parent unit          -> 'Return to Unit Overview'
	 *   parent course        -> 'Return to Course Overview'
	 */
	public function print_continue_btn( $object = null, $attr = '' )
	{
		$object = $object ? $object : $this->{$this->context};

		if( isset($this->is_exam) && $this->is_exam ) {

			// Determin next object
			$next = $this->find_sibling( array( 'position' => $this->{$this->context}->position+1 ) );
			if( !$next ) $next = $this->course;

			// Labeling
			switch ( $next->post_type ) {
				case 'unit'  : $label = sprintf( __( 'Go to Unit %d', 'gladtidings' ), $next->order ); break;
				case 'quizz' : $label =          __( 'Take the Exam', 'gladtidings' ); break;
				case 'course': $label =          __( 'Return to Course Overview', 'gladtidings' ); break;
			}

		} else {

			// Determin next object
			$next = $this->find_sibling( array( 'order' => $this->{$this->context}->order+1 ) );
			if( !$next ) $next = $this->unit;

			// Labeling
			$label = __( 'Next', 'gladtidings' ) . ' <span class="fi fi-arrow-right"></span>';
			switch ( $next->post_type ) {
				case 'lesson':
				case 'quizz' : $label = __( 'Next', 'gladtidings' ) . ' <span class="fi fi-arrow-right"></span>'; break;
				case 'unit'  : $label = __( 'Return to Unit Overview', 'gladtidings' ); break;
			}

		}

		$args = array();
		                    $args['class']     = 'btn btn--success';
		if( isset($label) ) $args['display']   = $label;
		if( $attr )         $args['attribute'] = $attr;

		$this->print_link_to( $next, $args );
	}

	/*====================================*\
		Node Functions
	\*====================================*/

	public function current_node( $object )
	{
		return $object->ID == $this->{$this->context}->ID;
	}

	/**
	 * Used on print_link_to function to get the $attr because node_lesson and node_quizz share the same code
	 */
	public function get_node_link_attr( $object )
	{
		switch ( $object->post_type ) {
			case 'lesson':
				$attr = array( 'display' => __('Lesson', 'gladtidings') . $object->order );
				break;
			case 'quizz':
				$attr = array( 'display' => __('Quizz', 'gladtidings') . $object->order );
				break;
			default:
				$attr = array();
		}
		return $attr;
	}

}
