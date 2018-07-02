<?php

class zporg {
	/* gets the tags prefixed with "author_", "pluginsupport_" and "zp_" (author/contributor status tags)
	 *
	 * @param string $mode 'item' (single album or article only) or 'all'
	 * @param string $tagmode 'author', "status', 'pluginsupport'
	 * @return array
	 */

	static function getSpecificTags($mode = 'item', $tagmode = 'author', $obj = null) {
		global $_zp_gallery_page, $_zp_current_album, $_zp_current_zenpage_news, $_zp_current_zenpage_page;
		switch ($mode) {
			case 'item':
				switch ($_zp_gallery_page) {
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
		switch ($tagmode) {
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
		if (!empty($tags)) {
			foreach ($tags as $tag) {
				if (stristr($tag, $search_text)) {
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

	static function getAuthorTags($mode = 'item') {
		return self::getSpecificTags($mode, 'author');
	}

	/* Prints the tag search link for this author $tag
	 *
	 */

	static function printReadmoreOfAuthorLink($tag = '', $count = '') {
		echo '<a href="' . html_encode(getSearchURL($tag, '', 'tags', '')) . '" title="' . html_encode($tag) . '">' . ucwords(substr($tag, 7)) . '</a>';
	}

	/* Prints a list of "More by author:xxx" tag search links for all authors assigned to this item (album or article)
	 *
	 */

	static function printMoreByAuthorsLinks() {
		$tags = self::getAuthorTags('item');
		if (!empty($tags)) {
			$tagcount = getAllTagsCount();
			?>
			<hr />
			<h3 class="relateditems">More by author:</h3>
			<ul class="morebyauthorlist">
				<?php
				foreach ($tags as $tag) {
					$count = $tagcount[$tag];
					if ($count > 1) {
						?>
						<li><?php self::printReadmoreOfAuthorLink($tag); ?> (<?php echo $count; ?>)</li>
						<?php
					} else {
						?>
						<li>Nothing else available by <?php echo ucwords(substr($tag, 7)); ?></li>
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

	static function getMoreByThisAuthor($tag = '', $mode = 'all') {
		global $_zp_gallery_page;
		$result = array();
		$search = new SearchEngine();
		$searchstring = $tag;
		$paramstr = urlencode('words') . '=' . $searchstring . '&searchfields=tags';
		$searchparams = $search->setSearchParams($paramstr);
		switch ($mode) {
			case 'albums':
				$result = $search->getAlbums(0, "date", "desc");
				break;
			case 'news':
				$result = $search->getArticles(0, NULL, true, "date", "desc");
				break;
			case 'all':
				$result1 = $search->getAlbums(0, "date", "desc");
				$result2 = $search->getArticles(0, NULL, true, "date", "desc");
				//$result = array_merge($result1,$result2);
				break;
		}
		if (function_exists('createRelatedItemsResultArray')) {
			switch ($mode) {
				case 'albums':
					$result = createRelatedItemsResultArray($result, $mode);
					break;
				case 'news':
					$result = createRelatedItemsResultArray($result, $mode);
					break;
				case 'all':
					$result1 = createRelatedItemsResultArray($result1, 'albums');
					$result2 = createRelatedItemsResultArray($result2, 'news');
					$result = array_merge($result1, $result2);
					$result = sortMultiArray($result, 'name', false, true, false, false); // sort by name abc
					break;
			}
		}
		return $result;
	}

	/* custom mod of printRelatedItems()	for printing items with the tag author_xxxx
	 * @param string $tag author tag to use
	 * @param string $mode 'albums' or 'news' or 'all' for both
	 * @param string $mode2 'extensions' (default), 'user-guide' or 'release'
	 * @return array
	 */

	static function getAuthorContributions($tag, $mode, $mode2 = 'extensions') {
		global $_zp_gallery, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news;
		$result = self::getMoreByThisAuthor($tag, $mode);
		//echo "<pre>"; print_r($result); echo "</pre>";
		if ($mode == 'news') {
			if ($mode2 == 'extensions' || $mode2 == 'user-guide' || $mode2 == 'release') {
				$resultnew = array();
				if (!empty($result)) {
					foreach ($result as $item) {
						$i = new ZenpageNews($item['name']);
						if ($i->inNewsCategory($mode2)) {
							$resultnew[] = $item;
						}
					}
				}
			}
			$descending = false;
			if ($mode2 == 'release') {
				$descending = true;
			}
			$resultnew = sortMultiArray($resultnew, 'name', $descending, true, false, false); // sort by name abc
			return $resultnew;
		}
		$result = sortMultiArray($result, 'name', false, true, false, false); // sort by name abc
		return $result;
	}

	/* custom mod of printRelatedItems()	for printing items with the tag author_xxxx
	 * @param string $tag author tag to use
	 * @param string $mode 'albums' or 'news' or 'all' for both
	 * @param string $mode2 'extensions' (default), 'user-guide' or 'release'
	 * @return array
	 */

	static function printAuthorContributions($tag, $mode, $mode2 = 'extensions') {
		global $_zp_gallery, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news;
		$thumb = false;
		$result = self::getAuthorContributions($tag, $mode, $mode2);
		$resultcount = count($result);
		if ($resultcount != 0) {
			switch ($mode) {
				case 'news':
					switch ($mode2) {
						case 'extensions':
							?>
							<h4>Extensions contributions (<?php echo $resultcount; ?>)</h4>
							<?php
							break;
						case 'user-guide':
							?><h4>User guide contributions (<?php echo $resultcount; ?>)</h4><?php
							break;
						case 'release':
							?><h4>Release contributions (<?php echo $resultcount; ?>)</h4>
							<p>Bugfix releases (1.x.x.x) are not listed.</p>
							<?php
							break;
					}
					break;
				case 'albums':
					?>
					<h4>Theme contributions (<?php echo $resultcount; ?>)</h4>
					<?php
					break;
			}
			?>
			<ol class="contributionslist">
				<?php
				foreach ($result as $item) {
					$category = '';
					switch ($item['type']) {
						case 'albums':
							$obj = newAlbum($item['name']);
							$url = $obj->getLink();
							$text = $obj->getDesc();
							break;
						case 'news':
							$obj = new ZenpageNews($item['name']);
							$url = $obj->getLink();
							$text = $obj->getContent();
							break;
					}
					?>
					<li class="<?php echo $item['type']; ?>">
						<?php
						if ($thumb) {
							$thumburl = false;
							switch ($item['type']) {
								case 'albums':
									$thumburl = $obj->getThumb();
									break;
							}
							if ($thumburl) {
								?>
								<a href="<?php echo html_encode($url); ?>" title="<?php echo html_encode($obj->getTitle()); ?>" class="contributions_thumb">
									<img src="<?php echo html_encode($thumburl); ?>" alt="<?php echo html_encode($obj->getTitle()); ?>" />
								</a>
								<?php
							}
						}
						?>
						<h4><a href="<?php echo html_encode($url) ?>" title="<?php echo html_encode($obj->getTitle()); ?>"><?php echo html_encode($obj->getTitle()); ?></a>
							<?php
							switch ($item['type']) {
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
							switch ($item['type']) {
								case 'albums':
									self::printThemeStatusIcon($obj);
									break;
								case 'news':
									self::printExtensionStatusIcon($obj);
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
	static function printAuthorStatusIcon($obj = NULL) {
		global $_zp_current_zenpage_page, $_zp_gallery_page, $_zp_themeroot;
		if (is_null($obj)) {
			$obj = $_zp_current_zenpage_page;
		}
		$icon = '';
		if (is_object($obj)) {
			if ($obj->hasTag('zp_team-member')) {
				$icon = '<img class="authoricon" src="' . $_zp_themeroot . '/images/authoricon_developer.png" alt="" title="Official Zenphoto team member" />';
			}
			if ($obj->hasTag('zp_team-member-former')) {
				$icon = '<img class="authoricon" src="' . $_zp_themeroot . '/images/authoricon_former.png" alt="" title="Former Zenphoto team member" />';
			}
			echo $icon;
		}
	}

	/**
	 * Prints the subline for an author entry with team rank and roles
	 *
	 */
	static function printAuthorStatusRanks($obj = NULL) {
		if (is_null($obj)) {
			$statustags = self::getSpecificTags('item', 'status');
		} else {
			$statustags = $obj->getTags();
		}
		?>
		<ul class="authorstatusrank">
			<?php
			if (in_array('zp_team-member', $statustags)) {
				echo "<li><strong>Zenphoto team member</strong></li>";
			} else if (in_array('zp_team-member-former', $statustags)) {
				echo "<li><strong>Former Zenphoto team member</strong></li>";
			} else {
				echo "<li>Zenphoto contributor</li>";
			}
			$count = '';
			foreach ($statustags as $status) {
				if ($status != 'zp_team-member' && $status != 'zp_team-member-former' && $status != 'zp_contributor') {
					$count++;
					?>
					<li>
						<?php
						echo ", ";
						echo ucfirst(str_replace(array('zp_', '-'), array('', ' '), $status));
						?></li><?php
				}
			}
			?>
		</ul>
		<?php
	}

	/* Prints a list of all contribitor profile pages (all subpages of the page "all-contributors")
	 * @param string $mode 'all', 'teammembers', "formermembers', 'contributors'
	 */

	static function printAuthorList($mode = 'all', $content = false) {
		global $_zp_current_zenpage_page;
		if (!is_null($_zp_current_zenpage_page) && $_zp_current_zenpage_page->getTitlelink() == 'all-contributors') {
			$subpages = $_zp_current_zenpage_page->getPages();
		} else {
			$page = new ZenpagePage('all-contributors');
			$subpages = $page->getPages();
		}
		// Workaround to get an alphabetically list by name
		if ($mode == 'all' || $mode == 'contributors') {
			$sorted = array();
			foreach ($subpages as $subpage) {
				$obj = new Zenpagepage($subpage);
				$explode = explode(' ', $obj->getTitle());
				if ($explode) {
					// if we have normal names with probably only a 2nd surname (e.g. Nils K. Windisch) use the name
					if (count($explode) <= 3) {
						$explode = array_reverse($explode);
						$name = $explode[0];
					}
				} else {
					// otherwise we just use the alias no matter how it begins, e.g. "The Whole Life to Learn"
					$name = $obj->getTitle();
				}
				$sorted[] = array('titlelink' => $obj->getTitlelink(), 'name' => $name);
			}
			$sorted = sortMultiArray($sorted, 'name', false, true, false, false); // sort by name abc
			$subpages = $sorted;
		}
		?>
		<ul class="authors">
			<?php
			foreach ($subpages as $subpage) {
				if ($mode == 'all' || $mode == 'contributors') {
					$obj = new Zenpagepage($subpage['titlelink']);
				} else {
					$obj = new Zenpagepage($subpage);
				}
				if ($mode == 'all' || ($mode == 'teammembers' && $obj->hasTag('zp_team-member')) || ($mode == 'formermembers' && $obj->hasTag('zp_team-member-former')) || ($mode == 'contributors' && $obj->hasTag('zp_contributor'))) {
					?>
					<li>
						<?php
						/* $mail = $obj->getCustomData();
						  if(!empty($mail)) {
						  $imgurl = self::getAuthorGravatarImage($mail,40);
						  if(!empty($imgurl)) {
						  echo $imgurl;
						  }
						  } */
						?>
						<h4 class="entrytitle"><a href="<?php echo $obj->getLink(); ?>" rel="author">
								<?php
								echo $obj->getTitle();
								if (strtolower($obj->getTitle()) != strtolower($obj->getTitlelink())) {
									?>
									<small><em>(<?php echo $obj->getTitlelink(); ?>)</em></small>
									<?php
								}
								?>
							</a><?php self::printAuthorStatusIcon($obj); ?>
						</h4>
						<div class="entrymeta">
							<?php self::printAuthorStatusRanks($obj); ?>
						</div>
						<?php if ($content) { ?>
							<div class="entrybody">
								<?php echo $obj->getContent(); ?>
								<p class="buttons">
									<a  href="<?php echo $obj->getLink(); ?>">
										<strong>Full profile</strong>
									</a>
								</p>
								<br style="clear: left" />
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

	/* Prints a list of all contribitor profile pages (subpages of "all-contributors")
	 * @param string $mode 'all', 'teammembers', "formermembers'
	 */

	static function printItemAuthorCredits() {
		global $_zp_current_zenpage_news, $_zp_current_album, $_zp_gallery_page, $_zp_themeroot;
		$parent = '';
		$parentname = '';
		$rightcat = false;
		switch ($_zp_gallery_page) {
			case 'news.php':
				if (is_NewsArticle() && ($_zp_current_zenpage_news->inNewsCategory('user-guide') || $_zp_current_zenpage_news->inNewsCategory('extensions') || $_zp_current_zenpage_news->inNewsCategory('release'))) {
					$rightcat = true;
				}
				break;
			case 'image.php':
			case 'album.php':
				$parent = $_zp_current_album->getParent();
				if ($parent) {
					$parentname = $parent->name;
				}
				break;
		}
		if ($rightcat || $parentname == 'theme') {
			$authors = self::getSpecificTags('item', 'author');
			$numauthors = count($authors);
			$creditplural = 'Developers:';
			$creditsingular = 'Developer:';
			if ($_zp_gallery_page == 'news.php') {
				if ($_zp_current_zenpage_news->inNewsCategory('user-guide')) {
					$creditplural = 'Authors:';
					$creditsingular = 'Author:';
				} else if ($_zp_gallery_page == 'news.php' && $_zp_current_zenpage_news->inNewsCategory('release')) {
					$creditplural = 'Contributors:';
					$creditsingular = 'Contributor:';
				}
			}
			if ($numauthors != 0) {
				if ($numauthors > 1) {
					$credit = $creditplural;
				} else {
					$credit = $creditsingular;
				}
				$page = new ZenpagePage('all-contributors');
				$subpages = $page->getPages();
				$subpages_new = array();
				foreach ($subpages as $subpage) {
					$subpages_new[] = $subpage['titlelink'];
				}
				$subpages = $subpages_new;
				unset($subpages_news);
				?>
				<div id="authorcredits" class="table_of_content_list">
					<h4><?php echo $credit; ?> </h4>
					<ul>
						<?php
						// Workaround to get an alphabetically list by name
						$sorted = array();
						$count = '';
						$name = '';
						foreach ($authors as $author) {
							$count++;
							$author = str_replace('author_', '', $author);
							$icon = '';
							if (in_array($author, $subpages)) {
								$obj = new ZenpagePage($author);
								if ($obj->hasTag('zp_team-member')) {
									$icon = '<img class="authoricon_credits" src="' . $_zp_themeroot . '/images/authoricon_developer-small.png" alt="Zenphoto team member" />';
								} else if ($obj->hasTag('zp_team-member-former')) {
									$icon = '<img class="authoricon_credits" src="' . $_zp_themeroot . '/images/authoricon_former-small.png" alt="Zenphoto former team member" />';
								}
								$explode = explode(' ', $obj->getTitle());
								if ($explode) {
									// if we have normal names with probably only a 2nd surname (e.g. Nils K. Windisch) use the name
									if (count($explode) <= 3) {
										$explode = array_reverse($explode);
										$name = $explode[0];
									}
								} else {
									// otherwise we just use the alias no matter how it begins, e.g. "The Whole Life to Learn"
									$name = $obj->getTitle();
								}
								$sorted[] = array('title' => $obj->getTitle(), 'titlelink' => $obj->getTitlelink(), 'name' => $name, 'status_icon' => $icon);
							} else {
								$sorted[] = array('title' => $author, 'titlelink' => '', 'name' => $author, 'status_icon' => $icon);
							}
						}
						$sorted = sortMultiArray($sorted, 'name', false, true, false, false); // sort by name abc
						foreach ($sorted as $p) {
							?>
							<li>
								<?php
								if (!empty($p['titlelink']) && strtolower($p['titlelink']) != strtolower($p['title'])) {
									$link = $p['title'] . ' <em>(' . $p['titlelink'] . ')</em>' . $p['status_icon'];
								} else {
									$link = $p['title'] . $p['status_icon'];
								}
								if (empty($p['titlelink'])) {
									echo $link;
								} else {
									echo $link = '<a href="' . getPageURL($p['titlelink']) . '" rel="author">' . $link . '</a>';
								}
								?>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
				<?php
			}
		}
	}

	/**
	   * Get the full Gravatar profile data as an array.
	   *
	   * @param string $email The email address
	   * @return array containing the profile data
	   * @source http://en.gravatar.com/site/implement/profiles/php/
	    */
	static function getAuthorGravatarProfileData($email) {
		if (!empty($email)) {
			$hash = md5(strtolower(trim($email)));
			$str = file_get_contents('http://www.gravatar.com/' . $hash . '.php');
			$profile = unserialize($str);
			if (is_array($profile) && isset($profile['entry'])) {
				return $profile;
			}
		}
	}

	/**
	   * Prints data from the Gravatar profile
	   * @param string $userid The Gravatar regsitered e-mail address or a pre-generated md5 hash of it
	   * @param string $field What to print 'thumbnail', 'aboutme', 'urls', 'all' (all in this order)
	   * @return gets the profile data as html elements
	   * @source http://en.gravatar.com/site/implement/profiles/php/
	    */
	static function getAuthorGravatarProfile($userid, $field) {
		$profile = false;
		if (filter_var($userid, FILTER_VALIDATE_EMAIL)) {
			$hash = md5(strtolower(trim($userid)));
		} else { // we assume this is a valid md5 hash already
			$hash = $userid;
		}
		$str = file_get_contents('http://www.gravatar.com/' . $hash . '.php');
		$data = unserialize($str);
		if (is_array($data) && isset($data['entry'])) {
			$profile = $data;
		}
		if ($profile) {
			if (($field == 'thumb' || $field == 'all') && !empty($profile['entry'][0]['thumbnailUrl'])) {
				return '<img class="authorprofile-image" src="' . $profile['entry'][0]['thumbnailUrl'] . '?s=105" alt="" />';
			}
			if (($field == 'aboutme' || $field == 'all') && !empty($profile['entry'][0]['aboutMe'])) {
				return '<p class="authorprofile-text">' . $profile['entry'][0]['aboutMe'] . '</p>';
			}
			if (($field == 'urls' || $field == 'all') && count($profile['entry'][0]['urls']) != 0) {
				$websites = '
			<hr />
			<h4>Websites</h4>
			<ul class="authorprofile-links">
			';
				foreach ($profile['entry'][0]['urls'] as $url) {
					$websites .= '<li><a href="' . $url['value'] . '">' . $url['title'] . '</a></li>';
				}
				$websites .= '</ul>';
				return $websites;
			}
		}
	}

	/**
	   * Gets the Google/Google+ or Facebook profile image
	 * @param string $userid 	$type  	'gravatar': The md5 hash of the Gravarar registered e-mail address
	 * 																'google': The Google+ user id number as found in the url of the profile.
	 * 																					This MUST be passed as a string enclosed in quotes!
	 * 																'facebook': The facebook profile/page name found in the url of the profile.
	   * @param num $type type 'google', 'facebook'
	 * @param string $field What to print 'thumb', 'aboutme', 'urls', 'all' ("all" in this order) $type="gravatar" ONLY!
	 * @return html img
	    */
	static function getAuthorSocialImage($userid = '', $type = 'google', $field = 'thumbnail') {
		if (!empty($userid)) {
			switch ($type) {
				case 'gravatar':
					return self::getAuthorGravatarProfile($userid, $field);
					break;
				case 'google':
					$url = 'https://www.google.com/s2/photos/profile/' . $userid;
					return "<img src=$headers[Location]>";
					break;
				case 'facebook':
					$url = 'https://graph.facebook.com/' . $userid . '/picture?type=normal';
					return "<img src=$headers[Location]>";
					break;
			}
		}
	}

	/* Imports the authors from a .csv file (Format: realname|url|author_<screenname>|ranktags(tag/tag/...)|descriptions)
	 * @param string $csv The absolute path to the csv file to use
	 * @param bool $testmode true or false if really pages should be created
	 */

	static function importAuthorsFromCSV($csv, $testmode = true) {
		$cons = file($csv);
		if ($cons) {
			//echo "<pre>";print_r($cons); echo "</pre>";
			//break;
			foreach ($cons as $con) {
				$c = explode('|', $con);
				//echo "<pre>";print_r($c); echo "</pre>";
				// Create titlelink from author tag
				$titlelink = trim(str_replace('author_', '', $c[2]));

				// Just to be sure the page does not exists already
				$sql = 'SELECT `id` FROM ' . prefix('pages') . ' WHERE `titlelink`=' . db_quote($titlelink);
				$rslt = query_single_row($sql, false);
				if ($rslt) {
					echo "<p style='color: red'>Page " . $titlelink . " already exists</p>";
				} else {
					//initialize the page object
					if (!$testmode)
						$page = new ZenpagePage($titlelink, true);

					// Get title or use titlelink
					if (empty($c[0])) {
						$title = $titlelink;
					} else {
						$title = trim($c[0]);
					}

					if (!$testmode)
						$page->setTitle($title);
					$website = '';
					if (!empty($c[1])) {
						$website = '<p><a href="' . trim($c[1]) . '"><strong>Website: </strong>' . trim($c[1]) . '</a></p>';
					}

					// Get the tags
					if (!empty($c[3])) {
						$tags = trim(str_replace('/', ',', $c[3]));
						if (!$testmode)
							$page->setTags($tags);
					}

					if (empty($c[4])) {
						$content = $website;
					} else {
						$c[4] = trim(str_replace('"', '', $c[4]));
						$content = $c[4] . $website;
					}
					if (!$testmode) {
						$page->setContent($content);
						$page->setShow(0);
						$page->setDatetime(date('Y-m-d H:i:s'));
						$page->setParentID(170); // id of "all-contributors" page
						$page->save();
					}
					echo "<p>Page " . $titlelink . " created!</p>";
				}
				echo "<br>------------------";
				/*
				  // just a check to see if the array of the list match with the site
				  if(in_array(trim($c[2]),$authors)) {
				  echo '<li><strong>'.$c[2].'</strong></li>';
				  } else {
				  echo '<li>'.$c[2].'</li>';
				  } */
			}
		}
	}

	/* Prints the plugin support list based on "pluginsupport_<pluginname(titlelink of article)>" tags.
	 *
	 * @return array
	 */

	static function printPluginsupportTags() {
		if (self::getParentAlbumName() == "theme") {
			$tags = self::getSpecificTags('item', 'pluginsupport');
			?>
			<h4>Layout specific plugins supported:</h4>
			<?php
			if (!empty($tags)) {
				?>
				<ul class="morebyauthorlist">
					<?php
					foreach ($tags as $tag) {
						$tag = substr($tag, 14);
						?>
						<li>
							<?php
							if ($tag == 'zenpage') {
								$url = getNewsURL('zenpage-a-cms-plugin-for-zenphoto');
							} else {
								$url = getNewsURL($tag);
							}
							echo "<a href=\"" . html_encode($url) . "\" title=\"" . $tag . "\">" . $tag . "</a>";
							?>
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
	static function printNewsCategoryFoldout() {
		global $_zp_current_category;
		if (zporg::inNewsCategory('news') || is_null($_zp_current_category)) {
			$cat_news = new ZenpageCategory('news');
			?>
			<hr />
			<h2>Categories</h2>
			<ul class="newscategories">
				<?php
				$allnews = '';
				if (is_null($_zp_current_category)) {
					$allnews = ' class="active"';
				}
				echo '<li' . $allnews . '><a href="' . html_encode(getNewsIndexURL()) . '" title="All news">All news</a></li>';
				$active = '';
				if (!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == 'news') {
					$active = ' class="active"';
				}
				$count = ' <small>(' . count($cat_news->getArticles(0)) . ')</small>';
				echo '<li' . $active . '><a href="' . html_encode($cat_news->getLink()) . '" title="News">' . html_encode($cat_news->getTitle()) . $count . '</a>';
				$subcats = $cat_news->getSubCategories();
				if ($subcats) {
					echo '<ul>';
					foreach ($subcats as $subcat) {
						$subobj = new ZenpageCategory($subcat);
						$active2 = '';
						$articleCount = count($subobj->getArticles(0));
						if ($articleCount != 0) {
							if (!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == $subobj->getTitlelink()) {
								$active2 = ' class="active"';
							}
							$count = ' <small>(' . $articleCount . ')</small>';
							echo '<li' . $active2 . '><a href="' . $subobj->getLink() . '" title="' . html_encode($subobj->getTitle()) . '">' . html_encode($subobj->getTitle()) . $count . '</a></li>';
						}
					}
					echo '</ul>';
				}
				echo '</li>';
				$countcat = new ZenpageCategory('user-guide');
				$count = ' <small>(' . count($countcat->getArticles(0)) . ')</small>';
				echo '<li><a href="' . html_encode(getNewsCategoryURL('user-guide')) . '" title="User guide">User guide' . $count . '</a></li>';
				$countcat = new ZenpageCategory('extensions');
				$count = ' <small>(' . count($countcat->getArticles(0)) . ')</small>';
				echo '<li><a href="' . html_encode(getNewsCategoryURL('extensions')) . '" title="Extensions">Extensions' . $count . '</a></li>';
				echo '</ul>';
			}
		}

		/**
		 * Prints only the "user guide" or "extensions "subcategories as a sublist folded out.
		 * Difference to the standard function this is also printed if we are on a single article page
		 * Means an article should only be in one main category at the time ("news", "user-guide" or "extensions" - Do not mix!)
		 *
		 */
		static function printSubCategories($cattitlelink) {
			global $_zp_current_category;
			$cattitlelink = sanitize($cattitlelink);
			$currentcat = new ZenpageCategory($cattitlelink);
			$subcats = $currentcat->getSubCategories(true, 'title', false);
			if ($subcats) {
				?>
				<hr />
				<h2>Categories</h2>
				<ul class="newscategories">
					<?php
					foreach ($subcats as $subcat) {
						$subcatobj = new ZenpageCategory($subcat);
						$active = '';
						$articleCount = count($subcatobj->getArticles(0));
						if ($articleCount != 0) {
							if (!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() == $subcatobj->getTitlelink()) {
								$active = ' class="active"';
							}
							$count = ' <small>(' . $articleCount . ')</small>';
							echo '<li' . $active . '><a href="' . html_encode($subcatobj->getLink()) . '" title="' . html_encode($subcatobj->getTitle()) . '">' . html_encode($subcatobj->getTitle()) . $count . '</a></li>';
						}
					}
					echo '</ul>';
				}
			}

			/**
			 * Custom print latest news
			 *
			 */
			static function printLatestNews($number = 2, $option = 'none', $category = '') {
				if (empty($category)) {
					$latest = $_zp_zenpage->getArticles($number, NULL, true);
				} else {
					$catobj = new ZenpageCategory($category);
					$latest = $catobj->getArticles($number, NULL, true);
				}

				if (is_array($latest)) {
					echo "<ul>";
					foreach ($latest as $news) {
						$article = new ZenpageNews($news['titlelink']);
						?>
						<li>
							<a href="<?php echo html_encode($article->getLink()); ?>">
								<?php echo html_encode($article->getTitle()); ?>
							</a>
							<small> (<?php echo zpFormattedDate(getOption('date_format'), strtotime($article->getDatetime())); ?>)</small>
						</li>
						<?php
					}
					echo '</ul>';
				}
			}

			/**
			 * Prints a html list from an array of image/album items
			 */
			static function printGalleryStatistics($array, $option = 'date', $album = false) {
				echo '<ul>';
				foreach ($array as $top) {
					if ($album) {
						$image = $top->getImage(0);
						if (is_object($image)) {
							$link = $image->getLink();
						} else {
							$link = $top->getLink();
						}
					} else {
						$link = $top->getLink();
					}
					$extra = '';
					switch ($option) {
						case 'date':
							$extra = zpFormattedDate(DATE_FORMAT, strtotime($top->getDateTime()));
							break;
						case 'rating':
							$rating = '';
							$votes = $top->get("total_votes");
							$value = $top->get("total_value");
							if ($votes != 0) {
								$rating = round($value / $votes, 1);
							}
							$extra = sprintf(gettext('Rating: %1$u (Votes: %2$u)'), $rating, $votes);
							break;
					}
					echo '<li><a href="' . $link . '">' . $top->getTitle() . '</a> <small>(' . $extra . ')</small></li>';
				}
				echo '</ul>';
			}

			/**
			 * checks if the article is in a category on single article pages or if we are on a category page in general
			 *
			 */
			static function inNewsCategory($titlelink) {
				global $_zp_current_zenpage_news, $_zp_current_category;
				$currentcat = '';
				if (is_NewsCategory() && !is_null($_zp_current_category)) {
					$currentcat = $_zp_current_category->getTitlelink();
					if ($currentcat == $titlelink || $_zp_current_category->isSubNewsCategoryOf($titlelink)) {
						return TRUE;
					}
				}
				if (is_NewsArticle()) {
					if ($_zp_current_zenpage_news->inNewsCategory($titlelink) || $_zp_current_zenpage_news->inSubNewsCategoryOf($titlelink)) {
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
			static function printZDSearchToggleJS() {
				?>
				<script type="text/javascript">
					// <!-- <![CDATA[
					function toggleExtraElements(category, show) {
						if (show) {
							jQuery('.' + category + '_showless').show();
							jQuery('.' + category + '_showmore').hide();
							jQuery('.' + category + '_extrashow').show();
						} else {
							jQuery('.' + category + '_showless').hide();
							jQuery('.' + category + '_showmore').show();
							jQuery('.' + category + '_extrashow').hide();
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
			static function printZDSearchShowMoreLink($option, $number_to_show) {
				$option = strtolower($option);
				$number_to_show = (int) $number_to_show;
				switch ($option) {
					case "news":
						$num = getNumNews();
						break;
					case "pages":
						$num = getNumPages();
						break;
				}
				if ($num > $number_to_show) {
					?>
					<a class="<?php echo $option; ?>_showmore"href="javascript:toggleExtraElements('<?php echo $option; ?>',true);"><?php echo gettext('Show more results'); ?></a>
					<a class="<?php echo $option; ?>_showless" style="display: none;"	href="javascript:toggleExtraElements('<?php echo $option; ?>',false);"><?php echo gettext('Show fewer results'); ?></a>
					<?php
				}
			}

			/**
			 * Adds the css class necessary for toggling of Zenpage items search results
			 *
			 * @param string $option "news" or "pages"
			 * @param string $c After which result item the toggling should begin. Here to be passed from the results loop.
			 */
			static function printZDToggleClass($option, $c, $number_to_show) {
				$option = strtolower($option);
				$c = sanitize_numeric($c);
				$number_to_show = (int) $number_to_show;
				if ($c > $number_to_show) {
					echo ' class="' . $option . '_extrashow" style="display:none;"';
				}
			}

			/**
			 * Prints prev/next album links for the theme section linking to the first image of an album
			 *
			 */
			static function printNextPrevAlbumLinkFirstImage() {
				if (getPrevAlbum() || getNextAlbum()) {
					echo '<div id="albumnav">';
					if (getPrevAlbum()) {
						$prevalbum = getPrevAlbum();
						if ($prevalbum->getNumImages() != 0) {
							$firstimg = $prevalbum->getImage(0);
							$firstimage = urlencode($firstimg->filename);
						} else {
							$firstimage = '';
						}
						echo "<a id='prevalbum' href='" . WEBPATH . "/" . pathurlencode($prevalbum->name) . "/" . $firstimage . getOption("mod_rewrite_image_suffix") . "' title='" . $prevalbum->getTitle() . "'>« <strong>" . $prevalbum->getTitle() . "</strong> (previous)</a>";
					}
					if (getNextAlbum()) {
						$nextalbum = getNextAlbum();
						if ($nextalbum->getNumImages() != 0) {
							$firstimg = $nextalbum->getImage(0);
							$firstimage = urlencode($firstimg->filename);
						} else {
							$firstimage = '';
						}
						echo " <a id='nextalbum' href='" . WEBPATH . "/" . pathurlencode($nextalbum->name) . "/" . $firstimage . getOption('mod_rewrite_image_suffix') . "' title='" . $nextalbum->getTitle() . "'><strong>" . $nextalbum->getTitle() . "</strong> (next)»</a>";
					}
					echo '</div>';
				}
			}

			/**
			 * gets the parent album name
			 *
			 */
			static function getParentAlbumName() {
				global $_zp_current_album, $_zp;
				if ($_zp_current_album->getParent()) {
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
			static function getThemeStatusIconClass() {
				global $_zp_current_album, $_zp_themeroot, $_zp_gallery_page;
				if (is_object($_zp_current_album)) {
					if ($_zp_current_album->hasTag('theme_officially-supported')) {
						return ' themeicon1';
					}
					if ($_zp_current_album->hasTag('theme_unsupported-hosted')) {
						return ' themeicon2';
					}
					if ($_zp_current_album->hasTag('theme_unsupported-3rd-party-hosted')) {
						return ' themeicon3';
					}
					if ($_zp_current_album->hasTag('theme_unsupported-3rd-party-external')) {
						return ' themeicon4';
					}
					if ($_zp_current_album->hasTag('theme_unsupported-unavailable')) {
						return ' themeicon5';
					}
				}
			}

			/**
			 * Prints the theme status icon for the theme overview section (album.php)
			 * @param obj $obj Object of an album optionally
			 */
			static function printThemeStatusIcon($obj = NULL) {
				global $_zp_current_album, $_zp_current_zenpage_news, $_zp_themeroot, $_zp_gallery_page;
				if (is_null($obj)) {
					$albumobj = '';
					switch ($_zp_gallery_page) {
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
				if (is_object($albumobj)) {
					if ($albumobj->hasTag('theme_officially-supported')) {
						echo '<img class="' . $iconclass . '" src="' . $_zp_themeroot . '/images/accept_green.png" alt="Officially supported – Included in the Zenphoto release package" title="Officially supported – Included in the Zenphoto release package" />';
					}
					if ($albumobj->hasTag('theme_unsupported-hosted')) {
						echo '<img class="' . $iconclass . '" src="' . $_zp_themeroot . '/images/question_blue.png" alt="Unsupported – hosted" title="Unsupported – hosted" />';
					}
					if ($albumobj->hasTag('theme_unsupported-3rd-party-hosted')) {
						echo '<img class="' . $iconclass . '" src="' . $_zp_themeroot . '/images/question_blue.png" alt="Unsupported – 3rd party hosted" title="Unsupported – 3rd party hosted" />';
					}
					if ($albumobj->hasTag('theme_unsupported-3rd-party-external')) {
						echo '<img class="' . $iconclass . '" src="' . $_zp_themeroot . '/images/question_orange.png" alt="Unsupported – 3rd party external" title="Unsupported – 3rd party external" />';
					}
					if ($albumobj->hasTag('theme_unsupported-unavailable')) {
						echo '<img class="' . $iconclass . '" src="' . $_zp_themeroot . '/images/cancel_round.png" alt="No longer provided" title="No longer provided" />';
					}
				}
			}

			/**
			 * Prints the theme status icon legend list for the sidebar
			 *
			 */
			static function printThemeStatusIconList() {
				global $_zp_themeroot;
				?>
				<hr />
				<h2>Theme icon legend</h2>
				<ul class="statuslist">
					<li class="themestatus1"><a href="<?php echo getSearchURL('theme_officially-supported', '', '', '', NULL); ?>">Officially supported</a> <br />Included in the Zenphoto release package.</li>
					<li class="themestatus2"><a href="<?php echo getSearchURL('theme_unsupported-hosted', '', '', '', NULL); ?>">Unsupported – hosted</a>
						<br />Formerly supported, now unsupported themes we host for archival purposes.</li>
					<li class="themestatus3"><a href="<?php echo getSearchURL('theme_unsupported-3rd-party-hosted', '', '', '', NULL); ?>">Unsupported – 3rd party hosted</a> <br />Unsupported 3rd party themes we host for archival purposes</li>
					<li class="themestatus4"><a href="<?php echo getSearchURL('theme_unsupported-3rd-party-external', '', '', '', NULL); ?>">Unsupported – 3rd party external</a> <br />Themes hosted by their developers</li>
					<li class="themestatus5"><a href="<?php echo getSearchURL('theme_unsupported-unavailable', '', '', '', NULL); ?>">No longer provided</a> <br />Unsupported and 3rd party</li>
				</ul>
				<?php
			}

			static function getShowcaseTypeIconClass() {
				global $_zp_current_image;
				if (is_object($_zp_current_image)) {
					if ($_zp_current_image->hasTag('showcase_zenphoto-with-zenpage-cms-plugin')) {
						return ' showcaseicon1';
					}
					if ($_zp_current_image->hasTag('showcase_zenphoto-only')) {
						return ' showcaseicon2';
					}
					if ($_zp_current_image->hasTag('showcase_partly-zenphoto')) {
						return ' showcaseicon3';
					}
				}
			}

			/**
			 * Prints the showcase type icon for the showcase overview section
			 *
			 */
			static function printShowcaseTypeIcon() {
				global $_zp_current_image, $_zp_themeroot, $_zp_gallery_page;
				if (is_object($_zp_current_image)) {
					if ($_zp_current_image->hasTag('showcase_zenphoto-with-zenpage-cms-plugin')) {
						echo '<img class="themeicon-news" src="' . $_zp_themeroot . '/images/showcasetype1.png" alt="Full site done completly with Zenphoto and the Zenpage CMS plugin" title="Full site done completly with Zenphoto and the Zenpage CMS plugin" />';
					}
					if ($_zp_current_image->hasTag('showcase_zenphoto-only')) {
						echo '<img class="themeicon-news" src="' . $_zp_themeroot . '/images/showcasetype2.png" alt="Site that uses Zenphoto only (e.g. pure gallery site)" title="Site that uses Zenphoto only (e.g. pure gallery site)" />';
					}
					if ($_zp_current_image->hasTag('showcase_partly-zenphoto')) {
						echo '<img class="themeicon-news" src="' . $_zp_themeroot . '/images/showcasetype3.png" alt="Site partly using Zenphoto (e.g. for the gallery part)" title="Site partly using Zenphoto (e.g. for the gallery part)" />';
					}
				}
			}

			/**
			 * Prints the showcase type icon legend list for the sidebar
			 *
			 */
			static function printShowcaseTypeIconList() {
				?>
				<hr />
				<h2>Showcase icon legend</h2>
				<ul class="statuslist">
					<li class="showcasetype1"><a href="<?php echo getSearchURL('showcase_zenphoto-with-zenpage-cms-plugin', '', '', '', NULL); ?>">Full site done completly with Zenphoto and the Zenpage CMS plugin</a>*</li>
					<li class="showcasetype2"><a href="<?php echo getSearchURL('showcase_zenphoto-only', '', '', '', NULL); ?>">Site that uses Zenphoto only (e.g. pure gallery site)</a>*</li>
					<li class="showcasetype3"><a href="<?php echo getSearchURL('showcase_partly-zenphoto', '', '', '', NULL); ?>">Site partly using Zenphoto (e.g. for the gallery part)</a>*</li>
				</ul>
				<strong>*</strong><em>as on date added.</em></p>
				<?php
			}

			/**
			 * Prints the extension status icon legend list for the news loop
			 * @param obj $obj Object of the extension article to check optionally
			 */
			static function printExtensionStatusIcon($obj = NULL) {
				global $_zp_current_zenpage_news, $_zp_themeroot;
				if (is_null($obj)) {
					$obj = $_zp_current_zenpage_news;
				}
				if ($obj->inNewsCategory("officially-supported")) {
					?>
					<img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/accept_green.png" title="Officially supported" />
				<?php } else if ($obj->inNewsCategory('unsupported-hosted') || $obj->inNewsCategory('unsupported-3rd-party-hosted') || $obj->inNewsCategory('unsupported-misc-github')) {
					?>
					<img class="pluginstatusicon" src="<?php echo $_zp_themeroot; ?>/images/question_blue.png" title="Third party hosted on GitHub- not officially supported" />
					<?php
				} else if ($obj->inNewsCategory('unsupported-3rd-party-external')) {
					if ($obj->inNewsCategory('unsupported-unavailable')) {
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

			static function printExtensionStatusIconList() {
				?>
				<hr />
				<h2>Extension icon legend</h2>
				<ul class="statuslist">
					<li class="extension-supported" id="officially-supported">
						Officially supported and included in the release package.
					</li>
					<li class="extension-unsupported-hosted" id="third-party-hosted-unsupported">
						Either our own now unsupported and third party extensions we host on GitHub as an archive.
					</li>
					<li class="extension-unsupported" id="third-party-unsupported">
						Third party extensions hosted by the developers themselves.
					</li>
					<li class="extension-abandoned" id="third-party-abandoned">
						Unsupported third party extensions currently not available.
					</li>
				</ul>
				<?php
			}

			/**
			 * Prints the html header title for each page context sensitive
			 */
			static function printPageHeaderTitle() {
				global $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news, $_zp_current_category;
				$gallerytitle = getBareGalleryTitle();
				$parent = '';
				switch ($_zp_gallery_page) {
					case 'index.php':
						return html_encode($gallerytitle);
						break;
					case 'album.php':
						if (!is_null($_zp_current_album->getParent())) {
							$parent = ' | ' . $_zp_current_album->getParent()->getTitle();
						}
						$gallerytitle = $_zp_current_album->getTitle() . $parent . ' | ' . $gallerytitle;
						break;
					case 'image.php':
						if (!is_null($_zp_current_album->getParent())) {
							$parent = ' | ' . $_zp_current_album->getParent()->getTitle();
						}
						$gallerytitle = $_zp_current_image->getTitle() . ' | ' . $_zp_current_album->getTitle() . $parent . ' | ' . $gallerytitle;
						break;
					case 'news.php':
						if (self::inNewsCategory('user-guide')) {
							$parent = ' | User guide';
						} else if (self::inNewsCategory('extensions')) {
							$parent = ' | Extensions';
						}
						if (!is_null($_zp_current_zenpage_news)) {
							$gallerytitle = $_zp_current_zenpage_news->getTitle() . $parent . ' | ' . $gallerytitle;
						} else if (!is_null($_zp_current_category)) {
							$gallerytitle = $_zp_current_category->getTitle() . $parent . ' | ' . $gallerytitle;
						} else {
							$gallerytitle = 'News | ' . $gallerytitle;
						}
						break;
					case 'pages.php':
						if ($_zp_current_zenpage_page->getParents()) {
							$parentpages = $_zp_current_zenpage_page->getParents();
							$parentpage = $parentpages[0];
							$obj = new ZenpagePage($parentpage);
							$parent = ' | ' . $obj->getTitle();
						}
						$gallerytitle = $_zp_current_zenpage_page->getTitle() . $parent . ' | ' . $gallerytitle;
						break;
					case 'search.php':
						$gallerytitle = 'Search | ' . $gallerytitle;
						break;
					case 'archive.php':
						$gallerytitle = 'Archive | ' . $gallerytitle;
						break;
				}
				echo html_encode($gallerytitle);
			}

			/**
			 * Prints downloadlinks for the logos incl. a albumzip download. Uses and requires the downloadlist plugin
			 */
			static function Logodownloads() {
				self::specialdownloads('storage/logos');
			}

			/**
			 * Prints downloadlinks for subalbum contents of a special album.
			 * Used for the logo (via Zenpage page codeblock) and sponsor badges (multiple layout album page) download pages.
			 * Uses and requires the downloadlist plugin
			 */
			static function specialdownloads($albumfolder = NULL) {
				global $_zp_gallery;
				if (function_exists('printDownloadlist') && !is_null($albumfolder)) {
					$albobj = newAlbum($albumfolder);
					$subalbs = $albobj->getAlbums(0, null, null, true, true);
					if ($subalbs) {
						foreach ($subalbs as $subalb) {
							$subobj = newAlbum($subalb);
							echo '<div class="logodownloadwrapper">';
							$albumthumb = $subobj->getAlbumThumbImage();
							echo '<span><img src="' . $albumthumb->getCustomImage(200, NULL, NULL, NULL, NULL, NULL, NULL, false, NULL) . '" alt="" /></span><h4>' . $subobj->getTitle() . '</h4>';
							printDownloadlist('albums/' . $subalb, 'ol', array(), 'jpg');
							//printDownloadAlbumZipURL('All above as .zip',$subobj);
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
			static function printZenphotoVersionOfExtensionOrTheme($before = '') {
				global $_zp_gallery_page, $_zp_current_zenpage_news, $_zp_current_album;
				$changelogtitle = '';
				if (!is_null($_zp_current_zenpage_news) || !is_null($_zp_current_album)) {
					switch ($_zp_gallery_page) {
						case 'news.php':
							if ($_zp_current_zenpage_news->inNewsCategory('extensions')) {
								$catobj = new ZenpageCategory('changelog');
								$changelog_articles = $catobj->getArticles('', true, true, "date", "desc", false);
								foreach ($changelog_articles as $article) {
									$newsobj = new ZenpageNews($article['titlelink']);
									//echo $newsobj->getDatetime()."/".$_zp_current_zenpage_news->getDatetime();
									if ($newsobj->getDatetime() < $_zp_current_zenpage_news->getDatetime()) {
										$changelogtitle = $newsobj->getTitle();
										if (strrchr($changelogtitle, 'Version')) {
											$changelogtitle = substr($changelogtitle, 8);
											$changelogtitle = substr($changelogtitle, 0, 5);
											$changelogtitle = $changelogtitle;
										}
										if (strrchr($changelogtitle, 'Zenphoto')) {
											$changelogtitle = substr($changelogtitle, 9);
										}
										$changelogtitle = $before . 'Originally introduced with Zenphoto ' . $changelogtitle;
										break;
									}
								}
							}
							break;
						case 'album.php':
						case 'image.php':
							if ($_zp_current_album->getParent() && $_zp_current_album->getParent()->name == 'theme') {
								$catobj = new ZenpageCategory('changelog');
								$changelog_articles = $catobj->getArticles('', true, true, "date", "desc", false);
								foreach ($changelog_articles as $article) {
									$newsobj = new ZenpageNews($article['titlelink']);
									//echo $newsobj->getDatetime()."/".$_zp_current_album->getDatetime();
									if ($newsobj->getDatetime() < $_zp_current_album->getDatetime()) {
										$changelogtitle = $newsobj->getTitle();
										if (strrchr($changelogtitle, 'Version')) {
											$changelogtitle = substr($changelogtitle, 8);
											$changelogtitle = substr($changelogtitle, 0, 5);
											$changelogtitle = $changelogtitle;
										}
										if (strrchr($changelogtitle, 'Zenphoto')) {
											$changelogtitle = substr($changelogtitle, 9);
										}
										$changelogtitle = $before . 'Originally introduced with Zenphoto ' . $changelogtitle;
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
			static function printSidebarBoxes() {
				global $_zp_themeroot, $_zp_gallery_page, $_zp_current_album;
				?>
				<hr />
				<?php
				zporgSponsors::printAds(true); //platinum (index) or palladium (sidebar) sponsors
				?>
				<div class="infobox paidsupport">
					<img src="<?php echo $_zp_themeroot; ?>/images/icon-forum.png" alt="" />
					<h3>Need project help?</h3>
					<p>Visit the <?php printPageURL('Paid support page', 'paid-support', '', '', NULL); ?>.
					</p>
					<br clear="left" />
				</div>
				<div class="infobox">
					<h3>Like using Zenphoto? Donate!</h3>
					<p>Your support helps pay for this server, and helps development of zenphoto. Thank you!</p>
					<p>Visit the <?php printPageURL('donations page', 'donation', '', '', NULL); ?></p>
				</div>
				<?php if (!is_NewsArticle() && $_zp_gallery_page != 'image.php') { ?>
					<div class="infobox">
						<h3>Share!</h3>
						<?php
						if (class_exists('ScriptlessSocialSharing')) {
							ScriptlessSocialSharing::printButtons();
						}
						?>
					</div>
					<?php
				}
			}

			/**
			 * Prints the theme download button depending of external (3rd party), hosted or abandoned
			 */
			static function printThemeDownloadButton() {
				global $_zp_themeroot, $_zp_gallery, $_zp_current_album;
				$linktext = '';
				$note = '';
				$themeurl = trim($_zp_current_album->getLocation());
				if (self::getParentAlbumName() == "theme") {
					if ($_zp_current_album->hasTag('theme_officially-supported')) {
						if(empty($themeurl)) {
							$note = '<p class="articlebox">The theme is included in the ZenphotoCMS release.</p>';
						} else {
							$linktext = 'Info & download (GitHub)';
							$note = '<p class="articlebox">This theme is officially supported but not included in the ZenphotoCMS release.</p>';
						}
					} else {
						if ($_zp_current_album->hasTag('theme_unsupported-hosted') || $_zp_current_album->hasTag('theme_unsupported-3rd-party-hosted')) {
							$linktext = 'Info & download (GitHub)';
							if ($_zp_current_album->hasTag('theme_unsupported-hosted')) {
								echo '<div class="articlebox-left warningnote"><p>This theme has been abandoned by the ZenphotoCMS team and we provide it for archival purposes on our "unsuppported-themes-official" GitHub repository "as is".'
								. ' We may sometimes roughly update it but cannot promise full compatibility with current ZenphotoCMS releases. Contributions are welcome.</p></div>';
								$themeurl = 'https://github.com/zenphoto/unsupported-themes-official';
							} else if ($_zp_current_album->hasTag('theme_unsupported-3rd-party-hosted')) {
								echo '<div class="articlebox-left warningnote"><p>This theme has been abandoned by its original developer and we provide it for archival purposes on our "unsuppported-themes-thirdpary" GitHub repository "as is".'
								. ' We may sometimes roughly update it but cannot promise full compatibility with current ZenphotoCMS releases. Contributions are welcome.</p></div>';
								$themeurl = 'https://github.com/zenphoto/unsupported-themes-thirdparty';
							}
						} else if ($_zp_current_album->hasTag('theme_unsupported-3rd-party-external')) {
							$linktext = 'Info & download (external)';
						} else if ($_zp_current_album->hasTag('theme_unsupported-unavailable')) {
							echo '<p class="articlebox-left warningnote"><strong>Sorry, this theme is no longer provided by its developer and sadly we don not have any copy of it.</strong></p>';
						}
						
					}
					echo $note;
					if (!empty($themeurl) && !empty($linktext)) {
						echo '<div class="buttons"><a href="' . $themeurl . '"><img src="' . $_zp_themeroot . '/images/arrow_right_blue_round.png" alt="" /> ' . $linktext . '</a></div>';
					}
				}
			}

			/**
			 * Prints the extension download button depending of external (3rd party), hosted or abandoned
			 */
			static function printExtensionDownloadButton() {
				global $_zp_current_zenpage_news, $_zp_themeroot;
				$note = $linktext = $exturl = '';
				if (self::inNewsCategory("extensions")) {
					$exturl = trim($_zp_current_zenpage_news->getCustomData());
					$linkicon_url = '';
					if (self::inNewsCategory("officially-supported")) {
						if ($_zp_current_zenpage_news->inNewsCategory("translations")) {
							$linktext = '';
							$linkicon_url = '';
							$exturl = '';
							$note = '<p>You will find most available translations included in the <a href="https://github.com/zenphoto/zenphoto/tree/master/zp-core/locale">Zenphoto release package</a>. Regular translators have GitHub access to update their translation themselves. But note that not all included translations are complete or actively maintained. Their status is noted on the translation selector on the Zenphoto backend.</p><p>If you would like to contribute a new translation or to an existing one that is welcome. Please read our <a href="http://www.zenphoto.org/news/translating-tutorial/">translation tutorial</a> before starting anything.</p>';
							$note .= '<p class="articlebox">This translation is included in the ZenphotoCMS release.</p>';
						} else {
							if(empty($exturl)) {
								$linktext = 'Usage information';
								$linkicon_url = $_zp_themeroot . '/images/info_green.png';
								$note = '<p class="articlebox">This extension is included in the ZenphotoCMS release.</p>';
								//$exturl = 'http://docs.zenphoto.org/package-plugins.html'; // fix links later
							} else {
								$linktext = 'Info & Download (GitHub)';
								$linkicon_url = $_zp_themeroot . '/images/arrow_right_blue_round.png';
								$note = '<p class="articlebox">This extension is officially supported but not included in the ZenphotoCMS release.</p>';
							}
						}
					} if (self::inNewsCategory("unsupported-hosted") || self::inNewsCategory("unsupported-3rd-party-hosted") || self::inNewsCategory("unsupported-misc")) {
						$linktext = 'Info & download (GitHub)';
						$linkicon_url = $_zp_themeroot . '/images/arrow_right_blue_round.png';
						if (self::inNewsCategory("unsupported-hosted")) {
							$note = '<div class="articlebox-left warningnote"><p>This extension has been abandoned by the ZenphotoCMS team and we provide it for archival purposes on our "unsuppported-plugins-offical" GitHub repository "as is". .</p></div>';
							$exturl = 'https://github.com/zenphoto/unsupported-plugins-official';
						} else if (self::inNewsCategory("unsupported-3rd-party-hosted")) {
							$note = '<div class="articlebox-left warningnote"><p>This extension has been abandoned by its original developer and we provide it for archival purposes on our "unsuppported-plugins-thirdparty" GitHub repository "as is". .</p></div>';
							$exturl = 'https://github.com/zenphoto/unsupported-plugins-thirdparty';
						} else if (self::inNewsCategory("unsupported-misc")) {
							$note = '<div class="articlebox-left warningnote"><p>This tool has been abandoned by the ZenphotoCMS team or its original developer and we provide it for archival purposes on our "unsuppported-misc" GitHub repository "as is". .</p></div>';
							$exturl = 'https://github.com/zenphoto/unsupported-misc';
						}
					} else if (zporg::inNewsCategory("unsupported-3rd-party-external")) {
						$linktext = 'Info & download (external)';
						$note = '<p class="articlebox">This extension is hosted and (hopefully maintnained) by its author.</p>';
						$linkicon_url = $_zp_themeroot . '/images/arrow_right_blue_round.png';
					} else if (zporg::inNewsCategory("unsupported-unavailable")) {
						$linktext = '';
						$exturl = '';
						$note = '<p class="articlebox-left warningnote"><strong>Sorry, this extension is no longer provided by its developer.</strong></p>';
					}
					echo $note;
					if (!empty($exturl) && !empty($linktext)) {
						echo '<p class="buttons"><a href="' . html_encode($exturl) . '"><img src="' . $linkicon_url . '" alt="" /> ' . $linktext . '</a></p>';
					}
				}
			}

			static function printMainSectionCategoryTitle() {
				global $_zp_current_category;
				if (!is_null($_zp_current_category)) {
					$articlecount = ' <small>(' . count($_zp_current_category->getArticles(0)) . ')</small>';
				}
				if (self::inNewsCategory('extensions')) {
					echo "Extensions";
					if (!is_NewsArticle() && $_zp_current_category->getTitlelink() == 'extensions') {
						echo $articlecount;
					}
					if (!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() != 'extensions') {
						echo '» ' . $_zp_current_category->getTitle() . $articlecount;
					}
				} else if (self::inNewsCategory('user-guide')) {
					echo "User guide ";
					if (!is_NewsArticle() && $_zp_current_category->getTitlelink() == 'user-guide') {
						echo $articlecount;
					}
					if (!is_null($_zp_current_category) && $_zp_current_category->getTitlelink() != 'user-guide') {
						echo '» ' . $_zp_current_category->getTitle() . $articlecount;
					}
				} else if (!self::inNewsCategory('extensions') || self::inNewsCategory('user-guide')) {
					echo "News";
					if (!is_NewsArticle() && !is_null($_zp_current_category) && !is_NewsArticle() && $_zp_current_category->getTitlelink() != 'news') {
						echo '» ' . $_zp_current_category->getTitle();
					}
				}
				if (is_NewsArchive()) {
					echo ': ' . getCurrentNewsArchive('plain');
				}
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
			static function printRelatedItems($number = 5, $type = 'news', $specific = NULL) {
				global $_zp_gallery, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_page, $_zp_current_zenpage_news;
				$result = array();
				if (function_exists('getRelatedItems')) {
					$result = getRelatedItems($type, $specific);
				}
				if (count($result) > 1) { // because one always gets found, the item itself!
					?>
					<h3 class="relateditems"><?php echo gettext('Related items'); ?></h3>
					<ul id="relateditems">
						<?php
						$count = '';
						foreach ($result as $item) {
							switch ($type) {
								case 'albums':
									$obj = newAlbum($item);
									$objname = $item;
									$currentname = $_zp_current_album->name;
									break;
								case 'images':
									$obj = newImage($_zp_current_album, $item);
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
							if ($objname != $currentname) { // avoid listing the item itself
								$count++;
								?>
								<li>
									<?php
									$category = '';
									$url = $obj->getLink();
									switch ($type) {
										case 'albums':
										case 'images':
										case 'pages':
											break;
										case 'news':
											if ($obj->inNewsCategory('extensions')) {
												$category = '<small> (Extensions)</small>';
											} else if ($obj->inNewsCategory('user-guide')) {
												$category = '<small> (User guide)</small>';
											} else if ($obj->inNewsCategory('news')) {
												$category = '<small> (News)</small>';
											}
											break;
									}
									?>
									<a href="<?php echo html_encode($url); ?>" title="<?php echo html_encode($obj->getTitle()); ?>"><?php echo html_encode($obj->getTitle()); ?></a><?php echo $category; ?>
									</h4></li>
								<?php
							} // if object not current
							if ($count == $number) {
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
			static function printTroubleshootingGuide() {
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
					foreach ($cats as $key => $cat) {
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
					self::listTroubleshootingArticles($catobj);
				}
			}

			/**
			 * Helpder function to zp_printTroubleshootingGuide(). This prints the individual troubleshooting section on the troubleshooting guide article
			 *
			 * @param obj $cat category object
			 */
			static function listTroubleshootingArticles($cat) {
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
									<p><a href="<?php echo $article->getLink(); ?>">Direct link</a></p>
								</div>

								<?php ?>
							</li>
							<?php
						}
						?>
					</ol>
					<?php
				}
				$_zp_current_category = $cat;
			}

			/**
			 * Prints the last change date for user guide and extension articles if there is one and if it is not the same as the date
			 *
			 */
			static function printNewsLastChange() {
				global $_zp_current_zenpage_news;
				if ((self::inNewsCategory('user-guide') || self::inNewsCategory('extensions')) && $_zp_current_zenpage_news->getLastchange()) {
					$lastchange = $_zp_current_zenpage_news->getLastchange();
					$date = $_zp_current_zenpage_news->getDateTime();
					if (substr($lastchange, 0, 9) != substr($date, 0, 9)) {
						echo ' / Updated: ' . zpFormattedDate(DATE_FORMAT, strtotime($lastchange));
					}
				}
			}

			static function printLicenseNote() {
				global $_zp_themeroot;
				?>
				<div class="articlebox license">
					<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="<?php echo $_zp_themeroot; ?>/images/cc-by-sa-88x31.png" /></a>This text by <a xmlns:cc="http://creativecommons.org/ns#" href="www.zenphoto.org" property="cc:attributionName" rel="cc:attributionURL">www.zenphoto.org</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.</p>
					<p>Code examples are released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPL v2 or later license</a></p>
				</div>
				<?php
			}

		}
		