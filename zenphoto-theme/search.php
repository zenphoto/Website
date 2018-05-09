<?php include('header.php'); ?>
<?php zporg::printZDSearchToggleJS(); ?>
<?php include('sidebar.php'); ?>
<div id="content">

	<h2>Search results</h2>
	<?php
	$numimages = getNumImages();
	$numalbums = getNumAlbums();
	$total = $numimages + $numalbums;
	$zenpage = extensionEnabled('zenpage');
	if ($zenpage && !isArchive()) {
		$numpages = getNumPages();
		$numnews = getNumNews();
		$total = $total + $numnews + $numpages;
	} else {
		$numpages = $numnews = 0;
	}
	if ($total == 0) {
		$_zp_current_search->clearSearchWords();
	}
	$searchwords = getSearchWords();
	$searchdate = getSearchDate();
	if (!empty($searchdate)) {
		if (!empty($searchwords)) {
			$searchwords .= ": ";
		}
		$searchwords .= $searchdate;
	}
	if ($total > 0) {
		?>
		<p>
			<?php
			printf(ngettext('%1$u Hit for <em>%1$s %2$s</em>', '%1$u Hits for <em>%1$s %2$s</em>', $total), $total, html_encode($searchwords));
			?>
		</p>

		<?php
		printPageListWithNav("« " . gettext("prev"), gettext("next") . " »");
	}
	if ($_zp_page == 1) { //test of zenpage searches
		if ($numpages > 0) {
			$number_to_show = 5;
			$c = 0;
			?>
			<h3 class="searchresulttitle"><?php printf(gettext('Pages (%s)'), $numpages); ?> <small><?php zporg::printZDSearchShowMoreLink("pages", $number_to_show); ?></small></h3>
			<ul class="searchresults">
				<?php
				while (next_page()) {
					$c++;
					?>
					<li<?php zporg::printZDToggleClass('pages', $c, $number_to_show); ?>>
						<h4 class="entrytitle"><?php printPageURL(); ?></h4>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		}
		if ($numnews > 0) {
			$number_to_show = 5;
			$c = 0;
			?>
			<h3 class="searchresulttitle"><?php printf(gettext('Articles (%s)'), $numnews); ?> <small><?php zporg::printZDSearchShowMoreLink("news", $number_to_show); ?></small></h3>
			<ul class="searchresults">
				<?php
				while (next_news()) {
					$c++;
					?>
					<li<?php zporg::printZDToggleClass('news', $c, $number_to_show); ?>>
						<h4 class="entrytitle"><?php printNewsURL(); ?> <small class="articledate"><?php printNewsDate(); ?></small>
							<?php
							if ($_zp_current_zenpage_news->inNewsCategory("extensions")) {
								zporg::printExtensionStatusIcon();
							}
							?>
						</h4>
						<div class="entrymeta">
			<?php printNewsCategories(", ", gettext("Categories: "), "wp-category"); ?>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		}
	}
	?>
	<br />

	<h2 class="searchresulttitle">
		<?php
		if (getOption('search_no_albums')) {
			if (!getOption('search_no_images') && ($numpages + $numnews) > 0) {
				printf(gettext('Images (%s)'), $numimages);
			}
		} else {
			if (getOption('search_no_images')) {
				if (($numpages + $numnews) > 0) {
					printf(gettext('Albums (%s)'), $numalbums);
				}
			} else {
				printf(gettext('Albums (%1$s) &amp; Images (%2$s)'), $numalbums, $numimages);
			}
		}
		?>
	</h2>
		<?php if (getNumAlbums() != 0) { ?>
		<div id="albums">
			<?php
			$count = '';
			while (next_album()):
				$count++;
				if ($count == 2) {
					$class = ' rightthumb';
					$count = '';
				} else {
					$class = ' leftthumb';
				}
				?>
				<div class="album<?php echo $class; ?>">
					<div class="thumb">
						<?php
						$firstimage = $_zp_current_album->getImages(); // get the first image of the current album in the loop
						if (getNumImages() === 0) { // to prevent tying to jump to the first image of an album with only subalbums
							$albumlinkurl = getAlbumURL();
						} else {
							$albumlinkurl = getAlbumURL() . $firstimage[0] . IM_SUFFIX;
						}
						?>
						<a href="<?php echo html_encode($albumlinkurl); ?>" title="<?php echo gettext('View album:'); ?> <?php echo getBareAlbumTitle(); ?>"><?php printCustomAlbumThumbImage(getBareAlbumTitle(), NULL, 255, 128, 255, 128, NULL, NULL, "thumbnail", NULL, TRUE, false); ?></a>
					</div>
					<div class="albumdesc">
						<h4 class="entrytitle"><a href="<?php echo html_encode($albumlinkurl); ?>" title="<?php echo gettext('View album:'); ?> <?php echo getBareAlbumTitle(); ?>"><?php echo shortenContent(getAlbumTitle(), 20, '(...)'); ?></a>
							<?php
							if (zporg::getParentAlbumName() === 'theme') {
								echo '<small>(Theme)</small>';
							}
							?>
						</h4>
		<?php zporg::printThemeStatusIcon(); ?>
					</div>
				</div>
		<?php endwhile; ?>
		</div>
	<?php } ?>
		<?php if (getNumImages() > 0) { ?>
		<div id="images">
			<?php
			$count = '';
			while (next_image(false)):
				$count++;
				if ($count == 2) {
					$class = ' rightthumb';
					$count = '';
				} else {
					$class = ' leftthumb';
				}
				?>
				<div class="album<?php echo $class; ?>">
					<div class="thumb">
						<a href="<?php echo htmlspecialchars(getImageURL()); ?>" title="<?php echo getImageTitle(); ?>"><?php printCustomSizedImage(getBareImageTitle(), NULL, 255, 128, 255, 128, NULL, NULL, "thumbnail", NULL, true, false); ?></a>
					</div>
					<div class="albumdesc">
						<h4 class="entrytitle"><a href="<?php echo htmlspecialchars(getImageURL()); ?>" title="<?php echo getImageTitle(); ?>"><?php echo shortenContent(getImageTitle(), 20, '(...)'); ?>
								<?php
								$albumname = $_zp_current_image->album;
								$parent = $albumname->getParent();
								if (is_object($parent) && $parent->name == 'theme') {
									echo '<small>(Theme)</small>';
								}
								if ($albumname->name == 'showcase') {
									echo '<small>(Showcase)</small>';
								}
								?>
						</h4><?php zporg::printShowcaseTypeIcon(); ?></a>
					</div>
				</div>
	<?php endwhile; ?>
		</div>
		<br clear="all" />
	<?php } ?>
	<?php
	//if (function_exists('printSlideShowLink')) printSlideShowLink(gettext('View Slideshow'));
	if ($total == 0) {
		echo "<p>" . gettext("Sorry, no matches found. Try refining your search.") . "</p>";
	}

	printPageListWithNav("« " . gettext("prev"), gettext("next") . " »");
	?>

<?php include('footer.php'); ?>