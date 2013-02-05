<?php include "header.php"; ?>
<div class="container">
	<header>
		<h1>Chatrealm Podcast Awards 2012</h1>
	</header>
	<div class="content">
		<h2>Winners have been chosen!</h2>
		<p>Watch the award show extravaganza!</p>
		<iframe width="780" height="438" src="http://www.youtube.com/embed/lyoOy4Hda0E" frameborder="0" allowfullscreen></iframe>
	</div>
	<div class="content">
		<?php foreach ($resultdata as $category) { ?>
		<div class="results">
			<div class="page-header"><h1><small class="header-block">The <?php echo e($category["award"]); ?> award for</small><?php echo e($category["text"]); ?></h1></div>
			<?php foreach ($category["nominees"] as $pos => $nominee) {
				if($pos == 0) {?>
			<div class="vote">
				<img src="<?php echo $nominee["image"]; ?>" />
				<div class="vote-text">
					<div class="vote-btn"><?php echo round(($nominee["count"] / ($category["total"] ?: 1))*100, 4); ?>%</div>
					<h4><?php echo $nominee["text"]; ?></h4>
					<small><a href="<?php echo $nominee["url"]; ?>">[www]</a></small>
				</div>
			</div>
			Runners-up:
			<?php } else { ?><?php echo $pos == 1 ? "" : ", " ?> <a href="<?php echo $nominee["url"]; ?>"><?php echo $nominee["text"]; ?></a> (<?php echo round(($nominee["count"] / ($category["total"] ?: 1))*100, 4); ?>%)<?php } // endif
			} //endforeach ?>
		</div>
		<?php } ?>
	</div>
</div>
<?php include "footer.php"; ?>