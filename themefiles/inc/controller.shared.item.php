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
	public function print_continue_btn()
	{
		// get id of the next item
		$item_ids = array();
		foreach ($this->unit->lesson_order as $n) {
			if( (int)end($n) > 0 ) $item_ids[] = (int)end($n);
		}
		$next_item_id = $item_ids[ array_search( $ID, $item_ids ) + 1 ];

		// print button
		printf ( '<a class="btn btn--success" href="%1$s" title="%2$s">%2$s <span class="fi fi-arrow-right"></span></a>',
			esc_url( $next_item_id ? get_permalink( $next_item_id ) : get_term_link( $this->unit ).'?origin=continue' ),
			__( 'Next', 'gladtidings' ),
			$attr
		);
	}

	/*====================================*\
		Node Functions
	\*====================================*/

	/**
	 * Used on print_link_to function to get the $attr because node_lesson and node_quizz share the same code
	 */
	public function get_node_title( $object )
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
