<?php include('header.php'); ?>
<div id="sidebar">
<?php printSearchForm();	
$zp_getParentAlbumName = zp_getParentAlbumName();
if($zp_getParentAlbumName != "theme") { ?>
<hr />
<?php echo getAlbumDesc(); 
}
if($zp_getParentAlbumName == 'theme' || $_zp_current_album->name == 'theme') {
	zp_printThemeStatusIconList();
}
if($_zp_current_album->name == 'showcase') {
	zp_printShowcaseTypeIconList();
}
if($_zp_current_album->getNumImages() > 0) {
	echo '<hr />';
	printSlideShowLink(gettext('View Slideshow'));
} 
zp_printSidebarBoxes(); ?>
<hr />
<?php
if($_zp_current_album->name == "theme") {
	echo "<h2>Latest themes</h2>";
	$latest = getAlbumStatistic(4,'latest', 'theme',true);
	zp_printGalleryStatistics($latest,'date',true);
	echo "<hr />";
	echo "<h2>Top rated themes</h2>";
	$toprated = getAlbumStatistic(4,'toprated', 'theme',true);
	zp_printGalleryStatistics($toprated,'rating',true);
}
if ($_zp_current_album->name ==="showcase") {
	echo "<h2>Top rated sites</h2>";
	$toprated = getImageStatistic(4,'toprated', 'showcase',false);
	zp_printGalleryStatistics($toprated,'rating');
}
if($_zp_current_album->name === "screenshots") {
} ?>
</div>
<div id="content">

<h2>
<?php
	printParentBreadcrumb('','','');

if($zp_getParentAlbumName != 'theme' && $zp_getParentAlbumName != 'screenshots') {
	printAlbumTitle();
}
?>
<?php
$sortorder = NULL;
if($_zp_current_album->name === "theme") {
	echo " (".getNumAlbums().")";
	$sortorder = 'title';
}
if($_zp_current_album->name === "showcase") {
	echo " (".getNumImages().")";
	$sortorder = NULL;
}
?>
</h2>
 <?php
  if($_zp_current_album->name != "showcase" && $_zp_current_album->name != "screenshots" && $_zp_current_album->name != "theme" && $zp_getParentAlbumName != "screenshots") {
   	zp_printNextPrevAlbumLinkFirstImage();
   }
 ?>
<?php

if($zp_getParentAlbumName == 'theme' || $zp_getParentAlbumName == "screenshots") {
	$iconclass = '';
	if($_zp_current_album->name == 'showcase') {
		$iconclass = zp_getShowcaseTypeIconClass();
	}
	if($zp_getParentAlbumName == "theme") {
		 $iconclass = zp_getThemeStatusIconClass();
	}
	?>
	<h3 class="entrytitle imagetitle<?php echo $iconclass; ?>">
	<?php printAlbumTitle(); ?>
	</h3>
	<?php
}
if(getTotalPages(false) != 1) {
	printPageListWithNav(gettext('« prev'),gettext('next »'));
}
?>
<div id="albums"><?php
if($_zp_current_album->name == "screenshots") {
	$albumcss = "album";
} else {
	$albumcss = "album";
}
?>
<?php
$count = '';
while (next_album(false,$sortorder)):
	$count++;
	if($count == 2) {
		$class = ' rightthumb';
		$count = '';
	} else {
		$class = ' leftthumb';
	}

?>
<div class="album<?php echo $class; ?>">
	<?php
	if($_zp_current_album->getNumImages() != 0) {
		$first = $_zp_current_album->getImage(0);
		$albumurl = $first->getImageLink();
	} else {
		$albumurl = getAlbumLinkURL();
	}
	?>
<div class="thumb">
<a href="<?php echo $albumurl; ?>" title="View album: <?php echo getAlbumTitle();?>">
	<?php printCustomAlbumThumbImage(getBareAlbumTitle(), NULL, 255, 128, 255, 128, NULL,NULL,"thumbnail",NULL,TRUE,false); ?>
	</a>
</div>
<div class="albumdesc">
<h3 class="entrytitle">
<a href="<?php echo $albumurl ;?>"	title="View album: <?php echo getAlbumTitle();?>"><?php echo shortenContent(getAlbumTitle(),20,'(...)'); ?></a>
		<?php
		if (getNumImages() != 0) {
			echo "<small>(".getNumImages().")</small>";
		}
		?>
</h3><?php zp_printThemeStatusIcon(); ?>

<?php
if($zp_getParentAlbumName === "theme") {
	echo "<br />Rating: ".getRating($_zp_current_album);
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
	if($count == 2) {
		$class = ' rightthumb';
		$count = '';
	} else {
		$class = ' leftthumb';
	}

	?>
	<div class="album<?php echo $class; ?>">
		<div class="thumb">
			<a	href="<?php echo html_encode(getImageLinkURL());?>" title="<?php echo getImageTitle();?>"><?php printCustomSizedImage(getBareImageTitle(), NULL, 255, 128, 255, 128,NULL,NULL,"thumbnail",NULL,true,false); ?></a>
		</div>
		<div class="albumdesc">
			<h3 class="entrytitle">
			<a href="<?php echo html_encode(getImageLinkURL());?>" title="<?php echo getImageTitle();?>"><?php echo shortenContent(getImageTitle(),20,'(...)'); ?>
			</a>
			<?php zp_printShowcaseTypeIcon(); ?></h3>
			<br />
			<?php
			if($_zp_current_album->name === "showcase") {
				echo "Rating: ".getRating($_zp_current_image);
				//printImageRating();
			}
			?>
		</div>
</div>
<?php endwhile; ?>
</div>
<br clear="left" />
<?php

	if(getTotalPages(false) != 1) {
		printPageListWithNav(gettext('« prev'),gettext('next »'));
	}
?>
<br />
<?php
if($zp_getParentAlbumName == "theme") { 
	echo "<p>";
	$theme = str_replace(' ', '_', $_zp_current_album->getTitle());
	echo "</p>";
	echo '<div class="entrybody">';
	echo getAlbumDesc(); 
	echo '</div>';
}

if($zp_getParentAlbumName == "theme") {
	zp_printPluginsupportTags();
	if(!$_zp_current_album->hasTag('theme-officially-supported')) {
 	 	echo '<hr /><p><strong>Date added: </strong>'.getAlbumDate().'</p>';
 	}
}
 	 	if (function_exists('printRating') && ($zp_getParentAlbumName == "theme" || $_zp_current_album->name == "showcase")) {
 	 		echo "<hr />";
 	 		echo '<div class="rating" style="float: left; width:400px; height:50px;">';
 	 		if($zp_getParentAlbumName == "theme") {
 	 			$itemtobj_to_rate = $_zp_current_album;
 	 		} else if ($_zp_current_album->name == "showcase") {
 	 			$itemtobj_to_rate = $_zp_current_image;
 	 		}	
 	 		printRating(3,$itemtobj_to_rate,true) ;
 	 		echo '</div>';
 	 	}
 	 	if (function_exists('printSlideShowLink')) {
 	 		echo '<div class="buttons">'; 
 	 		printSlideShowLink(gettext('View Slideshow')); 
 	 		echo '</div>';
 	 	}
 	 	  echo '<br clear="left" />';
zp_printThemeDownloadButton();

if ($_zp_current_album->name != "showcase") {
	zp_printMoreByAuthorsLinks();
}
?>



<?php include('footer.php'); ?>