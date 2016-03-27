<?php
/*------------------------------------*\
    User Module
\*------------------------------------*/

class User
{
	public $ID, $first_name, $last_name, $name, $role;
	private $data;

	function __construct()
	{
		global $current_user;

		$this->ID         = $current_user->ID;
		$this->first_name = $current_user->user_firstname;
		$this->last_name  = $current_user->user_lastname;
		$this->name       = $current_user->display_name;
		$this->role       = '';
		$this->data       = $this->get_data();
	}


	/*=======================*\
		Private Functions
	\*=======================*/

	private function get_data()
	{
		global $wpdb;
		$query_str = "SELECT meta_key, meta_value
						FROM $wpdb->usermeta
						WHERE user_id = $this->ID
						AND ( meta_key LIKE 'course_%'
							OR meta_key LIKE 'unit_%'
							OR meta_key LIKE 'exam_%'
							OR meta_key LIKE 'lesson_%'
							OR meta_key LIKE 'quizz_%' )";
		$rows = $wpdb->get_results( $query_str, OBJECT );
		$return = new stdClass();
		foreach ( $rows as $row ) {
			$value = maybe_unserialize( $row->meta_value );
			$return->{$row->meta_key} = is_numeric( $value ) ? (int)$value : $value;
		}
		return $return;
	}


	/*=======================*\
		Public Functions
	\*=======================*/

	/**
	 * OUTPUT: (int) User ID
	 */
	public function ID()
	{
		return wp_get_current_user() ? (int)wp_get_current_user()->data->ID : false;
	}

	/**
	 * OUTPUT: (string) User Name
	 */
	public function name( $key )
	{
		global $current_user;
		var_dump($current_user->user_firstname, $current_user->user_lastname);
		return wp_get_current_user() ? wp_get_current_user()->data->display_name : false;
	}

	/**
	 * OUTPUt:
	 *	(int|string) if DB entry existed
	 * 	(NULL) if not
	 */
	public function get( $object, $key )
	{
		$xkey = "{$object->type}_{$object->ID}_{$key}";

		if( isset($this->data->{$xkey}) ) {
			return $this->data->{$xkey};
		} else {
			return NULL;
		}
	}

	public function get_ids( $type = 'course', $key = 'touched' )
	{
		$ids = [];
		$pattern = '/^'.$type.'_(\d+)_'.$key.'$/';
		foreach ( $this->data as $xkey => $value ) {
			if( preg_match( $pattern, $xkey, $matches ) ) {
				$ids[] = $matches[1];
			}
		}
		return $ids;
	}

	/**
	 * OUTPUt: (true|false) if DB entry existed
	 */
	public function update( $object, $key, $value )
	{
		$xkey = "{$object->type}_{$object->ID}_{$key}";
		$isset = isset($this->data->{$xkey});
		// update
		$this->data->{$xkey} = $value;
		update_user_meta( $this->ID, $xkey, is_bool($value) ? (int)$value : $value );
		return $isset;
	}

	/**
	 * OUTPUt: (int) new Value
	 */
	public function increment( $object, $key )
	{
		$xkey = "{$object->type}_{$object->ID}_{$key}";
		$value = isset($this->data->{$xkey}) ? $this->data->{$xkey} + 1 : 1;

		$this->update( $object, $key, $value );

		return $value;
	}


	public function courses()
	{
		$args = array(
			'posts_per_page'   => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'course',
			'post_status'      => 'publish',
			'include'          => $this->get_ids( 'course', 'touched' ),
			'suppress_filters' => false
		);
		return array_map( function($p) {
			return gt_instantiate_object( $p );
		}, get_posts( $args ) );
	}



	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID = object ID or term_id
	 * 	$name =	name of the key
	 * OUTPUt:
	 *	if DB entry exists = int
	 * 	else = false
	 */
	// public function get_value( $scope, $ID, $name )
	// {
	// 	$key = "{$scope}_{$ID}_{$name}";

	// 	if( isset($this->data->{$key}) ) {
	// 		return $this->data->{$key};
	// 	} else {
	// 		return NULL;
	// 	}
	// }

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$name
	 * 	$value = new value
	 * OUTPUt: DB entry existed true|false
	 */
	// public function update_value( $scope, $ID, $name, $value )
	// {
	// 	$key = "{$scope}_{$ID}_{$name}";
	// 	$isset = isset($this->data->{$key});
	// 	$this->data->{$key} = $value;
	// 	update_user_meta( $this->ID, $key, is_bool($value) ? (int)$value : $value );
	// 	return $isset;
	// }

	/**
	 * INPUT:
	 *	$scope = 'course'|'unit'|'lesson'|'quizz'
	 * 	$ID
	 *	$name
	 * OUTPUt: New Value
	 */
	// public function increment_value( $scope, $ID, $name )
	// {
	// 	$key = "{$scope}_{$ID}_{$name}";
	// 	$value = isset($this->data->{$key}) ? $this->data->{$key} + 1 : 1;

	// 	$this->update_value( $scope, $ID, $name, $value );

	// 	return $value;
	// }

	/**
	 * Delete question/answer cache for a quizz or exam
	 * [1] There is sometimes a problem when the purge is applied after the new question has been saved already
	 */
	public function purge_answers( $object )
	{
		// purge them from the database
		global $wpdb;
		$query_str = "DELETE FROM $wpdb->usermeta
						WHERE user_id = $this->ID
						AND ( meta_key LIKE '%s'
							OR meta_key LIKE '%s'
						)";
		$return = $wpdb->query(
			$wpdb->prepare(
					$query_str,
					"{$object->type}_{$object->ID}_question-%", /* [1] */
					"{$object->type}_{$object->ID}_answer-%"
				)
		);

		// purge them also in $this->data
		foreach ( array('question', 'answer') as $name ) {
			for ( $i=1; $i <= $object->required_questions; $i++ ) {
				$key = "{$object->type}_{$object->ID}_{$name}-{$i}";
				unset( $this->data->{$key} );
			}
		}

		return $return;
	}

	/**
	 * INPUT: Page String, eg: 'dashboard'
	 */
	public function url_to( $page = '' )
	{
		$page = $page == 'dashboard' ? '' : $page;
		return esc_url( home_url( '/' ) . 'user/' . $page );
	}

	/**
	 * INPUT:
	 *   %args  -> possible arguments:
	 *              'class'     = css class
	 *              'title'     = link title="" attribute
	 *              'attribute' = any attribute, eg. disabled
	 *              'display'   = the link text or label (should be renamed label)
	 */
	public function link_to( $page = 'show', $args = array() )
	{
		switch ($page) {
			default: $page = 'show';
			case 'show'     : $title = __( 'Dashboard', 'gladtidings' ); break;
			case 'messages' : $title = __( 'Messages', 'gladtidings' );  break;
			case 'settings' : $title = __( 'Settings', 'gladtidings' );  break;
		}

		$class = isset( $args['class'] ) ? $args['class'].' permalink' : 'permalink';

		global $wp_query;
		if( $wp_query->debug['model'] == 'User' && $wp_query->debug['action'] == $page ) {
			$class .= ' active';
		} elseif ( $wp_query->debug['model'] == 'Message' && $page == 'messages' ) {
			$class .= ' active';
		}

		return sprintf( '<a class="%2$s" href="%1$s" title="%3$s" %4$s>%5$s</a>',
				$this->url_to( $page ),
				$class,
				isset( $args['title']     ) ? $args['title']     : __('Permalink to:', 'gladtidings') . ' ' . $title,
				isset( $args['attribute'] ) ? $args['attribute'] : '',
				isset( $args['display']   ) ? $args['display']   : $title
		);
	}
}
