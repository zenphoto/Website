<?php include('header.php'); ?>
<?php printZDSearchToggleJS(); ?>

<div id="sidebar">
   	<?php printSearchForm(); ?>
		<hr />
   	<h2 class="latestadditions">Popular tags</h2>
   	<?php 
		if(function_exists('printAllTagsFromZenpage')) {
			?>
			<p><?php printAllTagsFromZenpage('news','','taglist',false,true,0.5,2,5,50); ?></p>
			<br style="clear:both" />
			<?php
		} else {
			printAllTagsAs('cloud','taglist','abc',TRUE,TRUE,2,50,10,NULL,0.8);
		}
  ?>
   <br clear="all" /><hr />
	<?php zp_printSidebarBoxes(); ?>
 	<hr />
 	 <div id="ads">
			<script type="text/javascript">
		
			google_ad_client = "pub-7903690389990760";
			google_ad_width = 250;
			google_ad_height = 250;
			google_ad_format = "250x250_as";
			google_ad_type = "text";
			google_ad_channel ="";
			google_color_border = "CCCCCC";
			google_color_bg = "FFFFFF";
			google_color_link = "000000";
			google_color_url = "666666";
			google_color_text = "333333";
		
		</script>
			<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
   </div>
   
</div>

<div id="content">
    	
		<h2>Search results</h2>
		<?php
		$numimages = getNumImages();
		$numalbums = getNumAlbums();
		$total = $numimages + $numalbums;
		$zenpage = getOption('zp_plugin_zenpage');
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
		if ($total > 0 ) {
			?>
			<p>
			<?php
			printf(ngettext('%1$u Hit for <em>%1$s %2$s</em>','%1$u Hits for <em>%1$s %2$s</em>',$total), $total, html_encode($searchwords));
			?>
			</p>
			
			<?php
			printPageListWithNav("« ".gettext("prev"),gettext("next")." »");
		}
		if ($_zp_page == 1) { //test of zenpage searches
			if ($numpages > 0) {
				$number_to_show = 5;
				$c = 0;
				?>
				<h3 class="searchresulttitle"><?php printf(gettext('Pages (%s)'),$numpages); ?> <small><?php	printZDSearchShowMoreLink("pages",$number_to_show); ?></small></h3>
					<ul class="searchresults">
					<?php
					while (next_page()) {
						$c++;
						?>
						<li<?php printZDToggleClass('pages',$c,$number_to_show); ?>>
						<h4 class="entrytitle"><?php printPageTitlelink(); ?></h4>
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
				<h3 class="searchresulttitle"><?php printf(gettext('Articles (%s)'),$numnews); ?> <small><?php	printZDSearchShowMoreLink("news",$number_to_show); ?></small></h3>
					<ul class="searchresults">
					<?php
					while (next_news()) {
						$c++;
						?>
						<li<?php printZDToggleClass('news',$c,$number_to_show); ?>>
						<h4 class="entrytitle"><?php printNewsTitleLink(); ?> <small class="articledate"><?php printNewsDate();?></small>
							<?php
							if($_zp_current_zenpage_news->inNewsCategory("extensions")) { 
								zp_printExtensionStatusIcon();
							}?>
						</h4>
							<div class="entrymeta">
							<?php printNewsCategories(", ",gettext("Categories: "),"wp-category"); ?>
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
						printf(gettext('Images (%s)'),$numimages);
					}
				} else {
					if (getOption('search_no_images')) {
						if (($numpages + $numnews) > 0) {
							printf(gettext('Albums (%s)'),$numalbums);
						}
					} else {
						printf(gettext('Albums (%1$s) &amp; Images (%2$s)'),$numalbums,$numimages);
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
					if($count == 2) {
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
								if(getNumImages() === 0) { // to prevent tying to jump to the first image of an album with only subalbums
									$albumlinkurl = getAlbumLinkURL();
								} else {
									$albumlinkurl = getAlbumLinkURL().$firstimage[0].".php";
								}
								?>
							<a href="<?php echo html_encode($albumlinkurl);?>" title="<?php echo gettext('View album:'); ?> <?php echo getBareAlbumTitle();?>"><?php printCustomAlbumThumbImage(getBareAlbumTitle(), NULL, 255, 128, 255, 128, NULL,NULL,"thumbnail",NULL,TRUE,false); ?></a>
 							 </div>
								<div class="albumdesc">
									<h4 class="entrytitle"><a href="<?php echo html_encode($albumlinkurl);?>" title="<?php echo gettext('View album:'); ?> <?php echo getBareAlbumTitle();?>"><?php echo shortenContent(getAlbumTitle(),20,'(...)'); ?></a>
									<?php 
										if(zp_getParentAlbumName() === 'theme') {					
											echo '<small>(Theme)</small>';	
										}	
									?>
									</h4>								
									<?php zp_printThemeStatusIcon(); ?>
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
					if($count == 2) {
						$class = ' rightthumb';
						$count = '';
					} else {
						$class = ' leftthumb';
					}
				?>
				<div class="album<?php echo $class; ?>">
        		<div class="thumb">
					<a href="<?php echo htmlspecialchars(getImageLinkURL());?>" title="<?php echo getImageTitle();?>"><?php printCustomSizedImage(getBareImageTitle(), NULL, 255, 128, 255, 128,NULL,NULL,"thumbnail",NULL,true,false); ?></a>
						</div>
				<div class="albumdesc">
					<h4 class="entrytitle"><a href="<?php echo htmlspecialchars(getImageLinkURL());?>" title="<?php echo getImageTitle();?>"><?php echo shortenContent(getImageTitle(),20,'(...)'); ?>
					<?php 
						$albumname = $_zp_current_image->album;
						$parent = $albumname->getParent();
						if(is_object($parent) && $parent->name == 'theme') {					
							echo '<small>(Theme)</small>';	
						}	
						if($albumname->name == 'showcase') {					
							echo '<small>(Showcase)</small>';	
						}	
						?>
					</h4><?php zp_printShowcaseTypeIcon(); ?></a>
				</div>
			</div>
				<?php endwhile; ?>
			</div>
		<br clear="all" />
<?php } ?>
		<?php
		//if (function_exists('printSlideShowLink')) printSlideShowLink(gettext('View Slideshow'));
			if ($total == 0) {
				echo "<p>".gettext("Sorry, no matches found. Try refining your search.")."</p>";
			}

			printPageListWithNav("« ".gettext("prev"),gettext("next")." »");
			?>
        
	

	
<?php include('footer.php'); ?>
