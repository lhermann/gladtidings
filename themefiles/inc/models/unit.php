<?php
/*------------------------------------*\
    Application Module
\*------------------------------------*/

class Unit extends Application
{

	function __construct( $post )
	{

		parent::__construct( $post );

		$this->order     = (int)$post->order;
		$this->position  = (int)$post->position;
		$this->parent_id = (int)$post->parent_id;
	}

	/*=======================*\
		Public Functions
	\*=======================*/


}
