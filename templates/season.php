<?php
/**
 * @var League\Plates\Template\Template|t2t2\SlimPlatesExtension $this
 * @var \NotORM_Row                                              $season
 * @var array[]                                                  $timeplan
 */
?>

<div class="inverted padding">
	<div id="content" class="row">
		<div class="columns">
			<p>
				<?= $season["promo"] ?>
			</p>
			<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-4 timeplan">
				<?php foreach ($timeplan as $section): ?>
					<li<?= $section['class'] ? " class=\"{$section['class']}\"" : null ?>>
						<div class="panel">
							<h6><?= $section['title'] ?></h6>

							<p><?= $section['description'] ?></p>
							<?php if (isset($section['start']) and isset($section['end'])): ?>
								<em title="<?= $section['start']->toCOOKIEString() ?> - <?= $section['end']->toCOOKIEString() ?>">
									<?php if ($section['class'] == 'active'): ?>
										<?php if ($section["start"]->isFuture()): ?>
											Starts in <?= $section['start']->diffForHumans() ?>
										<?php elseif ($section['end']->isFuture()): ?>
											Ends in <?= $section['end']->diffForHumans() ?>
										<?php
										else: ?>
											Duder, it's over!
										<?php endif ?>
									<?php else: ?>
										<?= $section["start"]->toFormattedDateString() ?> - <?= $section["end"]->toFormattedDateString() ?>
									<?php endif ?>
								</em>
							<?php else: ?>
								<em title="<?= $section["when"]->toCOOKIEString() ?>"><?= $section["when"]->format("l, M jS H:i T") ?></em>
							<?php endif ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>