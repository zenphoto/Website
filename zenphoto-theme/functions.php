<?php
/* gets the tags prefixed with "author_", "pluginsupport_" and "zp_" (author/contributor status tags)
 *
 * @param string $mode 'item' (single album or article only) or 'all'
 * @param string $tagmode 'author', "status', 'pluginsupport'
 * @return array
 */
 function zp_getSpecificTags($mode='item',$tagmode='author',$obj=null) {
	global $_zp_gallery_page, $_zp_current_album, $_zp_current_zenpage_news, $_zp_current_zenpage_page;
	switch($mode) {
		case 'item':
			switch($_zp_gallery_page) {
				case 'album.php':
				case 'image.php':
					$tags = $_zp_current_album->getTags();
					break;
				case 'news.php':
					$tags = $_zp_current_zenpage_news->getTags();
					break;
				case 'pages.php':
					$tags = $_zp_current_zenpage_page->getTags();
					break;
			}
			break;
		case 'all':
	 		$tags = getAllTagsUnique();
			break;
	}
	switch($tagmode) {
		case 'author':
			$search_text = 'author_';
			break;
		case 'status':
			$search_text = 'zp_';
			break;
		case 'pluginsupport':
			$search_text = 'pluginsupport_';
			break;
	}
	$specialtags = array();
	if(!empty($tags)) {
		foreach($tags as $tag) {
			if(stristr($tag,$search_text)) {
				$specialtags[] = $tag;
			}
		} 
		unset($tags);
	}
  return $specialtags;
 }

/* gets the tags prefixed with "author_"
 *
 * @param string $mode 'item' (single album or article only) or 'all'
 * @return array
 */
 function zp_getAuthorTags($mode='item') {
	 return zp_getSpecificTags($mode,'author');
 }

/* Prints the tag search link for this author $tag
 *
 */
function zp_printReadmoreOfAuthorLink($tag='',$count='') {
  echo '<a href="'.html_encode(getSearchURL($tag, '', 'tags','')).'" title="'.html_encode($tag).'">'.ucwords(substr($tag,7)).'</a>';
 }


/* Prints a list of "More by author:xxx" tag search links for all authors assigned to this item (album or article)
 *
 */
function zp_printMoreByAuthorsLinks() {
	$tags = zp_getAuthorTags('item');
	if(!empty($tags)) {
		$tagcount = getAllTagsCount();
		?>
		<hr />
		<h3 class="relateditems">More by author:</h3>
		<ul class="morebyauthorlist">
		<?php
		foreach($tags as $tag) {
			$count = $tagcount[$tag];
			if($count > 1) {
				?>
				<li><?php zp_printReadmoreOfAuthorLink($tag); ?> (<?php echo $count; ?>)</li>
				<?php
			} else {
				?>
				<li>Nothing else available by <?php echo ucwords(substr($tag,7)); ?></li>
				<?php
			}
 		} 
 		?>
 		</ul>
 		<?php
 	} 
}

/* gets all items of the current item type (album/article) assiged to the author $tag (uses a function from related_items plugin)
 * @param string $tag author tag to use
 * @param string $mode 'albums' or 'news' or 'all' for both
 * @return array
 */
	function zp_getMoreByThisAuthor($tag='',$mode='all') {
 		global $_zp_gallery_page;
 		$result = array();
 		$search = new SearchEngine();
 		$searchstring = $tag;
 		$paramstr = urlencode('words').'='.$searchstring.'&searchfields=tags';
 		$searchparams = $search->setSearchParams($paramstr);
 		switch($mode) {
			case 'albums':
				$result = $search->getAlbums(0,"date","desc");
				break;
			case 'news':
				$result = $search->getArticles(0,NULL,true,"date","desc");
				break;
			case 'all':
				$result1 = $search->getAlbums(0,"date","desc");
				$result2 = $search->getArticles(0,NULL,true,"date","desc");
				//$result = array_merge($result1,$result2);
				break;
		}
		if(function_exists('createRelatedItemsResultArray')) { 
			switch($mode) {
				case 'albums':
					$result = createRelatedItemsResultArray($result,$mode);
					break;
				case 'news':
					$result = createRelatedItemsResultArray($result,$mode);
					break;
				case 'all':
					$result1 = createRelatedItemsResultArray($result1,'albums');
					$result2 = createRelatedItemsResultArray($result2,'news');
					$result = array_merge($result1,$result2);
					$result = sortMultiArray($result,'name',false, true,false,false); // sort by name abc
					break;
			}
		}
 		if(empty($result)) {
			return NULL;
		} else {
			return $result;
		}
 	}
 	
 /* custom mod of printRelatedItems()	for printing items with the tag author_xxxx
 * @param string $tag author tag to use
 * @param string $mode 'albums' or 'news' or 'all' for both
 * @param string $mode2 'extensions' (default), 'user-guide' or 'release'
 * @return array
 */
function zp_printAuthorContributions($tag,$mode,$mode2='extensions') {
	global $_zp_gallery, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news;
	$thumb = false;
	$result = zp_getMoreByThisAuthor($tag,$mode);
	$result = sortMultiArray($result,'name',false,true,false,false); // sort by name abc
	if($mode == 'news') {
		if($mode2 == 'extensions' || $mode2 == 'user-guide' || $mode2 == 'release') {
			$resultnew = array();
			foreach($result as $item) {
				$i = new ZenpageNews($item['name']);
				if($i->inNewsCategory($mode2)) {
					$resultnew[] = $item;
				}
			}
			$result = $resultnew;
			unset($resultnew);
		}
	}
	$resultcount = count($result);
	if($resultcount != 0) {
	   switch($mode) {
	   	case 'news': 
				switch($mode2) {
					case 'extensions':
						?><h4>Extensions contributions (<?php echo $resultcount; ?>)</h4><?php
						break;
					case 'user-guide':
						?><h4>User guide contributions (<?php echo $resultcount; ?>)</h4><?php
						break;
					case 'release':
						?><h4>Release contributions (<?php echo $resultcount; ?>)</h4><?php
						break;
				}
			 break;
			 case 'albums': 
			 	?><h4>Theme contributions (<?php echo $resultcount; ?>)</h4><?php
			 	break;
		}
		?>
		<ol class="contributionslist">
		<?php
		foreach($result as $item) {
			$category = '';
			switch($item['type']) {
				case 'albums':
					$obj = new Album(NULL,$item['name']);
					$url = $obj->getAlbumLink();
					$text = $obj->getDesc();
					//$category = gettext('Theme');
					break;
				case 'news':
					$obj = new ZenpageNews($item['name']);
					$url = getNewsURL($obj->getTitlelink());
					$text = $obj->getContent(); 
					/*if($mode2 == 'extensions') {
						$category = 'Extensions';
					} elseif ($mode2 == 'user-guide') {
						$category = 'User guide';
					}	elseif ($mode2 == 'release') {
						$category = 'Release';
					} */
					break;
			}
			?>
			<li class="<?php echo $item['type']; ?>">
				<?php
				if($thumb) {
					$thumburl = false;
					switch($item['type']) {
						case 'albums':
							$thumburl = $obj->getAlbumThumb();
							break;
					}
					if($thumburl) {
						?>
						<a href="<?php echo pathurlencode($url); ?>" title="<?php echo html_encode($obj->getTitle()); ?>" class="contributions_thumb">
						<img src="<?php echo pathurlencode($thumburl); ?>" alt="<?php echo html_encode($obj->getTitle()); ?>" />
						</a>
						<?php
					}
				} 
				?>
			<h4><a href="<?php echo pathurlencode($url); ?>" title="<?php echo html_encode($obj->getTitle()); ?>"><?php echo html_encode($obj->getTitle()); ?></a>
				<?php 
					switch($item['type']) {
						case 'albums':
						case 'images':
							$d = $obj->getDateTime();
							break;
						case 'news':
						case 'pages':
							$d = $obj->getDateTime();
							break;
					}
					?>
					<span class="contributons_date">
					<small> – <?php echo zpFormattedDate(DATE_FORMAT, strtotime($d)); ?>
					</span>
					<?php
				 ?></small>
					<?php 
				 switch($item['type']) {
						case 'albums':
							zp_printThemeStatusIcon($obj);
							break;
						case 'news':
							 zp_printExtensionStatusIcon($obj); 
							break;
					}
				 ?>
				</h4>
			</li>
			<?php
	} // foreach
		?>
		</ol>
		<?php
	} 
}

