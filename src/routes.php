<?php

/** @var \Slim\Slim $app */


// Category Nominations
$app->get('/', 't2t2\\Controllers\\CategoryNominationsController:home')
    ->name('home.categories');
$app->post('/season/:season/category', 't2t2\\Controllers\\CategoryNominationsController:submit')
    ->name('categories.post');

// Nominations
$app->get('/', 't2t2\\Controllers\\NominationsController:home')
    ->name('home.nominations');
$app->post('/season/:season/nominate', 't2t2\\Controllers\\NominationsController:submit')
    ->name('nominations.post');

// Voting
$app->get('/', 't2t2\\Controllers\\VotingController:home')
    ->name('home.voting');
$app->post('/season/:season/vote/:category', 't2t2\\Controllers\\VotingController:submit')
    ->name('voting.post');


// Results
$app->get('/', 't2t2\\Controllers\\ResultsController:home')
    ->name('home.results');
$app->get('/results(/:season(/:format(/:secret)))', 't2t2\\Controllers\\ResultsController:results')
    ->name('results');


// Dummy route for urlFor('home')
$app->map('/', function () use ($app) {
		$app->pass();
	})->name('home');
