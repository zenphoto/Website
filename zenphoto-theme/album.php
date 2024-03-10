<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<div id="content">

	<?php if($_zp_current_album->name == 'hosting') { ?>
		<h1 class="pagetitle"><?php printAlbumTitle(); ?></h1>
		<?php zporgSponsors::printAds(); ?>
		<br clear="all" />
	<?php } ?>

	<h1 class="breadcrumb">
		<?php
		printParentBreadcrumb('', '', '');

		if ($zp_getParentAlbumName != 'theme' && $zp_getParentAlbumName != 'screenshots') {
			printAlbumTitle();
		}
		?>
		<?php
		if ($_zp_current_album->name === "theme") {
			echo " (" . getNumAlbums() . ")";
			$_zp_current_album->setSortType('title', 'album');
		}
		if ($_zp_current_album->name === "showcase") {
			echo " (" . getNumImages() . ")";
		}
		?>
	</h1>
	<?php
	if ($zp_getParentAlbumName != "theme" && $_zp_page == 1) {
		echo getAlbumDesc();
	}
	?>
	<?php
	if ($_zp_current_album->name != "showcase" && $_zp_current_album->name != "screenshots" && $_zp_current_album->name != "theme" && $zp_getParentAlbumName != "screenshots") {
		zporg::printNextPrevAlbumLinkFirstImage();
	}
	?>
	<?php
	if ($zp_getParentAlbumName == 'theme' || $zp_getParentAlbumName == "screenshots") {
		$iconclass = '';
		if ($_zp_current_album->name == 'showcase') {
			$iconclass = zporg::getShowcaseTypeIconClass();
		}
		if ($zp_getParentAlbumName == "theme") {
			$iconclass = zporg::getThemeStatusIconClass();
		}
		?>
		<h2 class="entrytitle imagetitle<?php echo $iconclass; ?>">
			<?php printAlbumTitle(); ?>
		</h2>
		<?php
		if ($zp_getParentAlbumName == "theme") {
			zporg::printItemAuthorCredits();
			echo '<div class="entrybody">';
			echo getAlbumDesc();
			zporg::printPluginsupportTags();
			if(!$_zp_current_album->hasTag('theme-officially-supported')) {
				echo '<p><strong>Date added: </strong>'.getAlbumDate().'</p>';
			}
			zporg::printThemeDownloadButton();
			echo '<br clear="left" />';
			echo '</div>';
		}
	}
	if (getTotalPages(false) != 1) {
		printPageListWithNav(gettext('« prev'), gettext('next »'));
	}
	?>
	<div id="albums"><?php
		if ($_zp_current_album->name == "screenshots") {
			$albumcss = "album";
		} else {
			$albumcss = "album";
		}
		?>
		<?php
		$count = '';
		while (next_album(false)):
			$count++;
			if ($count == 2) {
				$class = ' rightthumb';
				$count = '';
			} else {
				$class = ' leftthumb';
			}
			?>
			<div class="album<?php echo $class; ?>">
				<?php $albumurl = getAlbumURL(); ?>
				<div class="thumb">
					<a href="<?php echo $albumurl; ?>" title="View album: <?php echo getAlbumTitle(); ?>">
						<?php printCustomAlbumThumbImage(getBareAlbumTitle(), NULL, 255, 128, 255, 128, NULL, NULL, "thumbnail", NULL, TRUE, false); ?>
					</a>
				</div>

				<div class="albumdesc">
					<h2 class="entrytitle">
						<a href="<?php echo $albumurl; ?>"	title="View album: <?php echo getAlbumTitle(); ?>"><?php echo shortenContent(getAlbumTitle(), 20, '(...)'); ?></a>
						<?php
						if (getNumImages() != 0) {
							echo "<small>(" . getNumImages() . ")</small>";
						}
						?>
					</h2><?php zporg::printThemeStatusIcon(); ?>

					<?php
					if ($zp_getParentAlbumName === "theme") {
						echo "<br />Rating: " . getRating($_zp_current_album);
					}
					?>
				</div>

			</div>
		<?php endwhile; ?>
	</div>

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
					<a	href="<?php echo html_encode(getImageURL()); ?>" title="<?php echo getImageTitle(); ?>"><?php printCustomSizedImage(getBareImageTitle(), NULL, 255, 128, 255, 128, NULL, NULL, "thumbnail", NULL, true, false); ?></a>
				</div>
				<div class="albumdesc">
					<h2 class="entrytitle">
						<a href="<?php echo html_encode(getImageURL()); ?>" title="<?php echo getImageTitle(); ?>"><?php echo shortenContent(getImageTitle(), 20, '(...)'); ?>
						</a>
						<?php zporg::printShowcaseTypeIcon(); ?></h2>
					<br />
					<?php
					if ($_zp_current_album->name === "showcase") {
						echo "Rating: " . getRating($_zp_current_image);
					}
					?>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
	<br clear="left" />
	<?php
	if (getTotalPages(false) != 1) {
		printPageListWithNav(gettext('« prev'), gettext('next »'));
	}
	?>
	<br />
	<?php
	if (function_exists('printRating') && ($zp_getParentAlbumName == "theme" || $_zp_current_album->name == "showcase")) {
		echo "<hr />";
		echo '<div class="rating" style="float: left; width:400px; height:50px;">';
		if ($zp_getParentAlbumName == "theme") {
			$itemtobj_to_rate = $_zp_current_album;
		} else if ($_zp_current_album->name == "showcase") {
			$itemtobj_to_rate = $_zp_current_image;
		}
		printRating(3, $itemtobj_to_rate, true);
		echo '</div>';
	}
	if (function_exists('printSlideShowLink')) {
		echo '<div class="buttons">';
		printSlideShowLink(gettext('View Slideshow'));
		echo '</div>';
	}
	echo '<br clear="left" />';
	zporg::printThemeDownloadButton();

	if ($_zp_current_album->name != "showcase") {
		zporg::printMoreByAuthorsLinks();
	}
	?>



	<?php include('footer.php'); ?>