/**
 * Gets the theme status icon class for the single album and image page (not for the overview!)
 *
 */
function zp_printAuthorStatusIcon($obj=NULL) {
	global $_zp_current_zenpage_page, $_zp_gallery_page, $_zp_themeroot;
	if(is_null($obj)) {
		$obj = $_zp_current_zenpage_page;
	}	
	$icon = '';
	if(is_object($obj)) {
		if($obj->hasTag('zp_team-member')) {
			$icon = "<img class='authoricon' src='".$_zp_themeroot."/images/authoricon_developer.png' alt='' />";
		}
		if($obj->hasTag('zp_team-member-former')) {
			$icon = "<img class='authoricon' src='".$_zp_themeroot."/images/authoricon_former.png' alt='' />";
		} 
		echo $icon;
	}
}

/**
 * Prints the subline for an author entry with team rank and roles
 *
 */
function zp_printAuthorStatusRanks($obj=NULL) {
	if(is_null($obj)) {
		$statustags = zp_getSpecificTags('item','status');
	} else {
		$statustags = $obj->getTags();
	}
	?>
	<ul class="authorstatusrank">
	<?php
	if(in_array('zp_team-member',$statustags)) {
		echo "<li><strong>Zenphoto team member</strong></li>";
	} else 	if(in_array('zp_team-member-former',$statustags)) {
		echo "<li><strong>Former Zenphoto team member</strong></li>";
	} else {
		echo "<li>Zenphoto contributor</li>";
	}
	$count = '';
	foreach($statustags as $status) {
		if($status != 'zp_team-member' && $status != 'zp_team-member-former') {
			$count++;
			?>
			<li>
				<?php 
				echo ", "; echo ucfirst(str_replace(array('zp_','-'), array('',' '),$status)); 
				?></li><?php
		}
	}
	?>
	</ul>
 <?php
}

/* Prints a list of all contribitor profile pages (subpages of "contributors")
 * @param string $mode 'all', 'teammembers', "formermembers'
 */
function zp_printAuthorList($mode='all',$content=false) {
	$page = new ZenpagePage('contributors');
	$subpages = $page->getPages();
	?>
	<ul class="authors">
		<?php
		foreach($subpages as $subpage) {
			$obj = new Zenpagepage($subpage);
			if($mode == 'all' || ($mode == 'teammembers' && $obj->hasTag('zp_team-member')) || ($mode == 'formermembers' && $obj->hasTag('zp_team-member-former'))) {  
				?>
				<li>
					<?php 
					/* $mail = $obj->getCustomData();
					if(!empty($mail)) { 
						$imgurl = zp_getAuthorGravatarImage($mail,40);
						if(!empty($imgurl)) { 
							echo $imgurl; 
						}
					} */
					?>
					<h3 class="entrytitle"><a href="<?php echo getPagelinkURL($obj->getTitlelink()); ?>">
						<?php 
						echo $obj->getTitle();  
						if(strtolower($obj->getTitle()) != strtolower($obj->getTitlelink())) {
							?>
							<em>(<?php echo $obj->getTitlelink(); ?>)</em>
							<?php
						}
						?>
						</a><?php zp_printAuthorStatusIcon($obj);?>
					</h3>
					<div class="entrymeta">
						<?php zp_printAuthorStatusRanks($obj); ?>
					</div>
					<?php if($content) { ?>
						<div class="entrybody">
							<?php echo $obj->getContent(); ?>
						</div>
					<?php } ?>
				</li>
				<?php
			}
		}
		?>
	</ul>
	<?php
}

/* Prints a list of all contribitor profile pages (subpages of "contributors")
 * @param string $mode 'all', 'teammembers', "formermembers'
 */
function zp_printItemAuthorCredits() {
	if((zp_inNewsCategory('user-guide') || zp_inNewsCategory('extensions')) && zp_loggedin()) { // not live yet!
		$authors = zp_getSpecificTags('item','author');
		$numauthors = count($authors);
		if($numauthors != 0) {
			if($numauthors > 1) {
				$credit = 'Developers: ';
			} else {
				$credit = 'Developer: ';
			}
			$page = new ZenpagePage('contributors');
			$subpages = $page->getPages();
			?>
			<strong><?php echo $credit; ?> </strong>
			<ul id="authorcredits">
		 	<?php
		 	$count = '';
			foreach($authors as $author) {
				$count++;
				$author = str_replace('author_', '',$author);
				?>
				<li><?php 
				if($count > 1) echo ', '; 
				if(in_array($author,$subpages)) {
					$obj = new ZenpagePage($author);
					?>
					<a href="<?php echo getPagelinkURL($obj->getTitlelink()); ?>">
					<?php 
					if(strtolower($obj->getTitlelink()) != strtolower($obj->getTitle())) {
						echo $obj->getTitle();?> <em>(<?php echo $obj->getTitlelink(); ?>)</em>
						<?php 
					} else { 
						echo $obj->getTitle(); 
					} ?>
					</a>
					<?php
				} else {
					echo $author; 
				}
				?>
				</li>
				<?php
			}
			?>
			</ul>
			<br />
			<?php
		}
	}
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address. 
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function zp_getAuthorGravatarImage($email,$s=80,$d='mm',$r='r',$img=true,$atts=array()) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5(strtolower(trim($email)));
	$url .= "?s=$s&d=$d&$r=$r";
	if($img) {
		$url = '<img class="authorprofile-imagelist" src="'.$url.'"';
		foreach($atts as $key => $val) {
			$url .= ' '.$key.'="'.$val.'"';
		}
		$url .= ' />';
	}
	return $url;
}

/**
 * Get the full Gravatar profile data as an array.
 *
 * @param string $email The email address
 * @return array containing the profile data
 * @source http://en.gravatar.com/site/implement/profiles/php/
 */
function zp_getAuthorGravatarProfileData($email) {
	if(!empty($email)) {
		$hash = md5(strtolower(trim($email)));
		$str = file_get_contents( 'http://www.gravatar.com/'.$hash.'.php' );
		$profile = unserialize($str);
		if(is_array($profile) && isset($profile['entry'])) {
    	return $profile;
  	}
  }
}

/**
 * Prints data from the Gravatar profile
 *
 * @param string $field What to print 'thumbnail', 'aboutme', 'urls', 'all' (all in this order)
 * @param array $profile The Gravatar profile array as fetched by  zp_getAuthorGravatarProfileData()
 * @return gets the profile data as html elements
 * @source http://en.gravatar.com/site/implement/profiles/php/
 */
