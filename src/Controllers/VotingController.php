<?php
namespace t2t2\Controllers;

use ArrayObject;
use Carbon\Carbon;
use t2t2\BaseController;

class VotingController extends BaseController {

	/**
	 * Load home page for current season
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function home() {
		$req = self::$app->request();
		$season = $this->getSeason();
		if ($season['current'] != 'voting') {
			$this::$app->pass();
		}

		$data = $this->getViewArguments($season);

		$data['categories'] = $season->categorys()->where('published', 1);
		$data['between'] = array(new Carbon($season['voting_start']), new Carbon($season['voting_end']));

		$data['voted_today'] = $voted_today = new ArrayObject();
		$vote_checks = self::$app->db->votes(array(
			"ip"   => $req->getIp(),
			"date" => Carbon::now()->toDateString()
		));
		foreach ($vote_checks as $vote) { // Somehow this is just 2 queries.... wait isn't this just a bunch of copy-pasta?
			$voted_today[$vote->nominee["category_id"]] = $vote->nominee["id"];
		}

		// Next reset time
		$data['next_reset'] = Carbon::tomorrow();

		self::$app->render('voting', $data);
	}

	/**
	 * Vote for something
	 *
	 * @param $season
	 * @param $category
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function submit($season, $category) {
		$req = self::$app->request();
		$season = $this->getSeason($season);
		if ($season['current'] != 'voting') {
			$this::$app->pass();
		}

		// Check if time's up
		$inTime = Carbon::now()
		                ->between(new Carbon($season['voting_start']), new Carbon($season['voting_end']));
		if (! $inTime) {
			if ($req->isAjax()) {
				echo json_encode(array('error' => 'Voting period is over!'));
				return;
			} else {
				self::$app->flash('alert', 'Voting period is over!');
				self::$app->redirect(self::$app->urlFor('home.voting'));
			}
		}

		$nominee = $req->params("nominee");
		if(!($category = $season->categorys("id", $category)->fetch()) or !$category["published"]
			or !($nominee = $category->nominees("id", $nominee)->fetch())) { // Can't find category, nominee or unpublished category
			if($req->isAjax()) {
				echo json_encode(array("error" => "ಠ_ಠ"));
				return;
			} else {
				self::$app->flash("alert", "ಠ_ಠ");
				self::$app->redirect(self::$app->urlFor("home.voting"));
			}
		}

		// IP-check
		$vote_check = self::$app->db->votes(array(
			'ip'   => $req->getIp(),
			'date' => Carbon::now()->toDateString()
		))->where('nominee_id', $category->nominees())->limit(1);

		if(count($vote_check) === 0) {
			// Save nomination
			self::$app->db->votes->insert(array(
				'nominee_id' => $nominee['id'],
				'ip' => $req->getIp(),
				'date' => Carbon::now()->toDateString(),
			));
			if($req->isAjax()) {
				echo json_encode(array('success' => true));
				return;
			} else {
				self::$app->flash('success', 'Your vote has been saved!');
				self::$app->redirect(self::$app->urlFor('home.voting'));
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