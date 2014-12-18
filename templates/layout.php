<?php
/**
 * @var League\Plates\Template\Template|t2t2\SlimPlatesExtension $this
 * @var string                                                   $title
 * @var \NotORM_Row[]                                            $seasons
 * @var \NotORM_Row                                              $season
 * @var string[][]|null                                          $debug
 */
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>

	<link rel="stylesheet" href="<?= $this->url('css/normalize.css'); ?>">
	<link rel="stylesheet" href="<?= $this->url('css/foundation.min.css'); ?>">
	<link rel="stylesheet" href="<?= $this->url('css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?= $this->url('css/main.css'); ?>">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	<script src="<?= $this->url('js/vendor/modernizr.js'); ?>"></script>

	<link type="text/plain" rel="author" href="<?= $this->url('humans.txt'); ?>"/>
	<meta property="og:image" content="<?= $this->url('img/awards.jpg') ?>"/>
</head>

<body<?= isset($javascript) ? " data-controller=\"{$javascript[0]}\" data-action=\"{$javascript[1]}\"" : null ?>>
<div id="previous-awards" style="display: none;">
	<a href="#" class="js-previous-awards-link right" id="previous-awards-close">Close <i class="fa fa-times"></i></a>

	<h2>Previous Awards</h2>
	<ul>
		<?php foreach ($seasons as $prevSeason): ?>
			<li>
				<?php if ($prevSeason['archived']): ?>
					<a href="<?= $this->urlFor('results',
						array('season' => $prevSeason['id'])); ?>"><?= $prevSeason["name"] ?></a>
				<?php else: ?>
					<a href="<?= $this->urlFor('home'); ?>?season=<?= $this->e($prevSeason['id']); ?>"><?= $prevSeason["name"] ?>
						<span class="label secondary">Active Now!</span></a>
				<?php endif; ?>
			</li>
		<?php endforeach ?>
	</ul>
</div>
<header class="global clearfix">
	<div class="row">
		<a href="#" class="label right js-previous-awards-link" id="previous-awards-link">Previous awards</a>

		<div class="small-12 columns">
			<h1><?= $season["name"] ?></h1>
		</div>
	</div>
</header>

<?php if (isset($flash)): ?>
	<div class="row">
		<?php foreach ($flash as $type => $message): ?>
			<div class="alert-box <?= $type ?>">
				<?= $message ?>
			</div>
		<?php endforeach ?>
	</div>
<?php endif ?>

<?= $this->section('content') ?>

<footer>
	<div class="row padding">
		<div class="small-12 columns">
			<hr/>
			&copy; <?= date("Y") ?> Chatrealm Productions
			<?php if (isset($debug)): ?>
				<hr/>
				<div class="debug">
					<?php foreach ($debug as $line): ?>
						<code>
							<?= $line[0] ?>
							<br/>
							<?= implode('<br />', $line[1]); ?>
						</code>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>

<script src="<?= $this->url("js/vendor/jquery-2.0.3.min.js"); ?>"></script>
<script src="<?= $this->url("js/vendor/foundation.min.js"); ?>"></script>
<script src="<?= $this->url("js/vendor/swfobject.js"); ?>"></script>
<script src="<?= $this->url("js/vendor/kkcountdown.min.js"); ?>"></script>
<script src="<?= $this->url("js/main.js"); ?>"></script>
</body>
</html>
