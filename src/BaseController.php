<?php
namespace t2t2;

use Carbon\Carbon;
use Slim\Slim;

class BaseController {
	/**
	 * @var Slim
	 */
	static public $app;

	static public $season;

	/**
	 * Construct a controller
	 *
	 * @param Slim $app
	 */
	function __construct(Slim $app = null) {
		// Set current app
		if($app) {
			static::$app = $app;
		} else {
			static::$app = Slim::getInstance();
		}
	}

	/**
	 * Get the appropriate season
	 *
	 * @return \NotORM_Row
	 * @throws \Slim\Exception\Pass
	 */
	protected function getSeason($target = null) {
		if(static::$season) {
			return static::$season;
		}

		$target = $target ?: static::$app->request->params('season');

		/** @var \NotORM_Result $db */
		$db = static::$app->db->seasons()->limit(1)->order('id DESC');
		if ($target) {
			$db->where('id', $target);
		}

		static::$season = $db->fetch();

		if (! static::$season) {
			static::$app->notFound();
		}

		return static::$season;
	}

	/**
	 * Get default values for views based on the season
	 *
	 * @param $season
	 *
	 * @return array
	 */
	protected function getViewArguments($season) {
		if (is_null($season)) {
			return array();
		}

		// Build schedule
		$timeplan = array(
			array(
				'key'         => 'categories',
				"title"       => 'Category Nominations',
				'description' => "We can't just give out awards like nothing. Help us decide the categories!",
				'class'       => false,
			),
			array(
				'key'         => 'nominations',
				'title'       => 'Nominations',
				'description' => "You can't just expect one guy to come up with a list of nominees.",
				'class'       => false,
			),
			array(
				'key'         => 'voting',
				'title'       => 'Voting',
				'description' => "You decide who will be the winner!",
				'class'       => false,
			),
		);

		$past = true;
		foreach ($timeplan as &$section) {
			$section['start'] = new Carbon($season["{$section["key"]}_start"]);
			$section['end'] = new Carbon($season["{$section["key"]}_end"]);
			if ($section['key'] == $season['current']) {
				$past = false;
				$section['class'] = 'active';
			}
			if ($past) {
				$section['class'] = 'past';
			}
		}

		$timeplan[] = array(
			'key'         => 'show',
			'title'       => 'Awards Show',
			'description' => 'We could just print a list of winners on this page and be done with it. NO!',
			'class'       => ($past ? 'active' : false),
			'when'        => new Carbon($season['awards_show']),
		);

		// Set title
		$title = $season['name'];

		// Load previous seasons
		$seasons = static::$app->db->seasons()->order('id desc')->select('id', 'name', 'archived');

		return compact('season', 'timeplan', 'title', 'seasons');
	}
}