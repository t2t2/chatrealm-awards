<?php
/**
 * @var League\Plates\Template\Template|t2t2\SlimPlatesExtension $this
 * @var Carbon\Carbon[]                                          $between
 * @var string                                                   $title
 * @var \NotORM_Row[]                                            $seasons
 * @var \NotORM_Row                                              $season
 * @var string[][]|null                                          $debug
 * @var array[]                                                  $results_data
 */
$this->layout('layout', compact('title', 'season', 'seasons', 'debug', 'flash'));
?>

<?php $this->insert('season', compact('season', 'timeplan')) ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Results</h2>
		</div>
		<?php if($season['awards_show_youtube']): ?>
			<div class="flex-video widescreen" id="player">
				<iframe width="640" height="360" src="//www.youtube-nocookie.com/embed/<?= $season['awards_show_youtube'] ?>?rel=0" frameborder="0" allowfullscreen></iframe>
			</div>
		<?php endif; ?>
		<ul class="medium-block-grid-1 large-block-grid-2">
			<?php foreach ($results_data as $category): ?>
				<li>
					<div class="panel category-panel">
						<div class="clearfix">
							<div class="category-trophy">
								<i class="fa fa-trophy fa-4x"></i>
							</div>
							<div class="category-text">
								<small>The <?= $this->e($category['award']) ?> award for</small>
								<h4><?= $this->e($category['title']) ?></h4>
							</div>
						</div>
						<?php foreach ($category['nominees'] as $nominee): ?>
							<div class="nominee clearfix">
								<div class="progress">
									<span class="meter"
									      style="width: <?= round(($nominee["count"] / ($category["total"] ?: 1)) * 100,
										      1); ?>%"></span>
								</div>
								<div
									class="right votes"><?= number_format(($nominee['count'] / ($category['total'] ?: 1)) * 100,
										1); ?>%
								</div>
								<h5><?= $this->e($nominee['name']) ?></h5>
								<? if ($nominee['url']): ?>
									<small><a
											href="<?= $this->e($nominee['url']) ?>"><?= $this->e($nominee['url']) ?></a>
									</small>
								<? endif ?>
							</div>
						<?php endforeach ?>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>