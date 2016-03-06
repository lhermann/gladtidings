/*------------------------------------*\
    #FLYOUT
\*------------------------------------*/

// Toggle flyout on button click
window.onload = function() {
	// get array of flyout activator buttons
	var buttonArray = Array.from(document.getElementsByClassName("flyoutButton"));
	// console.log(buttonArray);
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
}

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

// Deactivate flyout on resize
window.onresize = function() {
	var container = document.getElementById("container");
	toggleClass( container, "has-flyout--left-active", null, "remove" );
	toggleClass( container, "has-flyout--right-active", null, "remove" );
}