function zp_getAuthorGravatarProfile($field,$profile) {
	if(is_array($profile)) {
		if(($field == 'thumbnail' || $field == 'all') && !empty($profile['entry'][0]['thumbnailUrl'])) {
			 return '<img class="authorprofile-image" src="'.$profile['entry'][0]['thumbnailUrl'].'?s=105" alt="" />';
		}
		if(($field == 'aboutme' || $field == 'all') && !empty($profile['entry'][0]['aboutMe'])) {
			return '<p class="authorprofile-text">'.$profile['entry'][0]['aboutMe'].'</p>';
		}
		if(($field == 'urls' || $field == 'all') && count($profile['entry'][0]['urls'])!= 0) {
			$websites = '
			<hr />
			<h4>Websites</h4>
			<ul class="authorprofile-links">
			';
			foreach($profile['entry'][0]['urls'] as $url) {
				$websites .= '<li><a href="'.$url['value'].'">'.$url['title'].'</a></li>';
			}
			$websites .= '</ul>';
			return $websites;
		}
	}
}


/**
 * Gets the Google/Google+ profile image 
 * @param string $userid 	The Google+ user ide number as found in the url of the profile. 
 *												This must be passed as a string enclosed in quotes!
 * @param num $size Size in pixels, if false full size
 * @return html img
 */
function zp_getAuthorGoogleImage($userid='',$size=false) {
  if(!empty($userid)) {
		$url = 'https://www.google.com/s2/photos/profile/'.$userid;
		if($size) {
			$url .= '?sz='.$size;
		}
		$headers = get_headers($url, 1);
		return "<img src=$headers[Location]>";
	}
}

/* Prints the plugin support list based on "pluginsupport_<pluginname(titlelink of article)>" tags.
 *
 * @return array
 */
 function zp_printPluginsupportTags() {
 	if(zp_getParentAlbumName() == "theme") {
		$tags = zp_getSpecificTags('item','pluginsupport');
		?>
		<h4>Layout specific plugins supported:</h4>
		<?php
		if(!empty($tags)) {
			?>
			<ul class="morebyauthorlist">
			<?php
			foreach($tags as $tag) {
				$tag = substr($tag,14);
				?>
				<li>
					<?php
					if($tag == 'zenpage') {
						$url = getNewsURL('zenpage-a-cms-plugin-for-zenphoto');
					} else {
						$url = getNewsURL($tag);
					}
					echo "<a href=\"".html_encode($url)."\" title=\"".$tag."\">".$tag."</a>"; ?>
				</li>
				<?php
			}
			?>
			</ul><br />
			<?php
		} else {
			echo "<p>Currently no information available.</p>";
		}
	}
 }


/**
 * Prints only the "news" subcategories as a sublist folded out for the main news section
 *
 */
function zp_printNewsCategoryFoldout() {
	global $_zp_current_category;
	if(zp_inNewsCategory('news') || is_null($_zp_current_category)) {
		$cat_news = new ZenpageCategory('news');
		echo '<ul class="newscategories">';
		$allnews = '';
		if(is_null($_zp_current_category)) {
			$allnews = ' class="active"';
		}
		echo '<li'.$allnews.'><a href="'.html_encode(getNewsIndexURL()).'" title="All news">All news</a></li>';
		$active = '';
		if(!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == 'news') {
			$active = ' class="active"';
		}
		$count = ' <small>('.count($cat_news->getArticles(0)).')</small>';
		echo '<li'.$active.'><a href="'.html_encode(getNewsCategoryURL($cat_news->getTitlelink())).'" title="News">'.html_encode($cat_news->getTitle()).$count.'</a>';
		$subcats = $cat_news->getSubCategories();
		if($subcats) {
			echo '<ul>';
			foreach($subcats as $subcat) {
				$subobj = new ZenpageCategory($subcat);
				$active2 = '';
				$articleCount = count($subobj->getArticles(0));
				if($articleCount != 0) {
					if(!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == $subobj->getTitlelink()) {
						$active2 = ' class="active"';
					}
					$count = ' <small>('.$articleCount.')</small>';
					echo '<li'.$active2.'><a href="'.getNewsCategoryURL($subobj->getTitlelink()).'" title="'.html_encode($subobj->getTitle()).'">'.html_encode($subobj->getTitle()).$count.'</a></li>';
				}
			}
			echo '</ul>';
		}
		echo '</li>';
		$countcat = new ZenpageCategory('user-guide');
		$count = ' <small>('.count($countcat->getArticles(0)).')</small>';
		echo '<li><a href="'.html_encode(getNewsCategoryURL('user-guide')).'" title="User guide">User guide'.$count.'</a></li>';
		$countcat = new ZenpageCategory('extensions');
		$count = ' <small>('.count($countcat->getArticles(0)).')</small>';
		echo '<li><a href="'.html_encode(getNewsCategoryURL('extensions')).'" title="Extensions">Extensions'.$count.'</a></li>';
		echo '</ul>';
	}
}
/**
 * Prints only the "user guide" or "extensions "subcategories as a sublist folded out.
 * Difference to the standard function this is also printed if we are on a single article page
 * Means an article should only be in one main category at the time ("news", "user-guide" or "extensions" - Do not mix!)
 *
 */
function zp_printSubCategories($cattitlelink) {
	global $_zp_current_category;
	$cattitlelink = sanitize($cattitlelink);
	$currentcat = new ZenpageCategory($cattitlelink);
 	$subcats = $currentcat->getSubCategories();
	if($subcats) {
	  echo '<ul class="newscategories">';
	  foreach($subcats as $subcat) {
	  	$subcatobj = new ZenpageCategory($subcat);
	 		$active = '';
	 		$articleCount = count($subcatobj->getArticles(0));
	 		if($articleCount != 0) {
				if(!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == $subcatobj->getTitlelink()) {
					$active = ' class="active"';
				}
				$count = ' <small>('.$articleCount.')</small>';
				echo '<li'.$active.'><a href="'.html_encode(getNewsCategoryURL($subcat)).'" title="'.html_encode($subcatobj->getTitle()).'">'.html_encode($subcatobj->getTitle()).$count.'</a></li>';
	  	}
	  }
	  echo '</ul>';
	}
}

/**
 * Custom printout of the printLatestNews(). Parameters are the same.
 *
 */
function zp_printLatestNews($number=2,$option='none', $category='') {
	$latest = getLatestNews($number,$option, $category);
	if(is_array($latest)) {
		echo "<ul>";
		foreach($latest as $news) {
			$article = new ZenpageNews($news['titlelink']);
			?>
			<li>
			<a href="<?php echo html_encode(getNewsURL($news['titlelink'])); ?>">
			<?php echo html_encode($article->getTitle()); ?>
			</a>
			<small> (<?php echo zpFormattedDate(getOption('date_format'),strtotime($article->getDatetime())); ?>)</small>
			</li>
			<?php
		}
		echo '</ul>';
	}
}

/**
 * Prints a html list from an array of image/album items
 */
 function zp_printGalleryStatistics($array,$option='date',$album=false) {
 	echo '<ul>';
	foreach($array as $top) {
		if($album) {
			$top = new Album(NULL,$top['folder']);
			$image = $top->getImage(0);
			if(is_object($image)) {
				$link = $image->getImageLink();
			} else {
				$link = $top->getAlbumLink();
			}
		} else {
			$link = $top->getImageLink();
		}
		$extra = '';
		switch($option) {
			case 'date':
				$extra = zpFormattedDate(DATE_FORMAT,strtotime($top->getDateTime()));
				break;
			case 'rating':
				$rating = '';
				$votes = $top->get("total_votes");
				$value = $top->get("total_value");
				if($votes != 0) {
					$rating =  round($value/$votes, 1);
				}
				$extra = sprintf(gettext('Rating: %1$u (Votes: %2$u)'),$rating,$votes);
				break;
			}
		echo '<li><a href="'.$link.'">'.$top->getTitle().'</a> <small>('.$extra.')</small></li>';
	}
	echo '</ul>';
 }

