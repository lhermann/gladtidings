<?php
/*------------------------------------*\
    User Module
\*------------------------------------*/

class User
{
	public $ID, $name;
	private $data;

	function __construct()
	{
		$this->ID   = $this->ID();
		$this->name = $this->name();
		$this->data = $this->get_data();
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
	public function name()
	{
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
					"{$object->type}_{$object->ID}_question-[^1]", /* [1] */
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
}
