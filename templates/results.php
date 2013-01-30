<?php include "header.php"; ?>

<div class="content">
<?php foreach ($resultdata as $category) { ?>
	<div class="page-header"><h1><?php echo e($category["text"]); ?> <small>The <?php echo e($category["award"]); ?> award for</small></h1></div>
	<ul>
<?php foreach ($category["nominees"] as $nominee) { ?>
		<li><a href="<?php echo $nominee["url"]; ?>"><?php echo $nominee["text"]; ?></a> - <?php echo $nominee["count"]; ?> (<?php echo round(($nominee["count"] / ($category["total"] ?: 1))*100, 4); ?>%)</li>
<?php } ?>
	</ul>
<?php } ?>
</div>


<?php include "footer.php"; ?>