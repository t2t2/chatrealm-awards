AWARDS = {
	common: {
		init: function () {
			// Foundation
			$(document).foundation();

			// Previous awards drop-down
			$(".js-previous-awards-link").click(function () {
				$("#previous-awards").slideToggle();
				return false;
			});

			// Countdowns
			$("[data-countdown]").html("").kkcountdown({displayZeroDays: false, textAfterCount: "now"})
		}
	},
	nominations: {
		init: function () {
			// Open category nomination form
			$(".category-panel[data-category-id]").click(function () {
				// Dupe check
				if (!$(this).data("category-id")) {
					return false;
				}

				$("#categories-list").slideUp(500);
				$("#nomination-form-container").slideDown(400);

				$("#nominee-category-dropdown").val($(this).data("category-id"));
				$("#category-text").text($(this).data("category-title"));

				return false;
			});

			// Back to categories link
			$("#js-back-to-categories").click(function () {
				$("#categories-list").slideDown(500);
				$("#nomination-form-container").slideUp(400);
				return false;
			});

			// Nomination submitting
			$("#nomination-form").submit(function () {
				var category = $("#nominee-category-dropdown").val();
				var title = $("#nominee-title").val();
				var url = $("#nominee-url").val();
				// Just cause
				if (title == "t2t2" && category == 23) {
					alert("How about nominating someone who actually deserves an award?");
					return false
				}

				$("#nomination-button").attr("disabled", "disabled");
				$.post($("#nomination-form").attr("action"), {category: category, title: title, url: url},
					function (data) {
						// On submitted
						$("#nomination-button").removeAttr("disabled");
						if (data.success) {
							$(".category-panel[data-category-id=\"" + category + "\"]").addClass("voted").removeClass("votable").data("category-id",
								"").find(".fa-trophy").removeClass("fa-trophy").addClass("fa-check-square-o");
							$("#categories-list").slideDown(500);
							$("#nomination-form-container").slideUp(400, function () {
								$("#nominee-category-dropdown, #nominee-title, #nominee-url").val("");
								$("#category-text").text("")
							});
						} else {
							alert(data.error)
						}
					}, "json");
				return false
			});
		}
	},
	voting: {
		init: function () {
			var voteForms = $('.vote-form');
			voteForms.click(function (event) {
				// http://stackoverflow.com/a/6877923
				$(this).data('clicked', $(event.target))
			});
			voteForms.submit(function () {
				if (!$(this).data('category-id')) {
					return false;
				}

				if ($(this).data('clicked')) { // Only if clickd submit button is known
					var $form = $(this);

					var $targetBtn = $form.data('clicked');
					$targetBtn.attr('disabled', 'disabled');

					var category = $form.data('category-id'),
					    nominee = $targetBtn.get(0).value;

					var data = {};
					data[$targetBtn.get(0).name] = nominee;

					$.post($form.attr('action'), data, function (data) {
						$targetBtn.removeAttr('disabled');
						if (data.success) {
							$form.data('category-id', '');
							$form.find('button[name="nominee"]').remove();
							$('.category-panel[data-category-id="' + category + '"]').addClass('voted')
								.find('.fa-trophy').removeClass('fa-trophy').addClass('fa-check-square-o');
							$('.nominee[data-nominee-id="' + nominee + '"]').prepend('<i class="fa fa-check fa-2x right"></i>');
						} else {
							alert(data.error)
						}
					}, 'json');
				}
				return false;
			});
		}
	},
	video: {
		live: function () {
			$("#live-source").find("[data-source]").click(function () {
				$("#live-source").find(".alert").removeClass('alert');
				if ($(this).data('source') == 'iframe') {
					var $embed = $('<iframe>').attr({
						"width": 640, "height": 360, "frameborder": 0, "allowfullscreen": "allowfullscreen",
						"src": $(this).data('source-url')
					});
					$("#player").empty().append($embed);
				}
				$(this).addClass('alert');
			});
		}
	}
};
/* AWARDS */




/* http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution */
UTIL = {
	exec: function (controller, calledAction) {
		var ns = AWARDS,
		    action = ( calledAction === undefined ) ? "init" : calledAction;

		if (controller !== "" && ns[controller] && typeof ns[controller][action] == "function") {
			ns[controller][action]();
		}
	},

	init: function () {
		var body = document.body,
		    controller = body.getAttribute("data-controller"),
		    action = body.getAttribute("data-action");

		UTIL.exec("common");
		UTIL.exec(controller);
		UTIL.exec(controller, action);
	}
};
$(document).ready(UTIL.init);