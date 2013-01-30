<?php include "header.php"; ?>
<div class="container">
	<header>
		<h1>Chatrealm Podcast Awards 2012</h1>
	</header>

<?php
if($open) { ?>
	<div class="content">
		<h2>Nomination period is now open for <span class="days"><?php echo $time_left->format("%a")+1; ?></span> more day(s)</h2>
		<p>You can nominate one entry to each category every 24 hours. The daily limit gets reset midnight GMT (next reset in <date time="<?php echo $next_reset->format('U'); ?>" data-countdown="true">Less than 24 hours</date>). Nomination period ends in <date time="<?php echo $end_date->format('U'); ?>" data-countdown="true"><?php echo $end_date->format('jS F Y \a\t G:i e'); ?></date>.</p>
	</div>
	<div class="accordion" id="vote-steps">
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" id="vote-step1-header">
					Step #1 - Category
				</a>
			</div>
			<div id="vote-step1" class="accordion-body">
				<div class="accordion-inner">
					<p>Choose a category to nominate in. You have already nominated today in categories with a <i class="icon-ok"></i> next to them.</p>
<?php } else { ?>
	<div class="content">
		<h2>Nominations are now closed!</h2>
		<p>Voting will open soon!</p>
	</div>
	<div class="accordion" id="vote-steps">
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" id="vote-step1-header">
					Categories
				</a>
			</div>
			<div id="vote-step1" class="accordion-body">
				<div class="accordion-inner">
<?php } ?>
					<ul class="categories unstyled">
<?php
foreach ($categories as $key => $texts) {
	if(!$open) { ?>
						<li class="category btn clearfix">
							<div class="category-icon">
								<i class="icon-trophy"></i>
							</div>
<?php	} elseif(isset($nominated_today[$key]) && $nominated_today[$key]) { ?>
						<li class="category btn btn-success disabled clearfix">
							<div class="category-icon">
								<i class="icon-check"></i>
							</div>
<?php	} else { ?>
						<li class="category btn clearfix" data-category="<?php echo $key;?>" data-text="<?php echo e($texts[1]); ?>">
							<div class="category-icon">
								<i class="icon-trophy"></i>
							</div>
<?php	} ?>
							<div class="category-text">
								<div class="award">The <?php echo e($texts[0]); ?> Award for</div>
								<div><?php echo e($texts[1]); ?></div>
							</div>
						</li>
<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<div id="vote-step2" class="accordion-group hidden">
			<div class="accordion-heading">
				<a class="accordion-toggle">
					Step #2 - Nomination
				</a>
			</div>
			<div id="vote-nomination" class="accordion-body">
				<div class="accordion-inner">
					<form id="nomination-form" class="form form-horizontal" method="POST" action="<?php echo url("nominate"); ?>" accept-charset="UTF-8">
						<div class="control-group">
							<label class="control-label">Category</label>
							<div class="controls">
								<input type="hidden" id="category-id" />
								<div id="category-text"></div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="category-title">Title / URL</label>
							<div class="controls">
								<input type="text" id="category-title" class="input-block-level" placeholder="Title...">
								<input type="text" id="category-url" class="input-block-level" placeholder="URL">
								<span class="help-block">Required to fill at least one of the fields</span>
							</div>
						</div>
						<div class="form-actions">
							<button id="nominate-submit" type="submit" class="btn btn-primary">Nominate</button>
							<button id="nominate-back" type="button" class="btn">Back</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<footer>
		<p>&copy; Chatrealm 2013</p>
	</footer>

</div> <!-- /container -->

<?php include "footer.php"; ?>