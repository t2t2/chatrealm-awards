<?php
require "vendor/autoload.php";

use Carbon\Carbon;

// Initialisation
session_start();
$app = new \Slim\Slim(require "config.php");
$app->view(new \t2t2\SlimPlates());

$app->container->singleton("pdo", function () use($app) {
	$config = $app->config("database");

	return new PDO("mysql:dbname={$config["dbname"]}", $config["username"], $config["password"]);
});
$app->container->singleton("db", function () use($app) {
	$config = $app->config("database");

	$structure = new NotORM_Structure_Convention(
		$primary = "id",
		$foreign = "%s_id",
	    $table = "%ss", // {$table}s
		$prefix = $config["prefix"] // award_$table
	);

	return new NotORM($app->pdo, $structure);;
});
/*
$app->db->debug = function($query, $parameters) {
	var_dump(compact("query", "paraemters"));
};
*/
date_default_timezone_set($app->config("timezone"));

// Add data
$app->view->setData(array(
	"app" => $app,
));

function getSeasonViewData($season = null) {
	if (is_null($season)) {
		$season = $app->db->seasons[$app->config("season")];
	}

	$timeplan = array(
		array(
			"key" => "categories",
			"title" => "Category Nominations",
			"description" => "We can't just give out awards like nothing. Help us decide the categories!",
			"class" => false,
		),
		array(
			"key" => "nominations",
			"title" => "Nominations",
			"description" => "You can't just expect one guy to come up with a list of nominees.",
			"class" => false,
		),
		array(
			"key" => "voting",
			"title" => "Voting",
			"description" => "You decide who will be the winner!",
			"class" => false,
		),
	);

	$past = true;
	foreach ($timeplan as &$section) {
		$section["start"] = new Carbon($season["{$section["key"]}_start"]);
		$section["end"] = new Carbon($season["{$section["key"]}_end"]);
		if($section["key"] == $season["current"]) {
			$past = false;
			$section["class"] = "active";
		}
		if($past) {
			$section["class"] = "past";
		}
	}

	$timeplan[] = array(
		"key" => "show",
		"title" => "Awards Show",
		"description" => "We could just print a list of winners on this page and be done with it. NO!",
		"class" => ($past ? "active" : false),
		"when" => new Carbon($season["awards_show"]),
	);

	$title = $season["name"];

	return compact("timeplan", "title");
}
function array_random_value($array) {
	return $array[array_rand($array)];
}

/* CATEGORY NOMINATIONS PERIOD */
$app->get("/", function() use($app) {
	$season = $app->db->seasons[$app->config("season")];
	if($season["current"] != "categories") {
		$app->pass();
	}

	extract(getSeasonViewData($season));

	$seasons = $app->db->seasons()->order("id desc")->select("id", "name");
	$categoryPlaceholders = array(
		"Best kitten picture", "Nicest tweet", "The Slammiest Jam", "Most otaku kawaiiii desu desu desu"
	);

	$between = array(new Carbon($season["categories_start"]), new Carbon($season["categories_end"]));

	$app->render("categories", compact("title", "seasons", "season", "timeplan", "categoryPlaceholders", "between"));
})->name("home.categories");

$app->post("/category", function() use($app) {
	$req = $app->request();
	$season = $app->db->seasons[$app->config("season")];
	if($season["current"] != "categories") {
		$app->pass();
	}

	$between = array(new Carbon($season["categories_start"]), new Carbon($season["categories_end"]));

	if($between[0]->isFuture() or $between[1]->isPast()) {
		$app->pass();
	}

	$category = $req->params("category");
	$nominees = $req->params("nominees");

	if(strlen($category) == 0) {
		$app->flash("alert", "Please enter a category");
		$app->redirect($app->urlFor("home.categories"));
	}

	$added = $app->db->category_nominations()->insert(array(
		"category" => $category,
		"nominees" => $nominees,
		"ip" => $req->getIp(),
		"date" => Carbon::now()->toDateString(),
		"created_at" => Carbon::now()->toDateTimeString(),
	));

	if($added) {
		$app->flash("success", "Your category nomination has been saved!");
		$app->redirect($app->urlFor("home.categories"));
	} else {
		$app->flash("alert", "Error saving :(");
		$app->redirect($app->urlFor("home.categories"));
	}
})->name("categories.post");



// Do your thing
$app->run();