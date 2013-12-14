<?php
$this->layout("master");
$this->javascript = array('nominations', 'home');
?>

<?php $this->insert("season") ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Nominations</h2>
			<p>If I had my way I would make sure you could only vote for me! But apparently this isn't allowed according to the great code of awards ceremonies. Someone should tell that to <a href="http://chatrealm.us/awards/" target="_blank">those chumps</a>.</p>
			<p>You can nominate once per category per day! The limit is reset at midnight PST. Next reset is in <date data-time="<?= $this->next_reset->format('U'); ?>" data-countdown="true">less than 24 hours</date>.</p>
		</div>
		<?php if($this->between[0]->isFuture()): ?>
			<div class="alert-box">Nomination period hasn't started yet.</div>
		<?php elseif($this->between[1]->isFuture()): ?>
		<div id="nominations-container">
			<div id="categories-list" class="show-js">
				<h3>Categories</h3>
				<ul class="small-block-grid-1 medium-block-grid-2">
					<?php foreach($this->categories as $category): ?>
						<li>
							<?php if(!isset($this->nominated_today[$category["id"]])): ?>
							<a href="#" data-category-id="<?= $category ?>" data-category-title="<?= $this->e($category["title"]) ?>" class="panel votable category-panel clearfix">
								<div class="category-trophy">
									<i class="fa fa-trophy fa-4x"></i>
								</div>
							<?php else: ?>
							<div class="panel voted category-panel clearfix">
								<div class="category-trophy">
									<i class="fa fa-check-square-o fa-4x"></i>
								</div>
							<?php endif ?>
								<div class="category-text">
									<small>The <?= $this->e($category["award"]) ?> award for</small>
									<h4><?= $this->e($category["title"]) ?></h4>
								</div>
							<?php if(!isset($this->nominated_today[$category["id"]])): ?>
							</a>
							<?php else: ?>
							</div>
							<?php endif ?>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
			<div id="nomination-form-container" class="hide-js">
				<form action="<?= $this->urlFor("nominations.post") ?>" method="POST" id="nomination-form">
					<div class="panel category-panel clearfix">
						<div class="category-trophy">
							<i class="fa fa-trophy fa-4x"></i>
						</div>
						<div class="category-text">
							<div class="show-js right"><a href="#" id="js-back-to-categories">&lt; Back to categories</a></div>

							<label for="nominee-category-dropdown">Category:</label>
							<select name="category" id="nominee-category-dropdown" class="hide-js">
								<?php foreach ($this->categories as $category): ?>
									<option value="<?= $category ?>"><?= $this->e($category["title"]) ?></option>
								<?php endforeach ?>
							</select>
							<p id="category-text" class="show-js"></p>

							<label for="nominee-text">Nominee:</label>
							<input type="text" id="nominee-title" name="title" required />

							<label for="nominee-url">URL (optional):</label>
							<input type="text" id="nominee-url" name="url" placeholder="http://" />

							<button id="nomination-button" class="button right shorty" type="submit">Nominate</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php else: ?>
			<div class="alert-box">Nomination period has ended.</div>
		<?php endif ?>
	</div>
</div>