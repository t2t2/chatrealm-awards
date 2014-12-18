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
 * @var boolean[]                                                $nominated_today
 */
$javascript = array('nominations', 'home');
$this->layout('layout', compact('title', 'season', 'seasons', 'debug', 'flash', 'javascript'));
?>

<?php $this->insert('season', compact('season', 'timeplan')) ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Nominations</h2>

			<p>If I had my way I would make sure you could only vote for me! But apparently this isn't allowed according
				to the great code of awards ceremonies. Someone should tell that to <a
					href="http://chatrealm.us/awards/" target="_blank">those chumps</a>.</p>

			<p>You can nominate once per day per category! The limit is reset at midnight PST. Next reset is in
				<time data-time="<?= $next_reset->format('U'); ?>" data-countdown="true">less than 24 hours</time>
				.
			</p>
		</div>
		<?php if ($between[0]->isFuture()): ?>
			<div class="alert-box">Nomination period hasn't started yet.</div>
		<?php elseif ($between[1]->isFuture()): ?>
			<div id="nominations-container">
				<div id="categories-list" class="show-js">
					<h3>Categories</h3>
					<ul class="small-block-grid-1 medium-block-grid-2">
						<?php foreach ($categories as $category): ?>
							<li>
								<a href="#"
									<?php if (! isset($nominated_today[$category["id"]])): ?>
										data-category-id="<?= $category ?>"
										data-category-title="<?= $this->e($category["title"]) ?>"
										class="panel votable category-panel clearfix"
									<?php else: ?>
										class="panel voted category-panel clearfix"
									<?php endif ?>
									>
									<div class="category-trophy">
										<i class="fa <?= isset($nominated_today[$category["id"]]) ? 'fa-check-square-o' : 'fa-trophy' ?> fa-4x"></i>
									</div>

									<div class="category-text">
										<small>The <?= $this->e($category["award"]) ?> award for</small>
										<h4><?= $this->e($category["title"]) ?></h4>
									</div>
								</a>

							</li>
						<?php endforeach ?>
					</ul>
				</div>
				<div id="nomination-form-container" class="hide-js">
					<form action="<?= $this->urlFor('nominations.post', array('season' => $season['id'])) ?>"
					      method="POST" id="nomination-form">
						<div class="panel category-panel clearfix">
							<div class="category-trophy">
								<i class="fa fa-trophy fa-4x"></i>
							</div>
							<div class="category-text">
								<div class="show-js right"><a href="#" id="js-back-to-categories">&lt; Back to
										categories</a>
								</div>

								<label for="nominee-category-dropdown">Category:</label>
								<select name="category" id="nominee-category-dropdown" class="hide-js">
									<?php foreach ($categories as $category): ?>
										<option value="<?= $category ?>"><?= $this->e($category["title"]) ?></option>
									<?php endforeach ?>
								</select>

								<p id="category-text" class="show-js"></p>

								<label for="nominee-title">Nominee:</label>
								<input type="text" id="nominee-title" name="title" required/>

								<label for="nominee-url">URL (optional):</label>
								<input type="text" id="nominee-url" name="url" placeholder="http://"/>

								<button id="nomination-button" class="button right shorty" type="submit">Nominate
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		<?php
		else: ?>
			<div class="alert-box">Nomination period has ended.</div>
		<?php
		endif ?>
	</div>
</div>