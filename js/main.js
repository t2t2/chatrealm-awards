AWARDS = {
	common: {
		init: function() {
			// Foundation
			$(document).foundation();

			// Previous awards drop-down
			$(".js-previous-awards-link").click(function () {
				$("#previous-awards").slideToggle();
				return false;
			});
		},
	},
}; /* AWARDS */




/* http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution */
UTIL = {
	exec: function( controller, action ) {
		var ns = AWARDS,
		    action = ( action === undefined ) ? "init" : action;

		if (controller !== "" && ns[controller] && typeof ns[controller][action] == "function") {
			ns[controller][action]();
		}
	},

	init: function() {
		var body = document.body,
		    controller = body.getAttribute("data-controller"),
		    action = body.getAttribute("data-action");

		UTIL.exec("common");
		UTIL.exec(controller);
		UTIL.exec(controller, action);
	}
};
$(document).ready(UTIL.init);