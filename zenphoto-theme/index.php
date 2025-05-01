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
				<?php echo zporg::getZenphotoButtonHTML(); ?>
			</div>
		</div>


	</div> <!-- slideshow end -->


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
					$image = Image::newImage($tempalbum, $firstimage);
					$thumb = $image->getCustomImage(NULL, 238, 128, 238, 128, NULL, NULL, TRUE);
					$imagelink = $image->getLink();
				}
				$albtitle = $tempalbum->getTitle();
				$themes = Albumbase::newAlbum('theme');
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
		
		<?php echo zporg::getDonateCallToActionHTML('infobox', 'h3'); ?>
		
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
				$showcase = Albumbase::newAlbum('showcase');
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
