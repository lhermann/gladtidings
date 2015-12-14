/*------------------------------------*\
    #FLYOUT
\*------------------------------------*/

/**
 * Toggle class .flyout--active class .flyout is present
 */
var toggleFlyout = function( action ) {
	var page = document.getElementsByTagName("body")[0];
	var bodyClasses = page.getAttribute("class").split(" ");
	if( bodyClasses.indexOf("flyout") >= 0 ) {

		var key = bodyClasses.indexOf("flyout--active");
		if ( key >= 0 ) {
			bodyClasses.splice(key, 1);
			page.setAttribute("class", bodyClasses.join(" "));
		} else if ( action !== "close" ) {
			bodyClasses.push("flyout--active");
			page.setAttribute("class", bodyClasses.join(" "));
		}

	}	
}

// Toggle flyout on button click
window.onload = function() {
	var flyoutButton = document.getElementById("flyout-button");
	flyoutButton.onclick = function(event) {
		toggleFlyout();
		event.preventDefault();
	}
	var flyoutClose = document.getElementById("flyout-close");
	flyoutClose.onclick = function(event) {
		toggleFlyout("close");
		event.preventDefault();
	}
}

// Deactivate flyout on resize
window.onresize = function() {
	toggleFlyout("close");
}

