<?php
/**
 * @var League\Plates\Template\Template|t2t2\SlimPlatesExtension $this
 * @var Carbon\Carbon[]                                          $between
 * @var string                                                   $title
 * @var \NotORM_Row[]                                            $seasons
 * @var \NotORM_Row                                              $season
 * @var string[][]|null                                          $debug
 * @var \NotORM_Row[]                                            $categories
 * @var \Carbon\Carbon                                           $next_reset
 * @var int[]                                                    $voted_today
 */
$javascript = array('voting', 'home');
$this->layout('layout', compact('title', 'season', 'seasons', 'debug', 'flash', 'javascript'));
?>

<?php $this->insert('season', compact('season', 'timeplan')) ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Voting</h2>

			<p>Finally it's time for the real part of awards - Popularity contest disguised as a voting! Honestly I'm
				not
				sure why we even have the voting thing, might as well just hand all the awards to the most popular
				person.</p>

			<p>You can vote once per category per day! The limit is reset at midnight PST. Next reset is in
				<time data-time="<?= $next_reset->format('U'); ?>" data-countdown="true">less than 24 hours</time>
				.
			</p>
		</div>
		<?php if ($between[0]->isFuture()): ?>
			<div class="alert-box">Voting hasn't started yet.</div>
		<?php elseif ($between[1]->isFuture()): ?>
			<div id="nominations-container">
				<div id="categories-list">
					<h3>Categories</h3>

					<ul class="medium-block-grid-1 large-block-grid-2">
						<?php foreach ($categories as $category): ?>
							<li>
								<div
									class="panel category-panel <?= (isset($voted_today[$category['id']]) ? 'voted' : '') ?>"
									<?php if (! isset($voted_today[$category['id']])): ?>
										data-category-id="<?= $category['id'] ?>"
									<?php endif; ?>
									>

									<div class="clearfix">
										<div class="category-trophy">
											<?php if (! isset($voted_today[$category['id']])): ?>
												<i class="fa fa-trophy fa-4x"></i>
											<?php else: ?>
												<i class="fa fa-check-square-o fa-4x"></i>
											<?php endif; ?>
										</div>
										<div class="category-text">
											<small>The <?= $this->e($category['award']) ?> award for</small>
											<h4><?= $this->e($category['title']) ?></h4>
										</div>
									</div>

									<form method="POST" action="<?= $this->urlFor('voting.post',
										array('season' => $season['id'], 'category' => $category['id'])) ?>"
									      class="vote-form"
										<?php if (! isset($voted_today[$category['id']])): ?>
											data-category-id="<?= $category['id'] ?>"
										<?php endif; ?>
										>

										<?php foreach ($category->nominees() as $nominee): ?>
											<div class="nominee clearfix" data-nominee-id="<?= $nominee['id'] ?>">
												<?php if (! isset($voted_today[$category['id']])): ?>
													<button name="nominee" value="<?= $nominee['id'] ?>"
													        class="right shorty button">Vote
													</button>
												<?php elseif ($voted_today[$category['id']] == $nominee['id']): ?>
													<i class="fa fa-check fa-2x right"></i>
												<?php endif; ?>

												<h5><?= $this->e($nominee['name']) ?></h5>
												<? if ($nominee['url']): ?>
													<small>
														<a href="<?= $this->e($nominee['url']) ?>">
															<?= $this->e($nominee['url']) ?>
														</a>
													</small>
												<? endif ?>
											</div>
										<?php endforeach ?>
									</form>
								</div>
							</li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		<?php else: ?>
			<div class="alert-box">Voting has ended.</div>
		<?php endif ?>
	</div>
</div>