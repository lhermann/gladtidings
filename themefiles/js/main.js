/*------------------------------------*\
    #FLYOUT
\*------------------------------------*/

$(document).ready( function(){

	/**
	 * Make Tab Navigation work
	 */
	activateTab( $("#tabs .active a") );
	$("#tabs").on("click", "a", function(event){
		event.preventDefault();
		activateTab( $(event.currentTarget) )
	})

});


/**
 * Tab Navigation helper classes
 */
function activateTab(element) {
	element = $( element.first() );
	// tabs
	element.parent()
		.addClass("active")
		.siblings()
		.removeClass("active");
	//content
	$(".tab-content").addClass("hidden");
	$(element.attr("href")).removeClass("hidden")
}








window.onload = function() {

	// Toggle flyout on button click
	var buttonArray = Array.from(document.getElementsByClassName("flyoutButton"));
	if( buttonArray !== null ) {
		buttonArray.forEach( function( button, index ) {
			button.onclick = function(event) {
				var container = document.getElementById("container");
				var side      = button.getAttribute("data-flyout");
				toggleClass( container, "has-flyout--" + side + "-active", "has-flyout" );
				toggleClass( button.parentNode, "flyoutActive" );
				event.preventDefault();
			}
		});
	}

	/**
	 * Make tabs work
	 */
	// var tabElement = document.getElementById("tabs");
	// console.log(tabElement);
	// if( tabElement !== null ) {
	// 	var tabsArray = Array.from(tabElement.getElementsByTagName("a"));
	// 	var id = tabs.getElementsByClassName("active")[0].getElementsByTagName("a")[0].getAttribute("aria-controls");
	// 	console.log(id);
	// 	var contentsArray = Array.from(document.getElementsByClassName("tab-content"));
	// 	showTabContent( contentsArray, id );

	// 	tabsArray.forEach( function( tab, index ) {
	// 		tab.onclick = function(event) {
	// 			event.preventDefault();
	// 			showTabContent( contentsArray, tab.getAttribute("aria-controls") );
	// 		}
	// 	});
	// }
}


window.onresize = function() {

	// Deactivate flyout on resize
	var container = document.getElementById("container");
	toggleClass( container, "has-flyout--left-active", null, "remove" );
	toggleClass( container, "has-flyout--right-active", null, "remove" );

}


// function showTabContent( contentsArray, id ) {
// 	contentsArray.forEach( function( element, index ) {
// 		console.log(element.getAttribute("id"));
// 		if( element.getAttribute("id") == id ) {
// 			toggleClass( element, "hidden", null, "remove" )
// 		} else {
// 			toggleClass( element, "hidden", null, "add" )
// 		}
// 	});
// }


/**
 * Toggle a class on an element
 * INPUT:
 *   element          = the element to toggle the class on
 *   toggleClass      = the css class
 *   conditionalClass = (optional) css class that must be present
 *   limitTo          = (optional) "remove" | "add"
 */
var toggleClass = function( element, toggleClass, conditionalClass, limitTo ) {

	// get the classes array
	var classes = element.getAttribute("class").split(" ");

	// check if the conditionalClass is present
	if( conditionalClass == null || classes.indexOf(conditionalClass) >= 0 ) {

		// check if the class is present
		var key = classes.indexOf(toggleClass);

		// toggle the class
		if ( key >= 0 && limitTo != "add" ) {
			classes.splice(key, 1);
			element.setAttribute("class", classes.join(" "));
		} else if ( limitTo != "remove" ) {
			classes.push(toggleClass);
			element.setAttribute("class", classes.join(" "));
		}

	}
}

