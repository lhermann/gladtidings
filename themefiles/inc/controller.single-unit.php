<?php
/*------------------------------------*\
    Single Unit Controller
\*------------------------------------*/

class GTView extends GTGlobal
{

	function __construct( &$object )
	{

		// call parent __contruct
		parent::__construct( $object );

		// set up course
		// $this->course = get_posts( array( 'name' => get_query_var( 'course-name' ), 'post_type' => 'course' ) )[0];
		$this->course = get_post( $object->parent_id );

		$this->children = $this->get_children( $object );

		// Built Inline Theme CSS Styles
		add_filter( 'theme_css', 'add_theme_color', 10 );
	}

	/*=======================*\
		Unit Functions
	\*=======================*/

	public function get_items()
	{
		return $this->children;
	}


	public function print_continue_btn()
	{
		$progress = $this->get_progress( $this->unit );

		// bail early
		if( $progress === 100 ) return;

		$btn_label = $progress ? __( 'Continue learning', 'gladtidings' ) : __( 'Start learning', 'gladtidings' );

		// find the first item that is not done
		foreach ( $this->children as $key => $child ) {
			if( $child->post_type == 'headline' ) continue;
			if( $child->post_status == 'publish' ) {
				$next_item = $child;
				break;
			} 
		}

		print( '<a class="layout__item u-pull--right btn btn--success" href="'.gt_get_permalink( $next_item, $this->course, $this->unit ).'">'.$btn_label.'</a>' );
		return;
	}


	/*=======================*\
		Hero Functions
	\*=======================*/

	/**
	 * Meta States:
	 * <span class="color--success">Completed</span>
	 * <span class="color--locked">Locked: Complete "%s" first</span>
	 * <span class="color--primary">Coming soon: 01/01/2016</span>
	 */
	public function hero_meta()
	{
		switch ( $this->unit->post_status ) {
			case 'coming':  $output = '&bull; <span class="color--primary t-comp-text">' .          __('Coming soon', 'gladtidings') . ': ' . $this->node->release_date . '</span>'; break;
			case 'locked':  $output = '&bull; <span class="color--locked t-comp-text">'  . sprintf( __('Locked: Complete "%s" first', 'gladtidings'), $this->node->unlock_dependency_title ) . '</span>'; break;
			case 'success': $output = '&bull; <span class="color--success">'             .          __('Completed', 'gladtidings') . '</span>'; break;
			default: $output = '';
		}
		return $output;
	}

	/**
	 * Hero footer paragraph
	 * -> get number of videos and lessons
	 */
	public function hero_footer()
	{
		return sprintf( '%s %s',
			( $this->num_lessons( $this->unit ) == 1 ? '<span class="fi fi-video"></span> 1 '._x( 'Lesson', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-video"></span> '.$this->num_lessons( $this->unit ).' '._x( 'Lessons', 'Post Type General Name', 'gladtidings' ) ),
			( $this->num_quizzes( $this->unit ) ? '&nbsp; '.( $this->num_quizzes( $this->unit ) == 1 ? '<span class="fi fi-clipboard-pencil"></span> 1 '._x( 'Quizz', 'Post Type Singular Name', 'gladtidings' ) : '<span class="fi fi-clipboard-pencil"></span> '.$this->num_quizzes( $this->unit ).' '._x( 'Quizzes', 'Post Type General Name', 'gladtidings' ) ) : '' )
		);
	}

	/*=======================*\
		Node Functions
	\*=======================*/
	
	public function item_permalink( $post )
	{
		return gt_get_permalink( $post, $this->course, $this->unit );
	}


}