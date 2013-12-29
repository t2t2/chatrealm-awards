<?php $this->layout("master"); ?>

<?php $this->insert("season") ?>

<div class="row">
	<div class="columns">
		<div class="lead">
			<h2>Results</h2>
		</div>
		<ul class="medium-block-grid-1 large-block-grid-2">
			<?php foreach($this->result_data as $category): ?>
				<li>
					<div class="panel category-panel">
						<div class="clearfix">
							<div class="category-trophy">
								<i class="fa fa-trophy fa-4x"></i>
							</div>
							<div class="category-text">
								<small>The <?= $this->e($category["award"]) ?> award for</small>
								<h4><?= $this->e($category["title"]) ?></h4>
							</div>
						</div>
						<?php foreach($category["nominees"] as $nominee): ?>
							<div class="nominee clearfix">
								<div class="progress">
									<span class="meter" style="width: <?= round(($nominee["count"] / ($category["total"] ?: 1))*100, 1); ?>%"></span>
								</div>
								<div class="right votes"><?= number_format(($nominee["count"] / ($category["total"] ?: 1))*100, 1); ?>%</div>
								<h5><?= $this->e($nominee["name"]) ?></h5>
								<? if($nominee["url"]): ?>
									<small><a href="<?= $this->e($nominee["url"]) ?>"><?= $this->e($nominee["url"]) ?></a></small>
								<? endif ?>
							</div>
						<?php endforeach ?>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>