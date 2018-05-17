<?php

/**
 * Class for the theme related advertisement handling and display
 */
class zporgSponsors {
	
	/**
		 * Prints the ads on the sponsors album page and the platinum sidebar ones. If no sponsor is set default placeholders from the album "storage/sponsor-placeholders" are used.
		 */
		static function printAds($sponsorplatinum = false) {
			global $_zp_themeroot, $_zp_gallery, $_zp_current_album, $_zp_gallery_page;
			if ($sponsorplatinum) {
				switch ($_zp_gallery_page) {
					case 'index.php':
						$albums = array('sponsors/platinum');
						break;
					default:
						$albums = array('sponsors/palladium');
						break;
				}
			} else {
				$albums = array('sponsors/gold', 'sponsors/silver', 'sponsors/bronze');
			}
				?>
					<p class="ad-headline">Advertisements</p>
				<?php
			foreach ($albums as $album) {
				$albobj = newAlbum($album);
				$imagescount = $albobj->getNumImages();
				$adheight = 130;
				switch ($album) {
					case 'sponsors/platinum':
					case 'sponsors/palladium':
						$adwidth = 275;
						$maxnum = 2;
						switch ($album) {
							case 'sponsors/platinum':
								$imgclass = 'sponsor-platinum';
								break;
							case 'sponsors/palladium':
								$imgclass = 'sponsor-palladium';
								break;
						}
						break;
					case 'sponsors/gold':
						$adwidth = 560;
						$maxnum = 99;
						$imgclass = 'sponsor-gold';
						break;
					case 'sponsors/silver':
						$adwidth = 270;
						$maxnum = 2;
						break;
					case 'sponsors/bronze':
						$adwidth = 125;
						$maxnum = 4;
						break;
				}
				if ($imagescount != 0) {
					$images = $albobj->getImages(0, 0, null, null, true, true);
					$count = '';
					foreach ($images as $image) {
						$count++;
						$imgobj = newImage($albobj, $image);
						switch ($album) {
							case 'sponsors/platinum':
								$imgclass = 'sponsor-platinum';
								$linkclass = 'platinum-ad';
								if ($count == 1) {
									$imgclass .= ' sponsor-platinum-first';
								}
								break;
							case 'sponsors/palladium':
								$imgclass = 'sponsor-palladium';
								$linkclass = 'palladium-ad';
								if ($count == 1) {
									$imgclass .= ' sponsor-palladium-first';
								}
								break;
							case 'sponsors/gold':
								$linkclass = 'gold-ad';
								break;
							case 'sponsors/silver':
								$imgclass = 'sponsor-silver';
								$linkclass = 'silver-ad';
								if ($count < 2) {
									$imgclass .= ' sponsor-right';
								}
								break;
							case 'sponsors/bronze':
								$imgclass = 'sponsor-bronze';
								$linkclass = 'bronze-ad';
								if ($count != 4) {
									$imgclass .= ' sponsor-right';
								}
								//echo $count."/".$imgclass;
								break;
						}
						if (isImagePhoto($imgobj)) {
							$link = $imgobj->getCustomData();
							?>
							<a href="<?php echo html_encode($link); ?>" title="<?php echo html_encode($imgobj->getTitle()); ?>" class="<?php echo $linkclass; ?>"><img class="<?php echo $imgclass; ?>" src="<?php echo html_encode($imgobj->getFullImage()); ?>" alt="<?php echo html_encode($imgobj->getState()); ?>" width="<?php echo $adwidth; ?>" height="<?php echo $adheight; ?>"></a>
							<?php
						} else { // textobject support
							$ad = $imgobj->getSizedImage($adwidth);
							//$ad = strip_tags($imgobj->getSizedImage($adwidth),'<a><img>');
							?>
							<div class="<?php echo $imgclass; ?>"><?php echo $ad; ?></div>
							<?php
						}
					} // image loop
				} // if images != 0
				if ($imagescount != $maxnum && ($album == 'sponsors/platinum' || $album == 'sponsors/palladium')) {
					//if($imagescount != $maxnum) {
					?>
					<?php
					$link = getPageURL('advertise');
					if ($imagescount == 0) {
						$max = $maxnum;
					} else {
						$max = $maxnum - $imagescount;
					}
					$placeholderalb = newAlbum('storage/sponsor-placeholders');
					for ($i = 1; $i <= $max; $i++) {
						switch ($album) {
							case 'sponsors/platinum':
								$imgclass = 'sponsor-platinum';
								if ($i == 1) {
									$imgclass .= ' sponsor-platinum-first';
								}
								$placeholderimg = newImage($placeholderalb, 'sponsorplatinum-placeholder-ad.gif');
								break;
							case 'sponsors/palladium':
								$imgclass = 'sponsor-platinum';
								if ($i == 1) {
									$imgclass .= ' sponsor-platinum-first';
								}
								$placeholderimg = newImage($placeholderalb, 'sponsorpalladium-placeholder-ad.gif');
								break;
							/* s
							  case 'sponsors/gold':
							  $placeholderimg = newImage($placeholderalb,'sponsorgold-placeholder.gif');
							  break;
							  case 'sponsors/silver':
							  $imgclass = 'sponsor-silver';
							  if($i < $max) {
							  $imgclass .= ' sponsor-right';
							  }
							  $placeholderimg = newImage($placeholderalb,'sponsorsilver-placeholder.gif');
							  break;
							  case 'sponsors/bronze':
							  $imgclass = 'sponsor-bronze';
							  if($i < $max) {
							  $imgclass .= ' sponsor-right';
							  }
							  $placeholderimg = newImage($placeholderalb,'sponsorbronze-placeholder.gif');
							  //echo $count."/".$imgclass;
							  break; */
						}
						$placeholderimg = $placeholderimg->getFullImage();
						?>
						<a target="_top" href="<?php echo html_encode($link); ?>" title="Advertise"><img class="<?php echo $imgclass; ?>" src="<?php echo html_encode($placeholderimg); ?>" alt="Advertise" width="<?php echo $adwidth; ?>" height="<?php echo $adheight; ?>"></a>
						<?php
					} // for
				} // if
				if ($album == 'sponsors/silver') {
					?>
					<br style="clear:left" />
					<?php
				} else if ($album == 'sponsors/platinum' || $album == 'sponsors/palladium') {
					echo '<hr />';
				}
			} // album loop
		}
		