/**
 * checks if the article is in a category on single article pages or if we are on a category page in general
 *
 */
function zp_inNewsCategory($titlelink) {
	global $_zp_current_zenpage_news, $_zp_current_category;
	$currentcat = '';
	if(is_NewsCategory() && !is_null($_zp_current_category)) {
		$currentcat = $_zp_current_category->getTitlelink();
		if($currentcat == $titlelink || $_zp_current_category->isSubNewsCategoryOf($titlelink)) {
			return TRUE;
		}
	}
	if(is_NewsArticle()) {
		if($_zp_current_zenpage_news->inNewsCategory($titlelink) || $_zp_current_zenpage_news->inSubNewsCategoryOf($titlelink)) {
			return TRUE;
		}
	}
	return FALSE;
}

// force UTF-8 Ø

/**
 * Prints jQuery JS to enable the toggling of search results of Zenpage  items
 *
 */
function printZDSearchToggleJS() { ?>
	<script type="text/javascript">
		// <!-- <![CDATA[
		function toggleExtraElements(category, show) {
			if (show) {
				jQuery('.'+category+'_showless').show();
				jQuery('.'+category+'_showmore').hide();
				jQuery('.'+category+'_extrashow').show();
			} else {
				jQuery('.'+category+'_showless').hide();
				jQuery('.'+category+'_showmore').show();
				jQuery('.'+category+'_extrashow').hide();
			}
		}
		// ]]> -->
	</script>
<?php
}

/**
 * Prints the "Show more results link" for search results for Zenpage items
 *
 * @param string $option "news" or "pages"
 * @param int $number_to_show how many search results should be shown initially
 */
function printZDSearchShowMoreLink($option,$number_to_show) {
	$option = strtolower(sanitize($option));
	$number_to_show = sanitize_numeric($number_to_show);
	switch($option) {
		case "news":
			$num = getNumNews();
			break;
		case "pages":
			$num = getNumPages();
			break;
	}
	if ($num > $number_to_show) {
		?>
<a class="<?php echo $option; ?>_showmore"href="javascript:toggleExtraElements('<?php echo $option;?>',true);"><?php echo gettext('Show more results');?></a>
<a class="<?php echo $option; ?>_showless" style="display: none;"	href="javascript:toggleExtraElements('<?php echo $option;?>',false);"><?php echo gettext('Show fewer results');?></a>
		<?php
	}
}


/**
 * Adds the css class necessary for toggling of Zenpage items search results
 *
 * @param string $option "news" or "pages"
 * @param string $c After which result item the toggling should begin. Here to be passed from the results loop.
 */
function printZDToggleClass($option,$c,$number_to_show) {
	$option = strtolower(sanitize($option));
	$c = sanitize_numeric($c);
	$number_to_show = sanitize_numeric($number_to_show);
	if ($c > $number_to_show) {
		echo ' class="'.$option.'_extrashow" style="display:none;"';
	}
}

/**
 * Prints prev/next album links for the theme section linking to the first image of an album
 *
 */
function zp_printNextPrevAlbumLinkFirstImage() {
	if (getPrevAlbum() || getNextAlbum()) {
		echo '<div id="albumnav">';
		if (getPrevAlbum()) {
			$prevalbum = getPrevAlbum();
			if($prevalbum->getNumImages() != 0) {
				$firstimg = $prevalbum->getImage(0);
				$firstimage = urlencode($firstimg->filename);
			} else {
				$firstimage = '';
			}
			echo "<a id='prevalbum' href='".WEBPATH."/".pathurlencode($prevalbum->name)."/".$firstimage.getOption("mod_rewrite_image_suffix")."' title='".$prevalbum->getTitle()."'>&laquo; <strong>".$prevalbum->getTitle()."</strong> (previous)</a>";
		}
		if (getNextAlbum()) {
			$nextalbum = getNextAlbum();
			if($nextalbum->getNumImages() != 0) {
				$firstimg = $nextalbum->getImage(0);
				$firstimage = urlencode($firstimg->filename);
			} else {
				$firstimage = '';
			}
			echo " <a id='nextalbum' href='".WEBPATH."/".pathurlencode($nextalbum->name)."/".$firstimage.getOption('mod_rewrite_image_suffix')."' title='".$nextalbum->getTitle()."'><strong>".$nextalbum->getTitle()."</strong> (next) &raquo;</a>";
		}
		echo '</div>';
	}
}
/**
 * gets the parent album name
 *
 */
function zp_getParentAlbumName() {
	global $_zp_current_album;
	if($_zp_current_album->getParent()) {
  	$parentalbumobj = $_zp_current_album->getParent();
  	$parentalbum = $parentalbumobj->name;
  } else {
  	$parentalbum = "";
  }
  return $parentalbum;
}

/**
 * Gets the theme status icon class for the single album and image page (not for the overview!)
 *
 */
function zp_getThemeStatusIconClass() {
	global $_zp_current_album, $_zp_themeroot, $_zp_gallery_page;
	if(is_object($_zp_current_album)) {
		if($_zp_current_album->hasTag('theme-officially-supported')) {
			return ' themeicon1';
		}
		if($_zp_current_album->hasTag('theme-compatible')) {
			return ' themeicon2';
		}
		if($_zp_current_album->hasTag('theme-partly-compatible')) {
			return ' themeicon3';
		}
		if($_zp_current_album->hasTag('theme-not-compatible')) {
			return ' themeicon4';
		}
		if($_zp_current_album->hasTag('theme-abandoned')) {
			return ' themeicon5';
		}
	}
}

/**
 * Prints the theme status icon for the theme overview section (album.php) and within the news loop in combinews mode (news.php)
 * @param obj $obj Object of an album optionally
 */
function zp_printThemeStatusIcon($obj=NULL) {
	global $_zp_current_album, $_zp_current_zenpage_news, $_zp_themeroot, $_zp_gallery_page;
	if(is_null($obj)) {
		$albumobj = '';
		switch($_zp_gallery_page) {
			case 'album.php':
			case 'search.php':
				$albumobj = $_zp_current_album;
				$iconclass = 'themeicon';
				break;
			case 'news.php':
				$albumobj = $_zp_current_zenpage_news;
				$iconclass = 'themeicon-news';
				break;
		}
	} else {
		$albumobj = $obj;
		$iconclass = 'themeicon';
	}
	if(is_object($albumobj)) {
		if($albumobj->hasTag('theme-officially-supported')) {
			echo "<img class='".$iconclass."' src='".$_zp_themeroot."/images/accept_green.png' alt='official and included theme' />";
		}
		if($albumobj->hasTag('theme-compatible')) {
			echo "<img class='".$iconclass."' src='".$_zp_themeroot."/images/accept_blue.png' alt='generally compatible 3rd party theme'/>";
		}
		if($albumobj->hasTag('theme-partly-compatible')) {
			echo "<img class='".$iconclass."' src='".$_zp_themeroot."/images/question_orange.png' alt='partly compatible 3rd party theme' />";
		}
		if($albumobj->hasTag('theme-not-compatible')) {
			echo "<img class='".$iconclass."' src='".$_zp_themeroot."/images/stop_round.png' alt='incompatible 3rd party theme' />";
		}
		if($albumobj->hasTag('theme-abandoned')) {
			echo "<img class='".$iconclass."' src='".$_zp_themeroot."/images/cancel_round.png' alt='no longer provided 3rd party theme' />";
		}
	}
}

