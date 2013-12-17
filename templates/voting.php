<?php
$this->layout("master");
$this->javascript = array('voting', 'home');
?>

<?php $this->insert("season") ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Voting</h2>
			<p>Finall it's time for the real part of awards - Popularity contest disguised as a voting! Honestly I'm not sure why we even have the voting thing, might as well just hand all the awards to the most popular person.</p>
			<p>You can vote once per category per day! The limit is reset at midnight PST. Next reset is in <date data-time="<?= $this->next_reset->format('U'); ?>" data-countdown="true">less than 24 hours</date>.</p>
		</div>
		<?php if($this->between[0]->isFuture()): ?>
			<div class="alert-box">Voting hasn't started yet.</div>
		<?php elseif($this->between[1]->isFuture()): ?>
		<div id="nominations-container">
			<div id="categories-list">
				<h3>Categories</h3>
				<ul class="medium-block-grid-1 large-block-grid-2">
					<?php foreach($this->categories as $category): ?>
						<li>
							<?php if(!isset($this->voted_today[$category["id"]])): ?>
								<div class="panel category-panel" data-category-id="<?= $category["id"] ?>">
									<div class="clearfix">
										<div class="category-trophy">
											<i class="fa fa-trophy fa-4x"></i>
										</div>
										<div class="category-text">
											<small>The <?= $this->e($category["award"]) ?> award for</small>
											<h4><?= $this->e($category["title"]) ?></h4>
										</div>
									</div>
									<form action="<?= $this->urlFor("voting.post", array("category" => $category["id"])) ?>" method="POST" class="vote-form" data-category-id="<?= $category["id"] ?>">
										<?php foreach($category->nominees() as $nominee): ?>
											<div class="nominee clearfix">
												<button name="nominee" value="<?= $nominee["id"] ?>" class="right shorty button">Vote</button>
												<h5><?= $this->e($nominee["name"]) ?></h5>
												<? if($nominee["url"]): ?>
													<small><a href="<?= $this->e($nominee["url"]) ?>"><?= $this->e($nominee["url"]) ?></a></small>
												<? endif ?>
											</div>
										<?php endforeach ?>
									</form>
								</div>
							<?php else: ?>
								<div class="panel voted category-panel">
									<div class="clearfix">
										<div class="category-trophy">
											<i class="fa fa-check-square-o fa-4x"></i>
										</div>
										<div class="category-text">
											<small>The <?= $this->e($category["award"]) ?> award for</small>
											<h4><?= $this->e($category["title"]) ?></h4>
										</div>
									</div>
								</div>
							<?php endif ?>
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