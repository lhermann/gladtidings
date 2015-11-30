(function($) {

	/**
	 * Disable unit_id field
	 * Note: I cannot use this function because disabled fields will not be transmitted via $GLOBAL['acf']
	 */
	/*
	if( typeof(acf) === 'object' ) {
		acfDisableField( $(".acf-course-unit-id") ); // after DOM load
		acf.add_action('append', function( el ){ // when new repeater fields are added

			var field = el.find(".acf-course-unit-id");
			acfDisableField( field );
			
		});
		function acfDisableField ( field ) {
			field.find("input").prop( "disabled", true);
		}
	}
	*/

})( jQuery );