/**
 * Prints the theme status icon legend list for the sidebar
 *
 */
function zp_printThemeStatusIconList() {
		global $_zp_themeroot;
?>
	<hr />
	<ul class="statuslist">
		<li class="themestatus1"><a href="<?php echo getSearchURL('theme-officially-supported','','','',NULL); ?>">Officially supported</a> <br />Included in the Zenphoto release package.</li>
		<li class="themestatus2"><a href="<?php echo getSearchURL('theme-compatible','','','',NULL); ?>">Generally compatible</a>
		<br />Unsupported and 3rd party</li>
		<li class="themestatus3"><a href="<?php echo getSearchURL('theme-partly-compatible','','','',NULL); ?>">Partly compatible</a> <br />Unsupported and 3rd party</li>
		<li class="themestatus4"><a href="<?php echo getSearchURL('theme-not-compatible','','','',NULL); ?>">Currently not compatible</a> <br />Unsupported and 3rd party</li>
		<li class="themestatus5"><a href="<?php echo getSearchURL('theme-abandoned','','','',NULL); ?>">No longer provided</a> <br />Unsupported and 3rd party</li>
	</ul>
	<?php
}

function zp_getShowcaseTypeIconClass() {
global $_zp_current_image;
if(is_object($_zp_current_image)) {
	if($_zp_current_image->hasTag('showcase_zenphoto-with-zenpage-cms-plugin')) {
			return ' showcaseicon1';
		}
		if($_zp_current_image->hasTag('showcase_zenphoto-only')) {
			return ' showcaseicon2';
		}
		if($_zp_current_image->hasTag('showcase_partly-zenphoto')) {
			return ' showcaseicon3';
		}
	}
}
/**
 * Prints the showcase type icon for the showcase overview section
 *
 */
function zp_printShowcaseTypeIcon() {
	global $_zp_current_image, $_zp_themeroot, $_zp_gallery_page;
	if(is_object($_zp_current_image)) {
		if($_zp_current_image->hasTag('showcase_zenphoto-with-zenpage-cms-plugin')) {
			echo "<img class='themeicon-news' src='".$_zp_themeroot."/images/showcasetype1.png' alt='Full site done completly with Zenphoto and the Zenpage CMS plugin' />";
		}
		if($_zp_current_image->hasTag('showcase_zenphoto-only')) {
			echo "<img class='themeicon-news' src='".$_zp_themeroot."/images/showcasetype2.png' alt='Site that uses Zenphoto only (e.g. pure gallery site)' />";
		}
		if($_zp_current_image->hasTag('showcase_partly-zenphoto')) {
			echo "<img class='themeicon-news' src='".$_zp_themeroot."/images/showcasetype3.png' alt='Site partly using Zenphoto (e.g. for the gallery part)' />";
		}
	}
}

/**
 * Prints the showcase type icon legend list for the sidebar
 *
 */
function zp_printShowcaseTypeIconList() {
?>
<hr />
	<ul class="statuslist">
		<li class="showcasetype1"><a href="<?php echo getSearchURL('showcase_zenphoto-with-zenpage-cms-plugin','','','',NULL); ?>">Full site done completly with Zenphoto and the Zenpage CMS plugin</a>*</li>
		<li class="showcasetype2"><a href="<?php echo getSearchURL('showcase_zenphoto-only','','','',NULL); ?>">Site that uses Zenphoto only (e.g. pure gallery site)</a>*</li>
		<li class="showcasetype3"><a href="<?php echo getSearchURL('showcase_partly-zenphoto','','','',NULL); ?>">Site partly using Zenphoto (e.g. for the gallery part)</a>*</li>
	</ul>
	<strong>*</strong><em>as on date added.</em></p>
<?php
}


/**
 * Prints the extension status icon legend list for the news loop
 * @param obj $obj Object of the extension article to check optionally
 */
function zp_printExtensionStatusIcon($obj=NULL) {
	global $_zp_current_zenpage_news, $_zp_themeroot;
	if(is_null($obj)) {
		$obj = $_zp_current_zenpage_news;
	} 
	if($obj->inNewsCategory("officially-supported")) {  ?>
		 <img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/accept_green.png" title="Officially supported" />
		<?php 
	} else if ($obj->inNewsCategory('unsupported-plugin-github') || $obj->inNewsCategory('unsupported-misc-github')) { ?>
		<img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/question_blue.png" title="Third party hosted on GitHub- not officially supported" />
		<?php  
	} else if ($obj->inNewsCategory('unsupported-plugin-selfhosted')) { 
		if($obj->hasTag('extension-abandoned')) { 
			?>
			<img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/cancel_round.png" title="Third party - abandoned by developer" />
			<?php
		} else {
			?>
			<img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/question_orange.png" title="Third party - not officially supported" />
		<?php
		}
	}
}

/**
 * Prints the code for the AddThis.com social network bookmark button
 * @param bool $narrow true (default) for a slim variante for sidebar, false for the wide one for single articles etc.
 */
function zp_printAddthis($narrow=true) {
	?>
	<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_google_plusone" g:plusone:annotation="none"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-50334a5249e551d6"></script>
<!-- AddThis Button END -->
<?php
}

/**
 * Prints the html header title for each page context sensitive
 */
function zp_printPageHeaderTitle() {
 global $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news, $_zp_current_category;
 $gallerytitle = getBareGalleryTitle();
 $parent = '';
 switch($_zp_gallery_page) {
 	case 'index.php':
 		return html_encode($gallerytitle);
 		break;
 	case 'album.php':
 		if(!is_null($_zp_current_album->getParent())) {
 			$parent = ' | '.$_zp_current_album->getParent()->getTitle();
 		}
 		$gallerytitle = $_zp_current_album->getTitle().$parent.' | '.$gallerytitle;
 		break;
 	case 'image.php':
 		if(!is_null($_zp_current_album->getParent())) {
 			$parent = ' | '.$_zp_current_album->getParent()->getTitle();
 		}
 		$gallerytitle = $_zp_current_image->getTitle().' | '.$_zp_current_album->getTitle().$parent.' | '.$gallerytitle;
 		break;
 	case 'news.php':
 		if(zp_inNewsCategory('user-guide')) {
 			$parent = ' | User guide';
 		} else if(zp_inNewsCategory('extensions')) {
 			$parent = ' | Extensions';
 		}
 		if(!is_null($_zp_current_zenpage_news)) {
 			$gallerytitle = $_zp_current_zenpage_news->getTitle().$parent.' | '.$gallerytitle;
 		} else if(!is_null($_zp_current_category)) {
 			$gallerytitle = $_zp_current_category->getTitle().$parent.' | '.$gallerytitle;
 		} else {
 			$gallerytitle = 'News | '.$gallerytitle;
 		}
 		break;
 	case 'pages.php':
 		if($_zp_current_zenpage_page->getParents()) {
 			$parentpages = $_zp_current_zenpage_page->getParents();
 			$parentpage = $parentpages[0];
 			$obj = new ZenpagePage($parentpage);
 			$parent = ' | '.$obj->getTitle();
 		}
 		$gallerytitle = $_zp_current_zenpage_page->getTitle().$parent.' | '.$gallerytitle;
 		break;
 	case 'search.php':
 		$gallerytitle = 'Search | '.$gallerytitle;
 		break;
 	case 'archive.php':
 		$gallerytitle = 'Archive | '.$gallerytitle;
 		break;
 }
 echo html_encode($gallerytitle);
}

