<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
	<h2>
		<?php zporg::printMainSectionCategoryTitle(); ?>
	</h2>
	<?php
	if (!is_NewsArticle() && $_zp_page == 1) {
		if (zporg::inNewsCategory('extensions')) {
			$newcat = new ZenpageCategory('extensions');
			echo $newcat->getDesc();
		} else if (zporg::inNewsCategory('user-guide')) {
			$newcat = new ZenpageCategory('user-guide');
			echo $newcat->getDesc();
		}
	}
	?>
	<?php
// single news article
	if (is_NewsArticle()) {
		?>
		<div class="entry">
			<h3 class="entrytitle">
				<?php printNewsTitle(); ?> <small class="articledate"><?php
				printNewsDate(); 
				zporg::printNewsLastChange();
				?> </small>
				<?php
				if (zporg::inNewsCategory('extensions')) {
					zporg::printExtensionStatusIcon();
				}
				?>
			</h3>
			<div class="entrymeta">
				<?php printNewsCategories(", ", gettext("Categories: "), "wp-category"); ?>

			</div>

			<ol id="toc" class="table_of_content_list"></ol>
			<?php zporg::printItemAuthorCredits(); ?>
			<div class="entrybody">
				<?php
				printCodeblock(1);
				// we need this for the automatic jquery table of contents
				?>
				<span id="entrybody">
					<?php
					printNewsContent();
					printCodeblock(2);
					?>
				</span>

				<?php zporg::printExtensionDownloadButton(); ?>

				<?php if (zporg::inNewsCategory("user-guide")) { 
					zporg::printLicenseNote();
				} ?>
				<p class="articlebox"><em>For questions and comments please use the <a href="http://www.zenphoto.org/support" title="Zenphoto forums" >forum</a> or discuss on the <a href="#stay-tuned">social networks</a>.</em></p>


			</div> <!--- class entrybody --->
			<div class="entrymeta">
				<?php
				if ($_zp_current_zenpage_news->inNewsCategory("extensions") || $_zp_current_zenpage_news->inNewsCategory("user-guide")) {
					printTags('links', 'Tags: ', 'wp-category', ', ', false, '', false);
				}
				?>
			</div>
			<?php 
			if(function_exists('printScriptlessSocialSharingButtons')) {
				printScriptlessSocialSharingButtons();
			} 
			if (zporg::inNewsCategory('extensions')) {
				zporg::printMoreByAuthorsLinks();
			}
			zporg::printRelatedItems(5, 'news');
			?>
		</div>

		<?php
// NEWS LOOP
	} else {
		
		printNewsPageListWithNav(gettext('next »'), gettext('« prev'), true, 'pagelist', true);

		while (next_news()):
			if (stickyNews()) {
				?>
				<div class="entry stickyarticle">
					<strong><small>FEATURED</small></strong>
					<?php
				} else {
					?>
					<div class="entry">
						<?php
					}
					// if a theme/screenshots news entry link to first image of album
					$title = getNewsTitle();
					$newslink = $_zp_current_zenpage_news->getLink();
					?>
					<h3 class="entrytitle"><a href="<?php echo html_encode($newslink); ?>" title="<?php echo html_encode(getBareNewsTitle()); ?>"><?php echo $title; ?></a> <small class="articledate"><?php
							printNewsDate();
							zp_printNewsLastChange();
							?></small>
						<?php
						// adding support status icon to extensions entries

						if ($_zp_current_zenpage_news->inNewsCategory('extensions')) {
							zporg::printExtensionStatusIcon();
						}
						?>
					</h3>

					<div class="entrymeta">
						<?php
						printNewsCategories(", ", gettext("Categories: "), "wp-category");
						?>
					</div>
					<?php
					if (!$_zp_current_zenpage_news->inNewsCategory('extensions') && !$_zp_current_zenpage_news->inNewsCategory('release') && !$_zp_current_zenpage_news->inNewsCategory('user-guide')) {
						zporg::printItemAuthorCredits();
					}
					?>
					<div class="entrybody">

						<?php
						// only print content on main news loop
						if (is_null($_zp_current_category)) {
							// news section stuff is not shortened
							if ($_zp_current_zenpage_news->inNewsCategory("news")) {
								printNewsContent(); // disabling shortening is not possible so we set it high!
								printCodeblock(2);
							} else {
								printNewsContent();
							}
						}
						?>
					</div>  <!--- class entrybody --->
				</div> <!--- class entry --->
				<?php
			endwhile;
			printNewsPageListWithNav(gettext('next »'), gettext('« prev'), true, 'pagelist', true);
		}
		?>

		<?php include('footer.php'); ?>