		/**
		 * Prints the ad space availability for use in the sponsor categories
		 * 
		 * @param $which 'platinum', 'palladium', 'gold', 'silver', 'bronze' (ad cat box usage)
		 */
		static function printAvailability($which = 'platinum') {
			global $_zporg_sponsor_categories;
			if(!isset($_zporg_sponsor_categories)) {
				$obj = newAlbum('sponsors');
				$albums = $obj->getAlbums(0);
				$maxavail = array(
						'platinum' => 2,
						'palladium' => 2,
						'silver' => 2,
						'gold' => 'unlimited',
						'bronze' => 4
				);
				foreach ($albums as $album) {
					$albobj = newAlbum($album);
					$imagescount = $albobj->getNumImages();
					$maxnum = $maxavail[strtolower($albobj->getTitle())];
					if ($album == 'sponsors/gold') {
						$max = $maxnum;
					} else {
						if ($imagescount != $maxnum) {
							$max = $maxnum - $imagescount;
						} else {
							$max = 0;
						}
					}
					$_zporg_sponsor_categories[strtolower($albobj->getTitle())] = $max;
				}
			}
			?>
			<p class="sponsorcategories_entry-avail">Availability: <strong><?php echo $_zporg_sponsor_categories[$which]; ?></strong></p>
			<?php
		}
		
		/**
		 * Prints the sponsor categories, e.g. the subpages of the "Advertisement categories" page
		 */
		static function printCategories() {
			$obj = new ZenpagePage('advertisement-categories');
			if($obj->loaded) {
				$adcats = $obj->getPages();
				if($adcats) {
					?>
					<div class="sponsorcategories">
					<h4>Advertisement categories</h4>
					<?php
					foreach($adcats as $adcat) {
						$adcatobj = new ZenpagePage($adcat['titlelink']);
						?>
						<div class="sponsorcategories_entry">
							<h5><?php echo $adcatobj->getTitle(); ?></h5>
							<div class="sponsorcategories_entry-body">
							<?php 
							echo $adcatobj->getContent();
							?>
							</div>
							<?php
							if($adcat['titlelink'] != 'bundles') {							
								self::printAvailability($adcatobj->getTitlelink()); 
							}
							?>
						</div>
						<?php
					}
					?>
					</div>
					<?php
				}
			}
		}
	
}

