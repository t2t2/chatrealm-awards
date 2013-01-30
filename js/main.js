$(function () {

	/* Based off http://onehackoranother.com/projects/jquery/jquery-grab-bag/text-effects.html */
    function randomAlphaNum() {
        var rnd = Math.floor(Math.random() * 62);
        if (rnd >= 52) return String.fromCharCode(rnd - 4);
        else if (rnd >= 26) return String.fromCharCode(rnd + 71);
        else return String.fromCharCode(rnd + 65);
    }

    $.fn.scrambledWriter = function() {
        this.each(function() {
            var $ele = $(this), str = $ele.text(), progress = 0, replace = /[^\s]/g,
                random = randomAlphaNum, inc = 1;
            $ele.height($("#voting-label").height());
            $ele.text(str.replace(replace, random));
            var timer = setInterval(function() {
                $ele.text(str.substring(0, progress) + str.substring(progress, str.length).replace(replace, random));
                if (progress >= str.length + inc) {
                    clearInterval(timer);
                    clearInterval(revealtimer);
                    $ele.height("");
                }
            }, 20);
            var revealtimer = setInterval(function() {
                progress += inc
            }, Math.min(50, 2000/str.length));
        });
        return this;
    };

	$("#vote-step1 .category[data-category]").click(function () {
		if($(this).data("category")) {
			$("#vote-step1").slideUp(400)
			$("#vote-step2").slideDown(400).removeClass("hidden")
			$("#category-id").val($(this).data("category"))
			$("#category-text").text($(this).data("text"))
		}
	});
	$("#vote-step1-header, #nominate-back").click(function () {
		if ($("#vote-step1:hidden")) {
			$("#vote-step1").slideDown(400)
			$("#vote-step2").slideUp(400, function () {
				$(this).addClass("hidden")
				$("#category-id, #category-title, #category-url").val("")
				$("#category-text").text("")
			});
		}
	});
	$("#nomination-form").submit(function() {
		var category = $("#category-id").val();
		var text = $("#category-title").val();
		var url = $("#category-url").val();
		if(text.length == 0 && url.length == 0) {
			$("#category-title, #category-url").clearQueue().queue(function (next) {
				$(this).addClass("alert"); next();
			}).delay(500).queue(function (next) {
				$(this).removeClass("alert"); next();
			});
			return false;
		}
		$("#nominate-submit").attr("disabled", "disabled")
		$.post($("#nomination-form").attr("action"), {category: category, text: text, url: url}, function (data) {
			$("#nominate-submit").removeAttr("disabled")
			if(data.success) {
				$("#vote-step1").slideDown(400, function () {
					$(".category[data-category=\""+category+"\"]").addClass("disabled btn-success").data("category", "").find(".icon-trophy").removeClass("icon-trophy").addClass("icon-check")
				})
				$("#vote-step2").slideUp(400, function () {
					$(this).addClass("hidden")
				});
				$("#category-id, #category-title, #category-url").val("")
				$("#category-text").text("")
			} else {
				alert(data.error)
			}
		}, "json");
		return false;
	});
	$("date[data-countdown]").html("").kkcountdown({
		dayText	: ' day ',
        daysText: ' days ',
        displayZeroDays: false,
	});
	$("#voting-categories .category[data-category]").click(function () {
		if($(this).data("category")) {
			$("#voting").modal()
			$("#voting-label").text("Loading Nominees...")
			$("#voting-content").html("<i class=\"icon-spinner icon-spin icon-2x pull-left\"></i>")
			$.get(BASE_URL+"nominees/"+$(this).data("category"), function (data) {
				if(data.success) {
					$("#voting-label").text(data.category.title).scrambledWriter();
					$("#voting-content").html(Mustache.render("{{#nominees}}<div class=\"vote\"><img src=\"{{image}}\" /><div class=\"vote-text\">{{^already}}<button class=\"vote-btn btn btn-success\" data-category=\"{{category.id}}\" data-nominee=\"{{id}}\">Vote</button>{{/already}}<h4>{{name}}</h4><small><a href=\"{{url}}\">[www]</a></small></div></div>{{/nominees}}{{#already}}You have already voted in this category today{{/already}}", data))
					$(".vote-btn").click(function () {
						var category = $(this).data("category"), nominee = $(this).data("nominee");
						var dat = this;
						$(this).attr("disabled", "disabled")
						$.post(BASE_URL+"vote", {category: category, nominee: nominee}, function (data) {
							if(data.success) {
								$(".vote-btn:not([disabled])").removeClass("btn-success").attr("disabled", "disabled")
								$(".category[data-category=\""+category+"\"]").addClass("btn-success").find(".icon-trophy").removeClass("icon-trophy").addClass("icon-check")
							} else {
								$(dat).removeAttr("disabled")
								alert(data.error)
							}
						}, "json");
					});
				} else {
					alert(data.error)
				}
			}, "json");
		}
		return false;
	});
});