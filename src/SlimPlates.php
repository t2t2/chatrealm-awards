<?php
namespace t2t2;

use League\Plates\Engine;
use Slim\Slim;
use Slim\View;

class SlimPlates extends View {

	/**
	 * @var Engine
	 */
	public $engine = null;

	/**
	 * @var Slim
	 */
	public $app = null;

	public function __construct(Slim $app = null) {
		parent::__construct();

		// Try to autofetch Slim if it's skipped
		if(!$app) {
			$this->app = Slim::getInstance();
		} else {
			$this->app = $app;
		}
	}

	/**
	 * Render a template
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	public function render($template) {
		$templates = $this->getEngine();

		return $templates->render($template, $this->data->all());
	}

	/**
	 * Get a plates engine instance
	 *
	 * @return Engine
	 */
	public function getEngine() {
		if($this->engine) {
			return $this->engine;
		}

		$this->engine = new Engine($this->getTemplatesDirectory());
		$this->engine->loadExtension(new SlimPlatesExtension($this->app));

		return $this->engine;
	}
}
