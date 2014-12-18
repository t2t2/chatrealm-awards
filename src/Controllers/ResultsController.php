<?php
namespace t2t2\Controllers;

use Carbon\Carbon;
use t2t2\BaseController;

class ResultsController extends BaseController {

	/**
	 *
	 */
	public function home() {
		$season = $this->getSeason();
		if ($season['current'] != 'show') {
			$this::$app->pass();
		}

		if($season['archived']) {
			$this->results();
			return;
		}

		$data = $this->getViewArguments($season);
		$data['showtime'] = new Carbon($season["awards_show"]);

		self::$app->render('show', $data);
	}

	/**
	 * Show results page for a season
	 *
	 * @param null   $season
	 * @param string $format
	 * @param null   $secret
	 *
	 * @throws \Slim\Exception\Pass
	 */
	public function results($season = null, $format = 'html', $secret = null) {
		$season = $this->getSeason($season);

		if (! $season['archived'] && $season['access_secret'] != $secret) {
			self::$app->pass();
		}

		/** @var \NotORM_Result $categories */
		$categories = $season->categorys()->where('published', 1);
		$results_data = array_map(function($category) {
			$returns = array(
				'id' => $category['id'],
				'title' => $category['title'],
				'award' => $category['award'],
				'nominees' => array(),
				'total' => 0
			);

			foreach($category->nominees() as $nominee) {
				$returns['nominees'][] = array(
					'id' => $nominee['id'],
					'name' => $nominee['name'],
					'url' => $nominee['url'],
					'count' => intval($nominee->votes()->count('id'))
				);
				$returns['total'] += $nominee->votes()->count('id');
			}

			return $returns;
		}, $categories->jsonSerialize());

		if($format == 'json') {
			self::$app->response->headers->set('Content-Type', 'application/json');
			echo json_encode($results_data);
		} else {
			$data = $this->getViewArguments($season);

			array_walk($results_data, function(&$category) {
				usort($category['nominees'], function($a, $b) {
					return $b['count'] - $a['count']; // z-to-a
				});
			});

			$data['results_data'] = $results_data;

			self::$app->render('results', $data);
		}
	}
}