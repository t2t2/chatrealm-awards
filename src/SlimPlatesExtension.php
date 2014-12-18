<?php
namespace t2t2;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Slim\Slim;

class SlimPlatesExtension implements ExtensionInterface {

	/**
	 * @var Slim
	 */
	private $slim;

	/**
	 * @param Slim $slim
	 */
	public function __construct(Slim $slim) {
		if (! $slim) {
			$this->app = Slim::getInstance();
		} else {
			$this->slim = $slim;
		}
	}

	/**
	 * Register Slim-friendly functions
	 *
	 * @param Engine $engine
	 */
	public function register(Engine $engine) {
		$engine->registerFunction('url', array($this, 'url'));
		$engine->registerFunction('urlFor', array($this, 'urlFor'));
	}


	/**
	 * Get an URL based on root of the app
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public function url($value) {
		return $this->slim->request->getRootUri() . '/' . $value;
	}

	/**
	 * Get an URL for the route
	 *
	 * @param        $value
	 * @param array  $params
	 *
	 * @return string
	 */
	public function urlFor($value, $params = array()) {
		return $this->slim->urlFor($value, $params);
	}

}