/**
 * Prints downloadlinks for the logos incl. a albumzip download. Uses and requires the downloadlist plugin
 */
function zp_Logodownloads() {
	zp_specialdownloads('storage/logos');
}

/**
 * Prints downloadlinks for subalbum contents of a special album.
 * Used for the logo (via Zenpage page codeblock) and sponsor badges (multiple layout album page) download pages.
 * Uses and requires the downloadlist plugin
 */
function zp_specialdownloads($albumfolder=NULL) {
	global $_zp_gallery;
	if(function_exists('printDownloadlist') && !is_null($albumfolder)) {
		$albobj = new Album($_zp_gallery,$albumfolder);
		$subalbs = $albobj->getAlbums(0, null, null, true, true);
		if($subalbs) {
			foreach($subalbs as $subalb) {
				$subobj = new Album($_zp_gallery,$subalb);
				echo '<div class="logodownloadwrapper">';
				$albumthumb = $subobj->getAlbumThumbImage();
				echo '<span><img src="'.$albumthumb->getCustomImage(200, NULL, NULL, NULL, NULL, NULL, NULL, false, NULL).'" alt="" /></span><h4>'.$subobj->getTitle().'</h4>';
			  printDownloadlist('albums/'.$subalb,'ol',array(),'jpg');
				//printDownloadLinkAlbumZip('All above as .zip',$subobj);
				echo '</div>';
				echo '<br style="clear: left" />';
				echo '<hr />';
			}
		}
	}
}

/**
 * Prints "Originally introduced with Zenphoto version x.x.x" based on the extension article date which is compared with the dates of
 * of the changelog entries (for some older stuff from before being added to the old WP site wayback it might not be accurat!).
 */
function zp_printZenphotoVersionOfExtensionOrTheme($before='') {
	global $_zp_gallery_page, $_zp_current_zenpage_news,$_zp_current_album;
	$changelogtitle = '';
	if(!is_null($_zp_current_zenpage_news) || !is_null($_zp_current_album)) {
		switch($_zp_gallery_page) {
			case 'news.php':
				if($_zp_current_zenpage_news->inNewsCategory('extensions')) {
					$catobj = new ZenpageCategory('changelog');
					$changelog_articles = $catobj->getArticles('',true,true,"date", "desc",false);
					foreach($changelog_articles as $article) {
						$newsobj = new ZenpageNews($article['titlelink']);
						//echo $newsobj->getDatetime()."/".$_zp_current_zenpage_news->getDatetime();
						if($newsobj->getDatetime() < $_zp_current_zenpage_news->getDatetime()) {
							$changelogtitle = $newsobj->getTitle();
							if(strrchr($changelogtitle,'Version')) {
								$changelogtitle = substr($changelogtitle,8);
								$changelogtitle = substr($changelogtitle,0,5);
								$changelogtitle = $changelogtitle;
							}
							if(strrchr($changelogtitle,'Zenphoto')) {
								$changelogtitle = substr($changelogtitle,9);
							}
							$changelogtitle = $before.'Originally introduced with Zenphoto '.$changelogtitle;
							break;
						}
					}
				}
				break;
			case 'album.php':
			case 'image.php':
				if($_zp_current_album->getParent() && $_zp_current_album->getParent()->name == 'theme') {
					$catobj = new ZenpageCategory('changelog');
					$changelog_articles = $catobj->getArticles('',true,true,"date", "desc",false);
					foreach($changelog_articles as $article) {
						$newsobj = new ZenpageNews($article['titlelink']);
						//echo $newsobj->getDatetime()."/".$_zp_current_album->getDatetime();
						if($newsobj->getDatetime() < $_zp_current_album->getDatetime()) {
							$changelogtitle = $newsobj->getTitle();
							if(strrchr($changelogtitle,'Version')) {
								$changelogtitle = substr($changelogtitle,8);
								$changelogtitle = substr($changelogtitle,0,5);
								$changelogtitle = $changelogtitle;
							}
							if(strrchr($changelogtitle,'Zenphoto')) {
								$changelogtitle = substr($changelogtitle,9);
							}
							$changelogtitle = $before.'Originally introduced with Zenphoto '.$changelogtitle;
							break;
						}
					}
				}
				break;
			}
	 echo $changelogtitle;
	}
}

/**
 * Prints the sidebar boxes for sponsores, paid support, donations and sharing
 */
function zp_printSidebarBoxes() {
	global $_zp_themeroot, $_zp_gallery_page, $_zp_current_album; 
	?>
	<hr />
	<?php
	zp_printSponsorAds(true); //platinum (index) or palladium (sidebar) sponsors
	?>
	<div class="infobox paidsupport">
		<img src="<?php echo $_zp_themeroot; ?>/images/icon-forum.png" alt="" />
  	<h3>Need project help?</h3>
  	<p>Visit the <?php printPageLinkURL('Paid support page', 'paid-support','','',NULL); ?>.
  	</p>
  	<br clear="left" />
	</div>
	<div class="infobox"> 
		<h3>Like using Zenphoto? Donate!</h3>
    <p>Your support helps pay for this server, and helps development of zenphoto. Thank you!</p>
    <p>Visit the <a href="http://www.zenphoto.org/pages/donations">donations page</a></p>
  </div>
  <?php if(!is_NewsArticle() && $_zp_gallery_page != 'image.php') { ?>
    <div class="infobox"> 
    	<h3>Share!</h3>
     	<?php zp_printAddthis(); ?>
		</div> 
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
	<?php } 
}

/**
 * Prints the theme download button depending of external (3rd party), hosted or abandoned
 */
function zp_printThemeDownloadButton() {
	global $_zp_themeroot, $_zp_gallery, $_zp_current_album;
	if(!$_zp_current_album->hasTag('theme-officially-supported') && zp_getParentAlbumName() == "theme") {
  	if(zp_getParentAlbumName() == "theme" && $_zp_current_album->hasTag('hosted_theme')) {
			$linktext = 'Download on GitHub.com';
			$theme = explode('/',$_zp_current_album->name);
			$themeurl = 'https://github.com/zenphoto/Unsupported/tree/master/themes/';	
			echo '<p class="articlebox-left"><strong>Please note:</strong> It is not possible to download individual themes from the GitHub repository. You have to download the full repository (click on ZIP) and sort out what you need yourself.</p>';
		} else {
			$linktext = 'Info/download (external)';
			$themeurl = $_zp_current_album->getLocation();
		}
		if($_zp_current_album->hasTag('theme-abandoned')) {
			echo '<p><strong>Sorry, this theme is no longer provided by its developer.</strong></p>';
		} else {
			echo '<div class="buttons"><a href="' . $themeurl . '"><img src="'.$_zp_themeroot.'/images/arrow_right_blue_round.png" alt="" /> '.$linktext.'</a></div>';
		}
  }
  if($_zp_current_album->hasTag('theme-officially-supported')) {?>
		<p class="articlebox">Included in the Zenphoto release.</p>
	<?php 
  }
}

/**
 * Prints the extension download button depending of external (3rd party), hosted or abandoned
 */
