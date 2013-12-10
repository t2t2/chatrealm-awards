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

			// Countdowns
			$("[data-countdown]").html("").kkcountdown({ displayZeroDays : false, textAfterCount: "now" })
		},
	},
	nominations: {
		init: function () {
			$(".category-panel[data-category-id]").click(function () {
				if(!$(this).data("category-id")) return false;
				$("#categories-list").slideUp(100)
				$("#nomination-form-container").slideDown(50)

				$("#nominee-category-dropdown").val($(this).data("category-id"))
				$("#category-text").text($(this).data("category-title"))
				return false
			});
			$("#js-back-to-categories").click(function () {
				$("#categories-list").slideDown(100)
				$("#nomination-form-container").slideUp(100)
				return false
			});
			$("#nomination-form").submit(function () {
				var category = $("#nominee-category-dropdown").val();
				var title = $("#nominee-title").val();
				var url = $("#nominee-url").val();
				// Just cause
				if(title == "t2t2" && category == 23) {
					alert("How about nominating someone who actually deserves an award?")
					return false
				}


				$("#nomination-button").attr("disabled", "disabled")
				$.post($("#nomination-form").attr("action"), {category: category, title: title, url: url}, function (data) {
					$("#nomination-button").removeAttr("disabled")
					if(data.success) {
						$("#categories-list").show(0, function () {
							$(".category-panel[data-category-id=\""+category+"\"]").addClass("voted").removeClass("votable").data("category-id", "").find(".fa-trophy").removeClass("fa-trophy").addClass("fa-check-square-o")
						})
						$("#nomination-form-container").hide();
						$("#nominee-category-dropdown, #nominee-title, #nominee-url").val("")
						$("#category-text").text("")
					} else {
						alert(data.error)
					}
				}, "json");
				return false
			});
		}
	}
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