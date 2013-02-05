<?php include "header.php"; ?>
<div class="container">
	<header>
		<h1>Chatrealm Podcast Awards 2012</h1>
	</header>

<?php
if($open) { ?>
	<div class="content">
		<h2>Voting period is now open for <span class="days"><?php echo $time_left->format("%a")+1; ?></span> more day(s)</h2>
		<p>You can vote for one entry in each category every 24 hours. The daily limit gets reset midnight West Coast (next reset in <date time="<?php echo $next_reset->format('U'); ?>" data-countdown="true">Less than 24 hours</date>). Voting period ends in <date time="<?php echo $end_date->format('U'); ?>" data-countdown="true"><?php echo $end_date->format('jS F Y \a\t G:i e'); ?></date>.</p>
	</div>
<?php } else { ?>
	<div class="content">
		<h2>Voting is now closed!</h2>
		<iframe width="780" height="438" src="http://www.youtube.com/embed/lyoOy4Hda0E" frameborder="0" allowfullscreen></iframe>
	</div>
<?php } ?>
	<div class="content">
		Categories Nerdgasm Of the Year and Best Swear-Evasion Euphemism In A Podcast have been dropped due to lack of nominations. A <abbr title="Cheeto's Honorary Consolation Prize">cheetorary</abbr> title of Best Swear-Evasion Euphemism In A Podcast goes to Justin Robert Young for "<a href="http://youtu.be/TqIv9hP6oUY?t=49m50s" target="_blank">Syncing your own Ford</a>".
	</div>
	<div id="voting-categories" class="content">
		<h2>Categories</h2>
		<ul id="categories" class="unstyled">
<?php foreach ($categories as $category) {
	if(isset($voted_today[$category["id"]]) && $voted_today[$category["id"]]) { ?>
				<li class="category btn btn-success clearfix" data-category="<?php echo $category["id"];?>">
					<div class="category-icon">
						<i class="icon-check"></i>
					</div>
<?php	} else { ?>
				<li class="category btn clearfix" data-category="<?php echo $category["id"];?>">
					<div class="category-icon">
						<i class="icon-trophy"></i>
					</div>
<?php	} ?>
					<div class="category-text">
						<div class="award">The <?php echo $category["award"]; ?> Award for</div>
						<div><?php echo $category["title"]; ?></div>
					</div>
					<small class="category-nominees">Nominees: <?php echo implode(", ", array_map(function($nominee) { return e($nominee["name"]); }, $category->nominees()->jsonSerialize())); ?></small>
				</li>
			<?php } ?>
		</ul>
	</div>
	<div id="voting" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="voting-label" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="voting-label">Modal header</h3>
		</div>
		<div class="modal-body" id="voting-content">
			<p>One fine body…</p>
		</div>
		<div class="modal-footer">
			<button href="#" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>
	<hr />

	<footer>
		<p>&copy; Chatrealm 2013</p>
	</footer>

</div> <!-- /container -->

<?php include "footer.php"; ?>