function zp_printExtensionDownloadButton() {
	global $_zp_current_zenpage_news,$_zp_themeroot;
	if(zp_inNewsCategory("extensions")) {
		$customdata = $_zp_current_zenpage_news->getCustomData();
		$exturl = $customdata;
		$linkicon_url = '';
		if(zp_inNewsCategory("officially-supported")) {
			$linktext = 'Usage information'; 
			$linkicon_url = $_zp_themeroot.'/images/info_green.png'; 
			$exturl = 'http://www.zenphoto.org/documentation/li_plugins.html';
		} else {
			$githubtext = '<p class="articlebox-left"><strong>Please note:</strong> It is not possible to download individual extensions from the GitHub repository. You have to download the full repository (click on ZIP) and sort out what you need yourself.</p>';
			if(zp_inNewsCategory("unsupported-plugin-github")) {
			//if($_zp_current_zenpage_news->hasTag('hosted_extension')) {
				$linktext = 'Download on GitHub.com';
				$linkicon_url = $_zp_themeroot.'/images/arrow_right_blue_round.png'; 
				//$exturl = getDownloadLink('uploaded/extensions/'.$_zp_current_zenpage_news->getTitlelink().'.zip');
				$exturl = 'https://github.com/zenphoto/Unsupported/tree/master/plugins/';
				echo $githubtext;
			} else if (zp_inNewsCategory("unsupported-misc-github")) {
				$linktext = 'Download on GitHub.com';
				$linkicon_url = $_zp_themeroot.'/images/arrow_right_blue_round.png'; 
				//$exturl = getDownloadLink('uploaded/extensions/'.$_zp_current_zenpage_news->getTitlelink().'.zip');
				$exturl = 'https://github.com/zenphoto/Unsupported/tree/master/misc/';
				echo $githubtext;
			} else {
				$linktext = 'Info/download (external)';
				$linkicon_url = $_zp_themeroot.'/images/arrow_right_blue_round.png'; 
			}
		}  		
		if($_zp_current_zenpage_news->hasTag('extension-abandoned')) {
			echo '<p><strong>Sorry, this extension is no longer provided by its developer.</strong></p>';
		} else {
			echo '<p class="buttons"><a href="'.html_encode($exturl).'"><img src="'.$linkicon_url.'" alt="" /> '.$linktext.'</a></p>';
		} 	
		if($_zp_current_zenpage_news->inNewsCategory("officially-supported")) {  ?>
			<p class="articlebox">Included in the Zenphoto release.</p>
		<?php 
		} 
	} 
}


function zp_printMainSectionCategoryTitle() {
	global $_zp_current_category;
	if(!is_null($_zp_current_category)) {
		$articlecount = ' <small>('.count($_zp_current_category->getArticles(0)).')</small>';
	}
	if(zp_inNewsCategory('extensions'))  {
		echo "Extensions";
		if(!is_NewsArticle() && $_zp_current_category->getTitlelink() == 'extensions') {
			echo $articlecount;
		}
		if(!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() != 'extensions') {
			echo ' &raquo; '.$_zp_current_category->getTitle().$articlecount;
		}
	} else if(zp_inNewsCategory('user-guide')) {
		echo "User guide ";
		if(!is_NewsArticle() && $_zp_current_category->getTitlelink() == 'user-guide') {
			echo $articlecount;
		}
		if(!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() != 'user-guide') {
			echo ' &raquo; '.$_zp_current_category->getTitle().$articlecount;
		}
	} else if(!zp_inNewsCategory('extensions') || zp_inNewsCategory('user-guide')) { 
		echo "News";
		if(!is_NewsArticle() && !is_null($_zp_current_category) && !is_NewsArticle() && $_zp_current_category->getTitlelink() != 'news') {
			echo ' &raquo; '.$_zp_current_category->getTitle();
		}
	}
	if(is_NewsArchive()) {
		echo ': '.getCurrentNewsArchive('plain');
	} 
}

/**
 * Prints the ads on the sponsors album page and the platinum sidebar ones. If no sponsor is set default placeholders from the album "storage/sponsor-placeholders" are used.
 */
