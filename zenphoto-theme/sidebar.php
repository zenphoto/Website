<div id="sidebar">
	<?php
	printSearchForm();
	switch ($_zp_gallery_page) {

		case 'news.php':
			if (zp_inNewsCategory('extensions')) {
				zp_printExtensionStatusIconList();
				zp_printSubCategories('extensions');
			} else if (zp_inNewsCategory('user-guide')) {
				zp_printSubCategories('user-guide');
			} else {
				$catoption = 'list';
				$newsindex = gettext("All news");
				zp_printNewsCategoryFoldout();
				//printAllNewsCategories($newsindex,TRUE,"newscategories","menu-active",true,"submenu","menu-active",$catoption);
			}
			zp_printSidebarBoxes();
			if (zp_inNewsCategory('extensions')) {
				if (function_exists('printAllTagsFromZenpage')) {
					?>
					<hr />
					<h2 class="latestadditions">Popular tags</h2>
					<?php printAllTagsFromZenpage('news', '', 'taglist', false, true, 0.5, 3, 5, 50); ?>
					<?php
				} else {
					printAllTagsAs('cloud', 'taglist', 'abc', TRUE, TRUE, 2, 50, 5, NULL, 0.6);
				}
				?>
				<br />
				<?php
			}
			if (zp_inNewsCategory("user-guide")) {
				?>
				<hr />
				<h2 class="latestadditions">Latest additions and updates</h2>
				<?php
				zp_printLatestNews(3, 'none', 'user-guide');
			} else if (zp_inNewsCategory("extensions")) {
				?>
				<hr />
				<h2 class="latestadditions">Latest additions</h2>
				<?php
				zp_printLatestNews(3, 'none', 'extensions');
			}
			break;

		case 'pages.php':
			$parent = $_zp_current_zenpage_page->getParentid();
			$subpages = $_zp_current_zenpage_page->getPages();
			$pageid = $_zp_current_zenpage_page->getID();
			$parents = $_zp_current_zenpage_page->getParents($pageid);
			//echo '<pre>'; print_r($parents).'<br />'; print_r($subpages); echo '</pre>';
			if (!is_null($parent) || $subpages) {
				?>
				<!-- <hr /> -->
				<?php //printPageMenu('omit-top','','','','active','',1); ?>
				<?php
			}
			if (in_array('all-contributors', $parents) || $_zp_current_zenpage_page->getTitlelink() == 'all-contributors') {
				zp_printThemeStatusIconList();
				zp_printExtensionStatusIconList();
			}
			zp_printSidebarBoxes();
			break;

		case 'album.php':
		case 'image.php':
			$zp_getParentAlbumName = zp_getParentAlbumName();
			switch ($_zp_gallery_page) {

				case 'album.php':
					if ($_zp_current_album->name == 'sponsors') {
						echo '<hr />';
						printAlbumDesc();
					}
					if ($zp_getParentAlbumName == 'theme' || $_zp_current_album->name == 'theme') {
						zp_printThemeStatusIconList();
					}
					if ($_zp_current_album->name == 'showcase') {
						zp_printShowcaseTypeIconList();
					}
					if ($_zp_current_album->getNumImages() > 0) {
						echo '<hr />';
						printSlideShowLink(gettext('View Slideshow'));
					}
					zp_printSidebarBoxes();
					?>
					<hr />
					<?php
					if ($_zp_current_album->name == "theme") {
						echo "<h2>Latest themes</h2>";
						$latest = getAlbumStatistic(4, 'latest-date', 'theme', true);
						zp_printGalleryStatistics($latest, 'date', true);
						echo "<hr />"; 
						echo "<h2>Top rated themes</h2>";
						$toprated = getAlbumStatistic(4, 'toprated', 'theme', true);
						zp_printGalleryStatistics($toprated, 'rating', true); 
					}
					if ($_zp_current_album->name === "showcase") {
						echo "<h2>Top rated sites</h2>";
						$toprated = getImageStatistic(4, 'toprated', 'showcase', false);
						zp_printGalleryStatistics($toprated, 'rating');
					}
					if ($_zp_current_album->name === "screenshots") {

					}
					break;

				case 'image.php':
					if ($zp_getParentAlbumName == "theme") {
						zp_printThemeStatusIconList();
					}
					if ($_zp_current_album->name == 'showcase') {
						zp_printShowcaseTypeIconList();
					}
					zp_printSidebarBoxes();
					break;
			}
			break;

		case 'search.php':
			?>
			<hr />
			<h2 class="latestadditions">Popular tags</h2>
			<?php
			if (function_exists('printAllTagsFromZenpage')) {
				?>
				<p><?php printAllTagsFromZenpage('news', '', 'taglist', false, true, 0.5, 2, 5, 50); ?></p>
				<br style="clear:both" />
				<?php
			} else {
				printAllTagsAs('cloud', 'taglist', 'abc', TRUE, TRUE, 2, 50, 10, NULL, 0.8);
			}
			?>
			<hr />
			<?php
			zp_printSidebarBoxes();
			break;

		default:
			?>
			<hr />
		<?php
		zp_printSidebarBoxes();
		break;
}
?>

</div>
