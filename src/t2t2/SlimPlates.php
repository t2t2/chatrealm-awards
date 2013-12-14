<?php
namespace t2t2;

class SlimPlates extends \Slim\View {
	// Instance of plate engine
	public $engine = null;
	public $app = null;

	public function __construct(\Slim\Slim $app = null) {
		parent::__construct();

		// Try to autofetch Slim if it's skipped
		if(!$app) {
			$this->app = \Slim\Slim::getInstance();
		} else {
			$this->app = $app;
		}
	}

	// Renderer
	public function render($tplFile) {
		$template = new \League\Plates\Template($this->getEngine());

		return $template->render($tplFile, $this->all());
	}

	// Get plates engine instnace
	public function getEngine() {
		if($this->engine) {
			return $this->engine;
		}

		$this->engine = new \League\Plates\Engine($this->getTemplatesDirectory());
		$this->engine->loadExtension(new SlimPlatesExtension($this->app));

		return $this->engine;
	}
}

class SlimPlatesExtension implements \League\Plates\Extension\ExtensionInterface {
	public $engine;
	public $template;
	public $app;

	public function __construct(\Slim\Slim $app = null) {
		// Try to autofetch Slim if it's skipped
		if(!$app) {
			$this->app = \Slim\Slim::getInstance();
		} else {
			$this->app = $app;
		}
	}

	public function getFunctions() {
		return array(
			'urlFor' => 'urlFor',
			'url' => 'url',
		);
	}

	public function urlFor($value, $params = array()) {
		return $this->app->urlFor($value, $params);
	}

	public function url($value) {
		return $this->app->request->getRootUri().'/'.$value;
	}
}