function zp_printSponsorAds($sponsorplatinum=false) {
	global $_zp_themeroot, $_zp_gallery, $_zp_current_album, $_zp_gallery_page;
	if($sponsorplatinum) {
		switch($_zp_gallery_page) {
			case 'index.php':
				$albums = array('hosting/platinum');
				break;
			default: 
				$albums = array('hosting/palladium');
				break; 
		}
	} else {
		$albums = array('hosting/gold','hosting/silver','hosting/bronze');
	}
	foreach($albums as $album) {
		$albobj = new Album(NULL,$album);
		$imagescount = $albobj->getNumImages();
			if($sponsorplatinum) { ?>
				<p class="ad-headline">Advertisements</p>
			<?php } else { ?>
				<!-- <h4 class="ad-headline"><?php echo html_encode($albobj->getTitle()); ?></h4> -->
			<?php }
			$adheight = 130;
			switch($album) {
				case 'hosting/platinum':
				case 'hosting/palladium':
					$adwidth = 275;
					$maxnum = 2;
					switch($album) {
						case 'hosting/platinum':
					 		$imgclass = 'sponsor-platinum';
					  	break;
						case 'hosting/palladium':
							$imgclass = 'sponsor-palladium';
							break;
					}
					break;	
				case 'hosting/gold': 
					$adwidth = 560;
					$maxnum = 99;
					$imgclass = 'sponsor-gold';
					break;
				case 'hosting/silver': 
					$adwidth = 270;
					$maxnum = 2;
					break;
				case 'hosting/bronze': 
					$adwidth = 125;
					$maxnum = 4;
					break;
			}
		if($imagescount != 0) {
			$images = $albobj->getImages(0,0,null,null, true, true);
			$count = '';
			foreach($images as $image) {
				$count++;
				$imgobj = newImage($albobj,$image);
				switch($album) {
					case 'hosting/platinum':
						$imgclass = 'sponsor-platinum';
						$linkclass = 'platinum-ad';
						if($count == 1) {
							$imgclass .= ' sponsor-platinum-first';
						} 
						break;
					case 'hosting/palladium':
						$imgclass = 'sponsor-palladium';
						$linkclass = 'palladium-ad';
						if($count == 1) {
							$imgclass .= ' sponsor-palladium-first';
						} 
						break;
					case 'hosting/gold': 
							$linkclass = 'gold-ad';
							break;
					case 'hosting/silver': 
						$imgclass = 'sponsor-silver';
						$linkclass = 'silver-ad';
						if($count < 2) {
							$imgclass .= ' sponsor-right';
						}
						break;
					case 'hosting/bronze': 
						$imgclass = 'sponsor-bronze';
						$linkclass = 'bronze-ad';
						if($count != 4) {
							$imgclass .= ' sponsor-right';
						}
						//echo $count."/".$imgclass;
						break;
				}
				if(isImagePhoto($imgobj)) {
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
		if($imagescount != $maxnum && ($album == 'hosting/platinum' || $album == 'hosting/palladium')) {
		//if($imagescount != $maxnum) {
			?>
			<?php
			$link = 'http://www.zenphoto.org/pages/advertise';
			if($imagescount == 0) {
				$max = $maxnum;
			} else {
				$max = $maxnum-$imagescount;
			}
			$placeholderalb = new Album('','storage/sponsor-placeholders');
			for($i=1;$i <= $max;$i++) {
				switch($album) {
					case 'hosting/platinum':
						$imgclass = 'sponsor-platinum';
						if($i == 1) {
							$imgclass .= ' sponsor-platinum-first';
						} 
						$placeholderimg = newImage($placeholderalb,'sponsorplatinum-placeholder-ad.gif');
						break;
					case 'hosting/palladium':
						$imgclass = 'sponsor-platinum';
						if($i == 1) {
							$imgclass .= ' sponsor-platinum-first';
						} 
						$placeholderimg = newImage($placeholderalb,'sponsorpalladium-placeholder-ad.gif');
						break;
				/*s
					case 'hosting/gold': 
						$placeholderimg = newImage($placeholderalb,'sponsorgold-placeholder.gif');
						break;
					case 'hosting/silver': 
						$imgclass = 'sponsor-silver';
						if($i < $max) {
							$imgclass .= ' sponsor-right';
						}
						$placeholderimg = newImage($placeholderalb,'sponsorsilver-placeholder.gif');
						break;
					case 'hosting/bronze': 
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
		if($album == 'hosting/silver') { ?>
			<br style="clear:left" />
		<?php
		} else if($album == 'hosting/platinum' || $album == 'hosting/palladium') {
			echo '<hr />';
		}
	} // album loop
}


function zp_printSponsorAvailability() {
	$obj = new Album(NULL,'hosting');
	$albums = $obj->getAlbums(0);
	?>
	<hr />
	<h3>Ad space availability</h3>
	<ul>
	<?php
	foreach($albums as $album) {
		$albobj = new Album($obj,$album);
		$imagescount = $albobj->getNumImages();
		switch($album) {
				case 'hosting/platinum':
				case 'hosting/palladium':
				case 'hosting/silver': 
					$maxnum = 2;
					break;
				case 'hosting/gold': 
				  $maxnum = 'Unlimited';
				  break;
				case 'hosting/bronze': 
					$maxnum = 4;
					break;
		}
		if($album == 'hosting/gold') {
			$max = $maxnum;
		} else {
			if($imagescount != $maxnum) {
				$max = $maxnum-$imagescount;
			} else {				
				$max = 'not available currently';
			} 
		}
		?>
		<li><?php echo $albobj->getTitle(); ?>: <?php echo $max; ?></li>
		<?php
	}
	?>
	</ul>
	<?php
}


/**
 * Requires the official related_items plugin to be enabled (does not print anything if not or nothing related available)
 * Custom category/section display version for zenphoto.org only. Besides that the same as the official plugin function-
 * Prints the x related articles based on a tag search. 
 *
 * @param int $number Number of items to get
 * @param string $type 'albums', 'images','news','pages'
 * @param string $specific If $type = 'albums' or 'images' name of album, if $type = 'news' name of category
 */
function zp_printRelatedItems($number=5,$type='news',$specific=NULL) {
	global $_zp_gallery, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news;
	$result = array();
	if(function_exists('getRelatedItems')) {
		$result = getRelatedItems($type,$specific);
	}
	if(count($result) > 1) { // because one always gets found, the item itself!
		?>
		<h3 class="relateditems"><?php echo gettext('Related items'); ?></h3>
		<ul id="relateditems">
		<?php
		$count = '';
		foreach($result as $item) {
			switch($type) {
				case 'albums':
					$obj = new Album($_zp_gallery,$item);
					$objname = $item;
					$currentname = $_zp_current_album->name;
					break;
				case 'images':
					$obj = newImage($_zp_current_album,$item);
					$objname = $item;
					$currentname = $_zp_current_image->filename;
					break;
				case 'news':
					$obj = new ZenpageNews($item['name']);
					$objname = $obj->getTitlelink();
					$currentname = $_zp_current_zenpage_news->getTitlelink();
					break;
				case 'pages':
					$obj = new ZenpagePage($item['name']);
					$objname = $obj->getTitlelink();
					$currentname = $_zp_current_zenpage_page->getTitlelink();
					break;
			}
			if($objname != $currentname) { // avoid listing the item itself
				$count++;
				?>
				<li>
				<?php
					$category = '';
					switch($type) {
						case 'albums':
							$url = $obj->getAlbumLink();
							break;
						case 'images':
							$url = $obj->getImageLink();
							break;
						case 'news':
							$url = getNewsURL($obj->getTitlelink());
							if($obj->inNewsCategory('extensions')) {
								$category = '<small> (Extensions)</small>';
							} else if($obj->inNewsCategory('user-guide')) {
								$category = '<small> (User guide)</small>';
							} else if($obj->inNewsCategory('news')) {
								$category = '<small> (News)</small>';
							}
							break;
						case 'pages':
							$url = getPageLinkURL($obj->getTitlelink());
							break;
					}
				?>
				<a href="<?php echo html_encode($url); ?>" title="<?php echo html_encode($obj->getTitle()); ?>"><?php echo html_encode($obj->getTitle()); ?></a><?php echo $category; ?>
				</h4></li>
				<?php
			} // if object not current
			if($count == $number) {
				break;
			}
		} // foreach
		?>
		</ul>
		<?php
	} else { // if result
		//echo "Sorry, no related items found";
	}
}

/**
 * Prints the troubleshooting guide collector content. This function is used in codeblock2 of the troubleshooting guide
 *
 */
function zp_printTroubleshootingGuide() {
	global $_zp_current_category;
	?>
	<script type="text/javascript">
		var state = 'hide';
		function accordian() {
			switch (state) {
			 case 'hide':
				$('.body').show();
				state = 'show';
				$('#show_hide').html('Collapse all');
				break;
			case 'show':
				$('.body').hide();
				state = 'hide';
				$('#show_hide').html('Expand all');
				break;
			}
		}
	</script>
	<p class="buttons"><a href="javascript:accordian()"><span id="show_hide">Expand all</span></a></p>
	<br clear="left" />
	<?php
	$currentcat = $_zp_current_category;
	$catobj = new ZenpageCategory('troubleshooting');
	$cats = $catobj->getSubCategories();
	?>
	<ol class="index">
	<?php
	foreach ($cats as $key=>$cat) {
		$catobj = new ZenpageCategory($cat);
		$_zp_current_category = $catobj;
		$articles = $catobj->getArticles();
		if (!empty($articles)) {
			$h4 = $catobj->getTitle();
			?>
			<li><a href="#<?php echo $catobj->getTitlelink(); ?>"><?php echo $h4; ?></a></li>
			<?php
		} else {
			unset($cats[$key]);
		}
	}
	?>
	</ol>
	<?php
	foreach ($cats as $cat) {
		$catobj = new ZenpageCategory($cat);
		$h4 = $catobj->getTitle();
		?>
		<h4><a name="<?php echo $catobj->getTitlelink(); ?>"></a><?php echo $h4; ?></h4>
		<?php
		zp_listTroubleshootingArticles($catobj);
	}
} 
/**
 * Helpder function to zp_printTroubleshootingGuide(). This prints the individual troubleshooting section on the troubleshooting guide article
 *
 * @param obj $cat category object
 */
function zp_listTroubleshootingArticles($cat) {
	global $counter, $_zp_current_category;
	$_zp_current_category = $cat;
	$articles = $cat->getArticles();
	if (!empty($articles)) {
		?>
		<ol class="trouble">
		<?php
		foreach ($articles as $titlelink) {
			$titlelink = $titlelink['titlelink'];
			$article = new ZenpageNews($titlelink);
			$counter ++;
			?>
				<li>
				<h5><a name="<?php echo $titlelink; ?>"><a href="javascript:toggle('article_<?php echo $counter; ?>');"><?php echo $article->getTitle(); ?></a></h5>
				<div id="article_<?php echo $counter; ?>" style="display:none;" class="body">
					<?php echo $article->getContent(); ?>
					<p><a href="<?php echo $article->getNewsLink(); ?>">Direct link</a></p>
				</div>

				<?php 
				?>
			</li>
			<?php
		}
		?>
		</ol>
		<?php
	}
	$_zp_current_category = $cat;
}
?>