<?php
require 'vendor/autoload.php';
require 'config.php';

$app = new \Slim\Slim($settings);
$db = new NotORM($pdo, $structure);

/* Escape characters, taken from Laravel */
function e($value) {
	return htmlentities($value, ENT_QUOTES, "UTF-8", false);
}
$base_url = 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
function url($rest) {
	global $base_url; //yolo
	return $base_url.$rest;
}
function using($thing) {
	return $thing;
}

$app->view()->appendData(array('base_url' => $base_url));

/* Nominations */
$app->get("/", function() use ($app, $db) {
	if($app->config("phase") != "nominations") {
		$app->pass();
	}
	if($app->config("nomination-start")->diff(new DateTime)->invert == 1) {
		$app->render("teaser.php", array("start_date" => $app->config("nomination-start")));
		return true;
	}
	$req = $app->request();
	$time_left = $app->config("nomination-end")->diff(new DateTime);
	// Already nominated
	$nominated_today = array();
	if($time_left->invert) {
		$awardcheck = $db->nominations();
		$awardcheck->where("ip", $req->getIp());
		$awardcheck->where("date", using(new DateTime())->format('Y-m-d'));
		foreach ($awardcheck as $nomination) {
			$nominated_today[$nomination["category"]] = true;
		}
	}
	$next_reset = new DateTime("tomorrow");
	$app->render("home.php", array("categories" => $app->config("categories"), "end_date" => $app->config("nomination-end"), "time_left" => $time_left, "open" => $time_left->invert == 1, "nominated_today" => $nominated_today, "next_reset" => $next_reset));
});
$app->post("/nominate", function() use ($app, $db) {
	$req = $app->request();
	$time_left = $app->config("nomination-end")->diff(new DateTime);
	if($time_left->invert == 0) {
		echo json_encode(array("error" => "Nomination period is over!"));
		return;
	}
	if(strlen($req->params("text")) == 0 && strlen($req->params("url")) == 0) {
		echo json_encode(array("error" => "Fill out one of the nomine info fields"));
		return;
	}
	$categories = $app->config("categories");
	if(!$categories[$req->params("category")]) {
		echo json_encode(array("error" => "Invalid category"));
		return;
	}
	// Nomination time limit check
	$awardcheck = $db->nominations();
	$awardcheck->where("category", $req->params("category"));
	$awardcheck->where("ip", $req->getIp());
	$awardcheck->where("date", using(new DateTime())->format('Y-m-d'));
	$awardcheck->limit(1);
	if(count($awardcheck) == 0) {
		$nomination = $db->nominations()->insert(array(
			"category" => $req->params("category"),
			"title" => $req->params("text"),
			"url" => $req->params("url"),
			"ip" => $req->getIp(),
			"date" => using(new DateTime())->format('Y-m-d'),
		));
		echo json_encode(array("success" => true));
		return;
	} else {
		echo json_encode(array("error" => "Already voted in this category today"));
		return;
	}
});
/* Voting */
$app->get("/", function() use ($app, $db) {
	if($app->config("phase") != "voting") {
		$app->pass();
	}
	$req = $app->request();
	$time_left = $app->config("voting-end")->diff(new DateTime);
	// Already nominated
	$voted_today = array();
	if($time_left->invert) {
		$awardcheck = $db->votes();
		$awardcheck->where("ip", $req->getIp());
		$awardcheck->where("date", using(new DateTime("now", $app->config("voting-reset-timezone")))->format('Y-m-d'));
		foreach ($awardcheck as $vote) {
			$voted_today[$vote->nominees["categories_id"]] = true;
		}
	}
	$next_reset = new DateTime("tomorrow", $app->config("voting-reset-timezone"));
	$categories = $db->categories()->where("published", 1);
	$app->render("vote.php", array("categories" => $categories, "end_date" => $app->config("voting-end"), "time_left" => $time_left, "open" => $time_left->invert == 1, "voted_today" => $voted_today, "next_reset" => $next_reset));
});
$app->get("/nominees/:id", function($id) use ($app, $db) {
	if($app->config("phase") != "voting") {
		$app->pass();
	}
	$req = $app->request();
	$category = $db->categories[$id];
	if(!$category) {
		echo json_encode(array("error" => "Not Found"));
		return;
	}
	$time_left = $app->config("voting-end")->diff(new DateTime);
	$already = false;
	if($time_left->invert == 0) {
		$already = true;
	}
	foreach ($category->nominees() as $nominee) {
		$votecheck = $nominee->votes();
		$votecheck->where("ip", $req->getIp());
		$votecheck->where("date", using(new DateTime("now", $app->config("voting-reset-timezone")))->format('Y-m-d'));
		$votecheck->limit(1);
		if(count($votecheck) > 0) {
			$already = true;
		}
	}
	$nominees = array_values(array_map(function($nominee) {
		return $nominee->jsonSerialize();
	}, $category->nominees()->jsonSerialize()));

	echo json_encode(array(
		"success" => true, "already" => $already, "category" => $category->jsonSerialize(),
		"nominees" => $nominees
	));
	return;
});
$app->post("/vote", function() use ($app, $db) {
	$req = $app->request();
	$time_left = $app->config("voting-end")->diff(new DateTime);
	if($time_left->invert == 0) {
		echo json_encode(array("error" => "Voting period is over!"));
		return;
	}
	if(!$req->params("category") || !$req->params("nominee")) {
		echo json_encode(array("error" => "Bug in your voting pants"));
		return;
	}
	$category = $db->categories[$req->params("category")];
	if(!$category) {
		echo json_encode(array("error" => "Invalid category"));
		return;
	}

	$todaycheck = true;
	$votedfor = false;
	// Nomination time limit check
	$nominees = $category->nominees();
	foreach ($nominees as $nominee) {
		if($nominee["id"] == $req->params("nominee")) {
			$votedfor = $nominee;
		}

		$votecheck = $nominee->votes();
		$votecheck->where("ip", $req->getIp());
		$votecheck->where("date", using(new DateTime("now", $app->config("voting-reset-timezone")))->format('Y-m-d'));
		$votecheck->limit(1);
		if(count($votecheck) > 0) {
			$todaycheck = false;
		}
	}
	if($todaycheck) {
		if(!$votedfor) {
			echo json_encode(array("error" => "Invalid vote"));
			return;
		}
		$votedfor->votes()->insert(array(
			"ip" => $req->getIp(),
			"date" => using(new DateTime("now", $app->config("voting-reset-timezone")))->format('Y-m-d'),
		));
		echo json_encode(array("success" => true));
		return;
	} else {
		echo json_encode(array("error" => "Already voted in this category today"));
		return;
	}
});

$app->get("/results/:secret(/:format)", function($secret, $format = "page") use ($app, $db) {
	if($app->config("secret") and $app->config("secret") != $secret) {
		$app->pass();
	}
	$resultdata = array();
	foreach ($db->categories()->where("published", 1) as $catid => $category) {
		$catdata = array(
			"nominees" => array(),
			"id" => $category["id"],
			"text" => $category["title"],
			"award" => $category["award"],
			"total" => 0
		);

		foreach ($category->nominees() as $nomid => $nominee) {
			$catdata["nominees"][] = array("id" => $nominee["id"], "text" => $nominee["name"], "url" => $nominee["url"], "image" => $nominee["image"], "count" => intval($nominee->votes()->count("id")));
			$catdata["total"] += $nominee->votes()->count("id");
		}
		$resultdata[] = $catdata;
	}
	if($format == "json") {
		echo json_encode(array("categories" => $resultdata));
	} else {
		$app->render("results.php", array("resultdata" => $resultdata));
	}
});

$app->notFound(function () use ($app) {
    $app->render('404.html');
});






$app->run();