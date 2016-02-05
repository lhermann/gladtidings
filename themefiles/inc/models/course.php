<?php
class Course extends Application
{
	// public ;

	// function __construct( $post )
	// {
	// 	parent::__construct( $post );
	// }

	public function batch_src()
	{
		return gt_get_course_batch( $this->ID );
	}
}
