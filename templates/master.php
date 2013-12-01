<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$this->title?></title>

	<link rel="stylesheet" href="<?= $this->url("css/normalize.css"); ?>">
	<link rel="stylesheet" href="<?= $this->url("css/foundation.min.css"); ?>">
	<link rel="stylesheet" href="<?= $this->url("css/font-awesome.min.css"); ?>">
	<link rel="stylesheet" href="<?= $this->url("css/main.css"); ?>">

	<script src="<?= $this->url("js/vendor/modernizr.js"); ?>"></script>

	<link type="text/plain" rel="author" href="<?= $this->url("humans.txt"); ?>" />
</head>

<body>
	<div id="previous-awards" class="hide">
		<a href="#" class="js-previous-awards-link right" id="previous-awards-close">Close <i class="fa fa-times"></i></a>
		<h2>Previous Awards</h2>
		<ul>
			<?php foreach ($this->seasons as $season): ?>
				<li>
					<?= $season["name"] ?>
				</li>
			<?php endforeach ?>
		</ul>
		<small>History will be back soon!</small>
	</div>
	<header class="global clearfix">
		<div class="row">
			<a href="#" class="label right js-previous-awards-link" id="previous-awards-link">Previous awards</a>
			<div class="small-12 columns">
				<h1><?= $this->season["name"] ?></h1>
			</div>
		</div>
	</header>

	<?php if (isset($this->flash)): ?>
		<div class="row">
			<?php foreach ($this->flash as $type => $message): ?>
				<div class="alert-box <?= $type ?>">
					<?= $message ?>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>

	<?=$this->child()?>

	<footer>
		<div class="row padding">
			<div class="small-12 columns">
				<hr />
				&copy; <?= date("Y") ?> Chatrealm Productions
			</div>
		</div>
	</footer>

	<script src="<?= $this->url("js/vendor/jquery-2.0.3.min.js"); ?>"></script>
	<script src="<?= $this->url("js/vendor/foundation.min.js"); ?>"></script>
	<script src="<?= $this->url("js/main.js"); ?>"></script>
</body>
</html>
