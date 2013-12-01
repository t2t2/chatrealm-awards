<?php $this->layout("master"); ?>

<?php $this->insert("season") ?>

<div class="row">
	<div class="medium-6 columns">
		<h2>Category Nominations</h2>
		<p>Look buddy. We can't just give out awards like it's nothing. So help us come up with the categories and you get to suggest some nominations that will have a highe chance of getting nominated.</p>
		<p>You can nominate as many categories as you want during this week.</p>
	</div>
	<div class="medium-6 columns">
		<?php if($this->between[0]->isFuture()): ?>
			<div class="alert-box">Nomination period hasn't started yet.</div>
		<?php elseif($this->between[1]->isFuture()): ?>
			<form action="<?= $this->urlFor("categories.post") ?>" method="POST" data-abide>
				<div class="row">
					<div class="columns">
						<label for="form-category">Category:</label>
						<input id="form-category" name="category" type="text" placeholder="<?= array_random_value($this->categoryPlaceholders) ?>" required />
						<small class="error">Gotta have a category to nominate. Just submitting the placeholder won't work</small>
					</div>
				</div>
				<div class="row">
					<div class="columns">
						<label for="form-nominees">Possible nominations:</label>
						<textarea id="form-nominees" name="nominees" style="height: 120px;"></textarea>
						<small class="help">For helping us come up with categories you can also send in your list of possible nominees. Nominees from here will be considered in the nominees list.</small>
					</div>
				</div>
				<div class="row">
					<div class="columns">
						<button type="submit" class="button small shorty">Submit</button>
					</div>
				</div>
			</form>
		<?php else: ?>
			<div class="alert-box">Nomination period has ended.</div>
		<?php endif ?>
	</div>
</div>
