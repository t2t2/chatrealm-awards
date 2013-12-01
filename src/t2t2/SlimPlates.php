<?php
namespace t2t2;

class SlimPlates extends \Slim\View {
	// Instance of plate engine
	public $engine = null;

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
		$this->engine->loadExtension(new SlimPlatesExtension());

		return $this->engine;
	}
}

class SlimPlatesExtension implements \League\Plates\Extension\ExtensionInterface {
	public $engine;
	public $template;

	public function getFunctions() {
		return array(
			'urlFor' => 'urlFor',
			'url' => 'url',
		);
	}

	public function getSlim() {
		return \Slim\Slim::getInstance();
	}

	public function urlFor($value) {
		return $this->getSlim()->urlFor($value);
	}

	public function url($value) {
		return $this->getSlim()->request->getRootUri().'/'.$value;
	}
}