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

		// set up unit
		$this->unit = $this->setup_object( $object->parent_id );

		// set up course
		$this->course = $this->setup_object( $this->unit->parent_id );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );

	}

	/*====================================*\
		Lesson & Quizz Functions
	\*====================================*/

	public function get_siblings()
	{
		return $this->siblings;
	}

	public function unit( $value = null )
	{
		return $value ? $this->unit->{$value} : $this->unit;
	}

	/**
	 * Return the continue button
	 */
	public function print_continue_btn( $object = null, $attr = '' )
	{
		$object = $object ? $object : $this->{$this->context};

		// get the next object
		$next = $this->siblings[ array_search( $object->ID, array_column( $this->siblings, 'ID' ) ) + 1 ];

		// print button
		printf ( '<a class="btn btn--success" href="%1$s" title="%2$s">%2$s <span class="fi fi-arrow-right"></span></a>',
			esc_url( $next ? $this->get_link_to( $next ) : $this->get_link_to( $this->unit ).'?origin=continue' ),
			__( 'Next', 'gladtidings' ),
			$attr
		);
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
