<?php
namespace t2t2\Controllers;

use Carbon\Carbon;
use t2t2\BaseController;

class CategoryNominationsController extends BaseController {

	/**
	 * Load home page for current season
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function home() {
		$season = $this->getSeason();
		if ($season['current'] != 'categories') {
			$this::$app->pass();
		}

		$data = $this->getViewArguments($season);

		$data['between'] = array(new Carbon($season['categories_start']), new Carbon($season['categories_end']));

		$data['placeholders'] = array(
			'Best kitten picture',
			'Nicest tweet',
			'The Slammiest Jam',
			'Most otaku kawaiiii desu desu desu',
			'Most likely to become the next top model',
		);

		self::$app->render('categories', $data);
	}

	/**
	 * Submit category nomination
	 *
	 * @param $season
	 */
	public function submit($season) {
		$request = self::$app->request;
		$season = $this->getSeason($season);
		if (! $season || $season['current'] != 'categories') {
			self::$app->halt(403, 'Not allowed for season');
		}

		$inTime = Carbon::now()
		                ->between(new Carbon($season['categories_start']), new Carbon($season['categories_end']));
		if (! $inTime) {
			self::$app->flash('alert', 'Too late for category nominations!');
			self::$app->redirect(self::$app->urlFor('home.categories'));
		}

		$category = $request->params("category");
		$nominees = $request->params("nominees");

		if(strlen($category) == 0) {
			self::$app->flash("alert", "Please enter a category");
			self::$app->redirect(self::$app->urlFor("home.categories"));
		}

		$added = self::$app->db->category_nominations()->insert(array(
			'season_id' => $season['id'],
			'category' => $category,
			'nominees' => $nominees,
			'ip' => $request->getIp(),
			'date' => Carbon::now(),
			'created_at' => Carbon::now(),
		));

		if($added) {
			self::$app->flash("success", "Your category nomination has been saved!");
			self::$app->redirect(self::$app->urlFor("home.categories"));
		} else {
			self::$app->flash("alert", "Error saving :(");
			self::$app->redirect(self::$app->urlFor("home.categories"));
		}
	}


}