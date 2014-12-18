<?php
namespace t2t2\Controllers;

use ArrayObject;
use Carbon\Carbon;
use t2t2\BaseController;

class NominationsController extends BaseController {

	/**
	 * Load home page for current season
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function home() {
		$req = self::$app->request();
		$season = $this->getSeason();
		if ($season['current'] != 'nominations') {
			$this::$app->pass();
		}

		$data = $this->getViewArguments($season);

		// Set view data
		$data['categories'] = $season->categorys()->where('published', 1);
		$data['between'] = array(new Carbon($season['nominations_start']), new Carbon($season['nominations_end']));

		// Check what user has already nominated today
		$data['nominated_today'] = $nominated_today = new ArrayObject();
		$nomination_checks = self::$app->db->nominations(array(
			"ip"   => $req->getIp(),
			"date" => Carbon::now()->toDateString()
		));
		foreach ($nomination_checks as $nomination) { // Somehow this is just 1 query.
			$nominated_today[$nomination["category_id"]] = true;
		}

		// Next reset time
		$data['next_reset'] = Carbon::tomorrow();

		self::$app->render('nominations', $data);
	}

	/**
	 * Submit nomination
	 *
	 * @param $season
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function submit($season) {
		$req = self::$app->request();
		$season = $this->getSeason($season);
		if ($season['current'] != 'nominations') {
			$this::$app->pass();
		}

		// Check if time's up
		$inTime = Carbon::now()
		                ->between(new Carbon($season['nominations_start']), new Carbon($season['nominations_end']));
		if (! $inTime) {
			if ($req->isAjax()) {
				echo json_encode(array("error" => "Nomination period is over!"));
				return;
			} else {
				self::$app->flash("alert", "Nomination period is over!");
				self::$app->redirect(self::$app->urlFor("home.categories"));
			}
		}

		$category = $req->params("category");
		$title = $req->params("title");
		$url = $req->params("url");

		// Check that category exists and is public
		if (! ($category = $season->categorys("id", $category)->fetch()) or ! $category["published"]) {
			if ($req->isAjax()) {
				echo json_encode(array("error" => "ಠ_ಠ"));
				return;
			} else {
				self::$app->flash("alert", "ಠ_ಠ");
				self::$app->redirect(self::$app->urlFor("home.categories"));
			}
		}

		// IP-check
		$nomination_check = $category->nominations(array(
			"ip"   => $req->getIp(),
			"date" => Carbon::now()->toDateString()
		))->limit(1);

		if(count($nomination_check) === 0) {
			// Save nomination
			$nomination_check->insert(array(
				"title" => $title,
				"url" => $url,
				"ip" => $req->getIp(),
				"date" => Carbon::now()->toDateString(),
			));

			if($req->isAjax()) {
				echo json_encode(array("success" => true));
				return;
			} else {
				self::$app->flash("success", "Your nomination has been saved!");
				self::$app->redirect(self::$app->urlFor("home.categories"));
			}
		} else {
			if($req->isAjax()) {
				echo json_encode(array('error' => 'Already nominated in this category today'));
				return;
			} else {
				self::$app->flash('alert', 'Already nominated in this category today');
				self::$app->redirect(self::$app->urlFor('home.categories'));
			}
		}
	}

}