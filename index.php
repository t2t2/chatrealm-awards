<?php
use Slim\Slim;
use t2t2\SlimPlates;

require 'vendor/autoload.php';

// Initialisation
session_start();
$app = new Slim(require __DIR__ . '/config.php');
$app->view(new SlimPlates($app));

// Set up database
$app->container->singleton('pdo', function () use ($app) {
	$config = $app->config('database');

	return new PDO("mysql:dbname={$config['dbname']}", $config['username'], $config['password'],
		array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
});
$app->container->singleton('db', function () use ($app) {
	$config = $app->config('database');

	$structure = new NotORM_Structure_Convention(
		$primary = 'id',
		$foreign = "%s_id",
		$table = "%ss", // {$table}s
		$prefix = $config['prefix'] // award_$table
	);

	return new NotORM($app->pdo, $structure);
});

if($app->config('debug')) {
	$app->view->set('debug', new ArrayObject());

	$app->db->debug = function($query, $parameters) use($app) {
		$debug = $app->view->get('debug');
		$debug[] = array($query, $parameters);
	};
}

date_default_timezone_set($app->config("timezone"));

// Add data
$app->view->setData(array(
	'app' => $app,
));

// Load routes
require __DIR__ . '/src/routes.php';

$app->run();