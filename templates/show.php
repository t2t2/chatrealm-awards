<?php
/**
 * @var League\Plates\Template\Template|t2t2\SlimPlatesExtension $this
 * @var string                                                   $title
 * @var \NotORM_Row[]                                            $showtime
 * @var \NotORM_Row[]                                            $seasons
 * @var \NotORM_Row                                              $season
 * @var array[]                                                  $timeplan
 * @var string[][]|null                                          $debug
 */
$javascript = array('video', 'live');
$this->layout('layout', compact('title', 'season', 'seasons', 'debug', 'flash'));
?>

<?php $this->insert('season', compact('season', 'timeplan')) ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Results</h2>
		</div>
		<p>Chatrealm awards show will be live in
			<time data-time="<?= $showtime->format('U'); ?>"
			      data-countdown="true"><?= $showtime->toCOOKIEString() ?></time>
		</p>
		<?php if ($season['awards_show_youtube']): ?>
			<div class="flex-video widescreen" id="player">
				<iframe width="640" height="360"
				        src="//www.youtube-nocookie.com/embed/<?= $season['awards_show_youtube'] ?>?rel=0"
				        frameborder="0" allowfullscreen></iframe>
			</div>
		<?php else: ?>
			<div class="alert-box">Not yet configured</div>
		<?php endif; ?>
		<div class="flex-video widescreen" data-class-abuse="yes">
			<iframe src="http://irc.t2t2.eu?channels=chat" width="647" height="400" frameborder="0"></iframe>
		</div>
	</div>
</div>