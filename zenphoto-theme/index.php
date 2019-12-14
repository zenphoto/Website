<?php
include('header.php');
?>


<div id="frontcontent">


	<div>


		<div id="introwrapper">
			<h2>Media website management the easy way!</h2>
			<ul id="featureicons">
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#manage-image-video-and-audio-files"><span class="icon-image"></span><h3>Images</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#manage-image-video-and-audio-files"><span class="icon-music"></span><h3>Audio</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#manage-image-video-and-audio-files"><span class="icon-film"></span><h3>Video</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#manage-blog-and-pages"><span class="icon-file"></span><h3>Blog</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#manage-blog-and-pages"><span class="icon-file2"></span><h3>Pages</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#multi-user-management"><span class="icon-lock"></span><h3>User rights</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#multi-user-management"><span class="icon-users"></span><h3>Multi user</h3></a></li>
				<li><a href="<?php echo html_encode(getNewsURL('features')); ?>#international-sites-with-multiple-languages"><span class="icon-earth"></span><h3>Multilingual</h3></a></li>
			</ul>
			<div id="introtext">
				<?php printGalleryDesc(); ?>
				<p class="buttons"><a href="<?php echo html_encode(getNewsURL('features')); ?>">Read about all features</a></p>
			</div>
		</div>


	</div> <!-- slideshow end -->

	<?php
	$downloadroot = 'https://github.com/zenphoto/zenphoto/archive/';
	$zp_dl_version = '';
	$zp_version = '';
	$zp_dl_pubdate = '';
	$zp_dev_version = '';

	$cat = new ZenpageCategory('release');
	$latestnews = $cat->getArticles(10, 'published', true, 'date',true);
	do {
		$article = array_shift($latestnews);
		$newsobj = new ZenpageNews($article['titlelink']);
		$zp_dl_version = str_replace('zenphoto-', 'v', $newsobj->getTitlelink());
		$zp_version = $newsobj->getTitle();
		$zp_dl_pubdate = zpFormattedDate(DATE_FORMAT, strtotime($newsobj->getDatetime()));
		preg_match('~(\d[\.\d]*)\s*(.*)~', $zp_version, $matches);
		if (!defined('MASTER_BUILD')) {
			if ($matches[2]) {
				$v = ' ' . $matches[2];
			} else {
				$v = ' Support build';
			}
			define('MASTER_BUILD', $matches[1] . $v);
			$current = $matches[1];
		}
	} while (!empty($latestnews) && $matches[2]);

	$latestnews = $cat->getArticles(1, 'unpublished', true, 'date',true);
	if (count($latestnews) > 0) {
		$newsobj = new ZenpageNews($latestnews[0]['titlelink']);
		$dev_version = $newsobj->getTitle();
		preg_match('~((\d[\.\d]*)\s*(.*))~', $dev_version, $matches);
		if ($matches[0] != $current) {
			define('DEV_BUILD', $matches[0]);
		}
	}
	$downloadbaseurl = $downloadroot . $zp_dl_version;
	?>
	<div class="downloadwrapper">

		<div class="buttonzip">
			<a	href="<?php echo $downloadbaseurl; ?>.zip" title="Download Zenphoto in zip format" data-track-content data-content-piece="<?php echo $downloadbaseurl; ?>.zip">
				<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.zip)</span>
			</a>
		</div>

		<div class="buttontar">
			<a	href="<?php echo $downloadbaseurl; ?>.tar.gz"	title="Download Zenphoto in tar format" data-track-content data-content-piece="<?php echo $downloadbaseurl; ?>.tar.gz">
				<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.tar.gz)</span>
			</a>
		</div>

		<p class="version_info">
			<strong><?php echo $zp_version; ?></strong> (<?php echo $zp_dl_pubdate; ?>) | License: <a	href="http://www.gnu.org/licenses/gpl-2.0.html">GPL v2 or later</a> | <a
				href="<?php echo html_encode(getNewsURL('installation-and-upgrading')); ?>" title="Installation, upgrading and requirements">Installation,	upgrading and requirements</a>
		</p>
	</div> <!--download box div wrapper end -->

	<div class="infobox-links-r">
		<?php printSearchForm(); ?>
		<ul class="downloadlinks">
			<li><a href="<?php echo html_encode(getNewsCategoryURL('changelog')); ?>" title="Zenphoto changelog">Changelog</a></li>
			<li><a href="https://github.com/zenphoto/zenphoto/archive/master.zip" title="Zenphoto support build on GitHub"  data-track-content data-content-piece="Support build (GitHub master)">Support build (GitHub master)</a></li>

			<?php $devbuild = false; 
		/*	if (defined('DEV_BUILD')) { */
			if($devbuild) {
				?>
				<li><a href="https://github.com/zenphoto/zenphoto/archive/<?php echo DEV_BUILD; ?>.zip" title="Zenphoto development on Github" data-track-content data-content-piece="Development build (GitHub)"><?php echo DEV_BUILD; ?> Development build (GitHub)</a></li>
				<?php
			}
			?>
			<li><a href="<?php echo html_encode(getNewsURL('older-versions-archive')); ?>">Older versions archive</a></li>
		</ul>
	</div>

	<br style="clear: both" />
	<div class="column-l">
		<div class="infobox">
			<?php
			$number = '';
			$albtitle = '';
			$thumb = '';
			$imagelink = '';
			$latestalbum = getAlbumStatistic(1, 'latest-date', 'theme');
			if (!empty($latestalbum)) {
				$tempalbum = $latestalbum[0];
				if ($tempalbum->getNumImages() != 0) {
					$firstimage = $tempalbum->getImages(1); // need only the first so don't get all
					$firstimage = $firstimage[0];
					$image = newImage($tempalbum, $firstimage);
					$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
					$imagelink = $image->getLink();
				}
				$albtitle = $tempalbum->getTitle();
				$themes = newAlbum('theme');
				$number = $themes->getNumAlbums(); 
			}
			?>
			<h3>View available themes <small>(<?php echo $number; ?>)</small></h3>
			<a href="<?php echo $imagelink; ?>"
				 title="<?php echo html_encode($albtitle); ?>"> <img
					src="<?php echo html_encode($thumb); ?>"
					alt="<?php echo html_encode($albtitle); ?>" /> </a> <br />
			<p>Latest addition: <a href="<?php echo $imagelink; ?>"
														 title="<?php echo html_encode($albtitle); ?>"><?php echo $albtitle; ?></a></p>
		</div>

		<div class="infobox">
			<h3>Latest news</h3>
			<ul class="downloadlinks">
				<?php
				$latestnews = $_zp_zenpage->getArticles(5, NULL, true, NULL, 'DESC', true, NULL);

				if (empty($latestnews)) {
					echo 'No latest news';
				} else {
					foreach ($latestnews as $news) {
						$newsobj = new ZenpageNews($news['titlelink']);
						$category = '';
						if ($newsobj->inNewsCategory('extensions'))
							$category = ' (extensions)';
						if ($newsobj->inNewsCategory('news'))
							$category = ' (news)';
						if ($newsobj->inNewsCategory('user-guide'))
							$category = ' (user guide)';
						echo '<li><a href="' . $newsobj->getLink() . '" title="' . html_encode($newsobj->getTitle()) . '">' . $newsobj->getTitle() . '</a> ' . $category . '</li>';
					}
				}
				?>
			</ul>
		</div>

		<div class="infobox">
			<h3>Found a bug?!</h3>
			<p><img src="<?php echo $_zp_themeroot; ?>/images/icon-bugtracker.png" class="imgfloat-left" alt="" /> Please report any bugs you find with a detailed description via tickets at the <a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Zenphoto bugtracker on GitHub.com</a>.
			</p>
		</div>

		<div class="infobox">
			<h3>Share!</h3>
			<?php 
			if (class_exists('ScriptlessSocialSharing')) {
				ScriptlessSocialSharing::printButtons();
			}
			?>
		</div>

		<div class="infobox">
			<h3>Like using Zenphoto? Donate!</h3>
			<p>Your support helps pay for this server, and helps development of Zenphoto. Thank you!</p>
			<p>Visit the <a href="<?php echo html_encode(getPageURL('donations')); ?>">donations page</a></p>
		</div>


	</div><!-- infobox-l end -->

	<div class="column-m">

		<div class="infobox">
			<h3>Screenshots and screencasts!</h3>
			<?php
			$image = '';
			$thumb = '';
			$imgtitle = '';
			$thumb = '';
			$latestimage = getImageStatistic(1, 'latest', 'screenshots/zenphoto-admin', true);
			if (!empty($latestimage)) {
				$image = $latestimage[0];
				$imgtitle = $image->getTitle();
				$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
			} 
			?>
			<a href="<?php echo WEBPATH; ?>/screenshots/"	title="<?php echo html_encode($imgtitle); ?>">
				<img src="<?php echo html_encode($thumb); ?>" alt="<?php echo html_encode($imgtitle); ?>" /> </a>
			<br />
			<p>
				<a href="<?php echo WEBPATH; ?>/screenshots/" title="Zenphoto screenshots and screencasts">Learn about using Zenphoto</a>
			</p>
		</div>

		<?php zporgSponsors::printAds(true); ?>


	</div><!-- infobox-m end -->

	<div class="column-r">

		<div class="infobox">
			<?php
			$image = '';
			$imgtitle = '';
			$imglink = '';
			$thumb = '';
			$latestimage = getImageStatistic(1, 'latest', 'showcase', false);
			if (!empty($latestimage)) {
				$image = $latestimage[0];
				$showcase = newAlbum('showcase');
				$number = $showcase->getNumImages();
				$imgtitle = $image->getTitle();
				$imglink = $image->getLink();
				$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
			}
			?>
			<h3>Visit the showcase! <small>(<?php echo $number; ?> entries)</small></h3>
			<a href="<?php echo html_encode($imglink); ?>"	title="<?php echo html_encode($imgtitle); ?>">
				<img src="<?php echo html_encode($thumb); ?>" alt="<?php echo html_encode($imgtitle); ?>" />
			</a>
			<br />
			<p>Latest addition: <a href="<?php echo html_encode($imglink); ?>"	title="<?php echo html_encode($imgtitle); ?>"><?php echo $imgtitle; ?></a>
			</p>
		</div>

		<div class="infobox">
			<h3>Need help? Visit the forum!</h3>
			<p><img src="<?php echo $_zp_themeroot; ?>/images/icon-forum.png"	class="imgfloat-left" alt="" /> You can post help requests and discuss everything Zenphoto related in the
				<a href="<?php echo html_encode(getOption('zporg_forumurl')); ?>" title="Zenphoto forums">Zenphoto support forums</a> (Registration required for posting). Please do not e-mail us asking for help. Thanks!
			</p>
			<br />
			<h3>Need project help?</h3>
			<p>Visit the <a href="<?php echo html_encode(getPageURL('paid-support')); ?>">paid support page</a>.
			</p>
		</div>

		<div class="infobox">
			<h3>Get involved!</h3>
			<p>You would like to contribute? You don't need to be a programmer! <a href="<?php echo html_encode(getPageURL('get-involved')); ?>" title="Get involved!">Read here what you can do for Zenphoto!</a>
			</p>
		</div>



	</div><!-- infobox-r end -->


	<br style="clear: both" />



	<!-- The main column ends  --> <?php include('footer.php'); ?>
