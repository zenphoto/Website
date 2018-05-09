<?php include('header.php');
setOption('thumb_crop_width', 40, false);
setOption('thumb_crop_height', 40, false);
?>
<?php include('sidebar.php'); ?>
<div id="content">

		<h2>
			<?php printParentBreadcrumb('',' | ','');
			if($_zp_current_album->name === "showcase") {
				printAlbumBreadcrumb('', '');
			} 
			?>
		</h2>
		 
	   <?php
    if($_zp_current_album->name != "showcase") {
    	zporg::printNextPrevAlbumLinkFirstImage();
    }
    ?>	
	<div class="imgnav theme">
		<?php if (hasPrevImage()) { ?>
			<div id="imgprev"><a href="<?php echo htmlspecialchars(getPrevImageURL());?>" title="<?php echo gettext("Previous Image"); ?>">« <?php echo gettext("prev"); ?></a></div>
		<?php } else { ?>
			<div id="imgprev-inactive"></div>
		<?php }
		if (hasNextImage()) { ?>
			<div id="imgnext"><a href="<?php echo htmlspecialchars(getNextImageURL());?>" title="<?php echo gettext("Next Image"); ?>"><?php echo gettext("next"); ?>»</a></div>
		<?php } else { ?>
			<div id="imgnext-inactive"></div>
		<?php } ?>
	</div> 
	<?php 
	$iconclass = '';
	if($_zp_current_album->name == 'showcase') {
		$iconclass = zporg::getShowcaseTypeIconClass();
	}
	if($zp_getParentAlbumName == "theme") {
		 $iconclass = zporg::getThemeStatusIconClass();
	}
	?>
	<h3 class="entrytitle imagetitle<?php echo $iconclass; ?>">
	<?php 
	if($zp_getParentAlbumName == 'theme' || $zp_getParentAlbumName == 'screenshots') {
		 printAlbumTitle(); echo ': '; 
	}
	printImageTitle(true); 
	echo " (".imageNumber()."/".getNumImages().")"; 
	?>
	</h3>
 		<div id="image">
		<?php if(isImagePhoto()) { ?>
		<a href="<?php echo getUnprotectedImageURL(); ?>" class="colorbox"><?php printCustomSizedImageMaxSpace(getBareImageTitle(),560,1000,NULL,NULL,false); ?></a>
		<?php } else { ?>
		<?php printCustomSizedImage(getBareImageTitle(),560,700); ?>
		<?php } ?>
  	</div>
  	
  
	<?php
		if (function_exists('printThumbNav')) {
			//printThumbNav(6,50,50,50,50,FALSE);
 		}
	?>
	<?php zporg::printItemAuthorCredits(); ?>	
	<div class="entrybody">
<?php
	$customdata = '';
	
	if($zp_getParentAlbumName == "theme" || $zp_getParentAlbumName == "screenshots" || $_zp_current_album->name == "showcase") {
 	  if ($zp_getParentAlbumName == "theme") {
 	  	printAlbumDesc();
 	  }
 	 	if($zp_getParentAlbumName == "screenshots") {
 	 		printImageDesc();
 	 		echo "<hr style='clear: both' />";
   			echo '<p><strong>Date added: </strong>'.getImageDate().'</p>';
 	 		echo '<hr />';
 	 	} else if ($_zp_current_album->name == "showcase") {
 	 		echo '<div class="buttons visitsite">'; printImageDesc(); echo "</div>";
   		echo "<br style='clear: left' />";
   		echo '<p><strong>Date added: </strong>'.getImageDate().'</p>';
 	 	} else if($zp_getParentAlbumName == "theme"){
 	 		printImageDesc(); 
 	 		zporg::printPluginsupportTags();
 	 		if(!$_zp_current_album->hasTag('theme-officially-supported')) {
 	 			echo '<p><strong>Date added: </strong>'.getAlbumDate().'</p>';
 	 		}
 	 	}
 	 	?>
 	 	<?php
  	zporg::printThemeDownloadButton();
  	echo '<br clear="left" />';
 	 	if (function_exists('printRating') && ($zp_getParentAlbumName == "theme" || $_zp_current_album->name == "showcase")) {
 	 		echo "<hr v/>";
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
  }
  ?></div>
  <hr style="clear:left" />
<?php 
if(function_exists('printScriptlessSocialSharingButtons')) {
	printScriptlessSocialSharingButtons();
}  
if ($_zp_current_album->name != "showcase") {
	zporg::printMoreByAuthorsLinks();
}
?>
<br />

<?php include('footer.php'); ?>