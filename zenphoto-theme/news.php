<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
	<h2>
		<?php zp_printMainSectionCategoryTitle(); ?>
	</h2>
	<?php
	if (!is_NewsArticle() && $_zp_page == 1) {
		if (zp_inNewsCategory('extensions')) {
			$newcat = new ZenpageCategory('extensions');
			echo $newcat->getDesc();
		} else if (zp_inNewsCategory('user-guide')) {
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
				zp_printNewsLastChange();
				?> </small>
				<?php
				if (zp_inNewsCategory('extensions')) {
					zp_printExtensionStatusIcon();
				}
				?>
			</h3>
			<div class="entrymeta">
				<?php printNewsCategories(", ", gettext("Categories: "), "wp-category"); ?>

			</div>

			<ol id="toc" class="table_of_content_list"></ol>
			<?php zp_printItemAuthorCredits(); ?>
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

				<?php zp_printExtensionDownloadButton(); ?>

				<?php if (zp_inNewsCategory("user-guide")) { ?>
					<p class="articlebox license"><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a>This text by <a xmlns:cc="http://creativecommons.org/ns#" href="www.zenphoto.org" property="cc:attributionName" rel="cc:attributionURL">www.zenphoto.org</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.</p>
				<?php } ?>
				<p class="articlebox"><em>For questions and comments please use the <a href="http://www.zenphoto.org/support" title="Zenphoto forums" >forum</a> or discuss on the <a href="#stay-tuned">social networks</a>.</em></p>


			</div> <!--- class entrybody --->
			<div class="entrymeta">
				<?php
				if ($_zp_current_zenpage_news->inNewsCategory("extensions") || $_zp_current_zenpage_news->inNewsCategory("user-guide")) {
					printTags('links', 'Tags: ', 'wp-category', ', ', false, '', false);
				}
				?>
			</div>
			<?php zp_printAddthis(); ?>

			<?php
			if (zp_inNewsCategory('extensions')) {
				zp_printMoreByAuthorsLinks();
			}
			zp_printRelatedItems(5, 'news');
			?>
		</div>

		<?php
// NEWS LOOP
	} else {
		printNewsPageListWithNav(gettext('next »'), gettext('« prev'), true, 'pagelist', true);

		/* if(is_null($_zp_current_category)) {
		  $sortorder = 'date';
		  $sortdirection = 'desc';
		  } else if (!zp_inNewsCategory("news")) {
		  $sortorder = 'title';
		  $sortdirection = 'asc';
		  } else {
		  $sortorder ='';
		  $sortdirection ='';
		  } */

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
							zp_printExtensionStatusIcon();
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
						zp_printItemAuthorCredits();
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
