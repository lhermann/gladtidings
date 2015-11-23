(function($) {

	/*
	 * On Post Type Lesson Edit Page
	 * Add class 'active' to active radio option
	 */
	console.log( $("#acf-type .acf-radio-list input:checked").parent() );
	$("#acf-type .acf-radio-list input:checked").parent().parent().addClass("active");
	$("#acf-type .acf-radio-list").on( "change", "li", function(event) {
		$("#acf-type .acf-radio-list li").removeClass("active");
		$( this ).addClass("active");
	});

})( jQuery );