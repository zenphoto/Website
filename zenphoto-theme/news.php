<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">
	<?php if (is_NewsArticle()) { ?>
		<div class="breadcrumb">
			<?php zporg::printMainSectionCategoryTitle(); ?>
		</div>
	<?php } else { ?>
		<h1 class="breadcrumb">
			<?php zporg::printMainSectionCategoryTitle(); ?>
		</h1>
	<?php } 
	if (!is_NewsArticle() && $_zp_page == 1) {
		if (zporg::inNewsCategory('extensions')) {
			$newcat = new ZenpageCategory('extensions');
			echo $_zp_current_category->getDesc();
			echo '<p class="buttons"><a href="/news/general-contributor-guidelines#themes-and-plugins"><strong>How to submit your extensions</strong></a></p>';
		} else if (zporg::inNewsCategory('user-guide')) {
			$newcat = new ZenpageCategory('user-guide');
			echo $newcat->getDesc();
		}
	}
	?>
	<?php
// single news article
	if (is_NewsArticle()) {
		$unpublished_note = '';
		if (!$_zp_current_zenpage_news->isPublic()) {
			$unpublished_note = ' <strong>[unpublished]</strong>';
		}
		?>
		<div class="entry">
			<h1 class="entrytitle">
				<?php printNewsTitle(); echo $unpublished_note; ?> <small class="articledate"><?php
				printNewsDate(); 
				zporg::printNewsLastChange();
				?> </small>
				<?php
				if (zporg::inNewsCategory('extensions')) {
					zporg::printExtensionStatusIcon();
				}
				?>
			</h1>
			<div class="entrymeta">
				<?php printNewsCategories(", ", gettext("Categories: "), "wp-category"); ?>
			</div>
			
			<div class="entrybody zp-clearfix">
				<?php
				zporg::printFeaturedImage($_zp_current_zenpage_news);
				printCodeblock(1);
				$content = zporg::generateTableOfContent(getNewsContent(), false, 'Content');
				echo $content['toc'];	
				zporg::printItemAuthorCredits(); 
				echo $content['content'];
				
				//printNewsContent();
				printCodeblock(2);
				zporg::printExtensionDownloadButton();
				
				if (zporg::inNewsCategory("user-guide")) { 
					zporg::printLicenseNote();
				} 
				if(!zporg::hasContentMacro($_zp_current_zenpage_news->get("content"), 'donate')) {
					echo zporg::getDonateCallToActionHTML();
				}
				?>
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
					if (!$_zp_current_zenpage_news->isPublic()) {
						$title .= ' <strong>[unpublished]</strong>';
					}
					$newslink = $_zp_current_zenpage_news->getLink();
					?>
					<h2 class="entrytitle"><a href="<?php echo html_encode($newslink); ?>" title="<?php echo html_encode(getBareNewsTitle()); ?>"><?php echo $title; ?></a> <small class="articledate"><?php
							printNewsDate();
							zporg::printNewsLastChange();
							?></small>
						<?php
						// adding support status icon to extensions entries

						if ($_zp_current_zenpage_news->inNewsCategory('extensions')) {
							zporg::printExtensionStatusIcon();
						}
						?>
					</h2>

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
						//if (function_exists('printSizedFeaturedImage')) {
						//	printSizedFeaturedImage($_zp_current_zenpage_news, '', null, 150, 150,  150, 150, NULL, NULL, 'entrybody_featuredimage-thumb', NULL, true, NULL, false);
						//}
						
						// only print content on main news loop
						if (is_null($_zp_current_category)) {
							$content = strip_tags(getNewsContent(250, ' (…)', false));
							// news section stuff is not shortened
							if ($_zp_current_zenpage_news->inNewsCategory("news")) {
								echo $content; 
								printCodeblock(2);
							} else {
								echo $content; 
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
