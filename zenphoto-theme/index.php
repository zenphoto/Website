<?php include('header.php'); ?>
<noscript>
	<style type="text/css">
		#frontcontent #slidenav {
			display: none;
		}
		#slideshow img {
			float: right;
			margin-top: -20px;
		}
	</style>

</noscript>

<div id="frontcontent">

<div id="slideextranav">
  <a href="#"><span id="slideplay">Play</span></a>
  <a href="#"><span id="slidepause">Pause</span></a>
</div>
  <ul id="slidenav"></ul>
	<div id="slideshow">
		<div class="slide slidemain">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide0-intro.jpg" />
			<h2>Media website management the easy way!</h2>
			<p>Zenphoto is a standalone CMS for multimedia focused websites. Our focus lies on being easy to use and having all the features there when you need them (but out of the way if you do not.)</p>
			<p>Zenphoto  features support for images, video and audio formats, and the <a href="/news/zenpage-a-cms-plugin-for-zenphoto">Zenpage CMS plugin</a> provides a fully integrated news section (blog) and custom pages to run entire websites.</p>
			<p>This makes Zenphoto the ideal CMS for personal websites of illustrators, artists, designers, photographers, film makers and musicians.</p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide1-images.jpg" />
			<h2>Manage your image, video and audio files!</h2>
			<ul class="downloadlinks">
			<li>Upload files and folders via the administration or directly via FTP</li>
			<li>Automatically generated sized images</li>
			<li>EXIF/IPTC metadata support</li>
			<li>Drag & Drop sorting, moving, copying, renaming of albums and images</li>
			<li>Supported formats: jpg, gif, png, mp3, mp4, m4v, m4a, fla, flv, mov, 3gp</small></li>
			<li>Slideshow, comments, RSS, tags and much more!</li>
			</ul>
			<p class="buttons"><a href="/news/features">Read about all features</a></p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide2-zenpage.jpg" />
			<h2>Manage blog and pages!</h2>
			<ul class="downloadlinks">
			<li>Custom pages management incl. subpages</li>
			<li>Drag & drop page sorting</li>
			<li>News section (blog) with nested categories</li>
			<li>Post of new gallery items in the news section (blog)</li>
			<li>File manager for none gallery files</li>
			<li>Comments, RSS, tags and much more!</li>
			</ul>
			<p class="buttons"><a href="/news/zenpage-a-cms-plugin-for-zenphoto">Read more about the Zenpage CMS plugin</a></p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide3-user.jpg" />
			<h2>Multi-user management</h2>
			<ul class="downloadlinks">
			<li>User managment</li>
			<li>User groups</li>
			<li>Varying rights assignment</li>
			<li>Password protection for albums and pages</li>
			</ul>
			<p class="buttons"><a href="/news/an-overview-of-zenphoto-users">Read more about user rights</a></p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide4-lang.jpg" />
			<h2>International sites with multiple languages!</h2>
			<ul class="downloadlinks">
			<li>Admin backend translatable.</li>
			<li>Many backend translations included (gettext server support required): Chinese, Dutch, French, Galician, German, Hebrew, Japanese, Polish, Spanish, Swedish (See also <a href="http://www.zenphoto.org/trac/browser/trunk/zp-core/locale">Current language repository</a>)</li>
			<li>Multilingual sites</li>
			<li>
			</ul>
			<p class="buttons"><a href="/news/multi-lingual-sites">Read more about language features</a></p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide5-themes.jpg" />
			<h2>Create your own website design!</h2>
			<ul class="downloadlinks">
			<li>Highly customizable theme engine (XHTML, CSS, PHP)</li>
			<li>Four standard themes included, more themes available</li>
			<li>Object model framework for advanced theming and flexibility</li>
			<li>Multiple layouts/album themes</li>
			<li>Separate theme translations</li>
			</ul>
			<p class="buttons"><a href="/theme">Visit the themes section</a></p>
		</div>

		<div class="slide">
			<img src="<?php echo $_zp_themeroot; ?>/images/homeslides/slide6-plugins.jpg" />
			<h2>Extend Zenphoto with features you need!</h2>
			<ul class="downloadlinks">
			<li>Sophisticated plugin system</li>
			<li>Frontend and backend filters</li>
			<li>Object model framework for advanced coding</li>
			<li>Many plugins included plus more 3rd party ones available</li>

			</ul>
			<p class="buttons"><a href="/news/category/extensions">Visit the extensions section</a></p>
		</div>


	</div> <!-- slideshow end -->

<?php
$downloadroot = 'https://github.com/zenphoto/zenphoto/archive/';
$zp_dl_version = '';
$zp_version = '';
$zp_dl_pubdate = '';
$zp_dev_version = '';
if(function_exists('getLatestNews')) {
	$latestnews = getLatestNews(1,'none','release');
	//print_r($latestnews);
	$newsobj = new ZenpageNews($latestnews[1]['titlelink']);
	$zp_dl_version = $newsobj->getTitlelink();
	$zp_version = $newsobj->getTitle();
	$zp_dl_pubdate = zpFormattedDate(DATE_FORMAT, strtotime($newsobj->getDatetime()));
	$zp_dev = array_slice(explode('.',substr($zp_version, strpos($zp_dl_version, '-')+1).'.0.0.0'),0,3);

	//WARNING: the following code presumes that we march consistently through release numbers without any breaks!!!!
	$carry = 1;
	while (!empty($zp_dev)) {
		$v = array_pop($zp_dev)+$carry;
		if ($v % 10) {
			$carry = 0;
		} else {
			$v = 0;
		}
		$zp_dev_version = $v.'.'.$zp_dev_version;
	}
	$zp_dev_version = '/archive/'.substr($zp_dev_version,0,-1).'.zip';
}
?>
	<div class="downloadwrapper">


	<div class="buttonzip">
		<a	href="<?php echo $downloadroot; ?><?php echo $zp_dl_version; ?>.zip" title="Download ZenPhoto in zip format">
		<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.zip)</span>
		</a>
	</div>

	<div class="buttontar">
		<a	href="<?php echo $downloadroot; ?><?php echo $zp_dl_version; ?>.tar.gz"	title="Download ZenPhoto in tar format">
			<img src="<?php echo $_zp_themeroot; ?>/images/downloadbutton.png" alt="" /><span>Download (.tar.gz)</span>
		</a>
	</div>

	<p class="version_info">
	<strong><?php echo $zp_version; ?></strong> (<?php echo $zp_dl_pubdate; ?>) | License: <a	href="http://www.gnu.org/licenses/gpl-2.0.html">GPL v.2</a> | <a
href="http://www.zenphoto.org/news/installation-and-upgrading" title="Installation, upgrading and requirements">Installation,	upgrading and requirements</a>
	</p>
</div> <!--download box div wrapper end -->

	<div class="infobox-links-r">
		<ul class="downloadlinks">
			<li><a href="/news/category/changelog" title="Zenphoto changelog">Changelog</a></li>
			<li><a href="https://github.com/zenphoto/zenphoto/archive/master.zip" title="Zenphoto nightly build on GitHub">Support builds (GitHub)</a></li>
    	<li><a href="https://github.com/zenphoto/zenphoto<?php echo $zp_dev_version; ?>" title="Zenphoto development on Github">Development (GitHub)</li>
    	<li><a href="http://www.zenphoto.org/pages/older-versions-archive" title="Zenphoto older versions archive">Older versions archive</a></li>
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
	$latestalbum = getAlbumStatistic(1, 'latest', 'theme');
	if(!empty($latestalbum)) {
		$album = $latestalbum[0];
		$tempalbum = new Album($_zp_gallery, $album['folder']);
		if($tempalbum->getNumImages() != 0) {
			$firstimage = $tempalbum->getImages(1); // need only the first so don't get all
			$firstimage = $firstimage[0];
			$image = newImage($tempalbum,$firstimage);
			$thumb = $image->getCustomImage(NULL,238,128,238,128,NULL,NULL,TRUE);
			$imagelink = $image->getImageLink();
		}
		$albtitle = $tempalbum->getTitle();
		$themes = new Album($_zp_gallery,'theme');
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
		if(function_exists('getLatestNews')) {
			$latestnews = getLatestNews(5);
			if(empty($latestnews)) {
				echo 'No latest news';
			} else {
				foreach($latestnews as $news) {
					$newsobj = new ZenpageNews($news['titlelink']);
					$category = '';
					if($newsobj->inNewsCategory('extensions')) $category = ' (extensions)';
					if($newsobj->inNewsCategory('news')) $category = ' (news)';
					if($newsobj->inNewsCategory('user-guide')) $category = ' (user guide)';
					echo '<li><a href="'.getNewsURL($newsobj->getTitlelink()).'" title="'.html_encode($newsobj->getTitle()).'">'.$newsobj->getTitle().'</a> '.$category.'</li>';
				}
			}
		} else {
			echo 'No latest news';
		}
		?>
	</ul>
</div>

	<div class="infobox">
		<h3>Found a bug?!</h3>
		<p><img src="<?php echo $_zp_themeroot; ?>/images/icon-bugtracker.png" class="imgfloat-left" alt="" /> Please report any bugs you find with a detailed description via tickets at the <a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Zenhoto bugtracker on GitHub.com</a>.
		</p>
	</div>

 <div class="infobox">
  <h3>Share!</h3>
  <?php zp_printAddthis(); ?>
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
	$latestimage = getImageStatistic(1, 'latest', 'screenshots',true);
	if(!empty($latestimage)) {
		$image = $latestimage[0];
	 	$imgtitle = $image->getTitle();
	 	$thumb = $image->getCustomImage(NULL,238,128,238,128,NULL,NULL,TRUE);
	}
	?>
	<a href="<?php echo WEBPATH; ?>/screenshots/"	title="<?php echo html_encode($imgtitle); ?>">
	<img src="<?php echo html_encode($thumb); ?>" alt="<?php echo html_encode($imgtitle); ?>" /> </a>
	<br />
	<p>
	<a href="<?php echo WEBPATH; ?>/screenshots/" title="Zenphoto screenshots and screencasts">Learn about using Zenphoto</a>
	</p>
</div>

<?php zp_printSponsorAds(true); ?>
<br />
<div class="infobox">
<h3>Like using Zenphoto? Donate!</h3>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input
		type="hidden" name="cmd" value="_xclick"> <input type="hidden"
		name="business" value="tharward@berkeley.edu"> <input type="hidden"
		name="item_name" value="Zenphoto"> <input type="hidden" name="no_note"
		value="1"> <input type="hidden" name="currency_code" value="USD"> <input
		type="hidden" name="tax" value="0"> <input type="hidden" name="bn"
		value="PP-DonationsBF"> <input type="image"
		src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0"
		name="submit"
		alt="Make payments with PayPal - it's fast, free and secure!"> <img
		alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif"
		width="1" height="1"></form>
<p>Your support helps pay for this server, and helps development of
zenphoto. Thank you!</p>
</div>




</div><!-- infobox-m end -->

<div class="column-r">

	<div class="infobox">
		<?php
		$image = '';
		$imgtitle = '';
		$imglink = '';
		$thumb = '';
		$latestimage = getImageStatistic(1, 'latest', 'showcase',false);
		if(!empty($latestimage)) {
			$image = $latestimage[0];
			$showcase = new Album($_zp_gallery,'showcase');
			$number = $showcase->getNumImages();
			$imgtitle = $image->getTitle();
			$imglink = $image->getImageLink();
			$thumb = $image->getCustomImage(NULL,238,128,238,128,NULL,NULL,TRUE);
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
			<a href="http://www.zenphoto.org/support" title="Zenphoto forums">zenphoto support forums</a> (Registration required for posting). Please do not e-mail us asking for help. Thanks!
		</p>
		<br />
			<h3>Need project help?</h3>
  	<p>Visit the <a href="http://www.zenphoto.org/pages/paid-support">paid support page</a>.
  	</p>
	</div>

<div class="infobox">
		<h3>Get involved!</h3>
		<p>You would like to contribute? You don't need to be a programmer! <a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Read here what you can do for Zenphoto!</a>
		</p>
	</div>



</div><!-- infobox-r end -->

<div class="infobox-3columns">
<h3>What our users say</h3>
<p class="version_requirements"><em>&#8220;I just wanna say thanks for bringing out such a great photo-gallery to the community! Zenphoto is perfect because of its simplicity, usability and of course its
design.&#8221;</em><br /><a	href="http://www.zenphoto.org/support/topic.php?id=1742&amp;replies=1">— crushmaster</a></p>
<p class="version_requirements"><em>&#8220;I found ZenPhoto by accident and it is a godsend! I was using Menalto Gallery before and I am so frustrated with its bloated code and Web 1.0 architecture! I was able to redo the site in less than a week using ZenPhoto! You guys did a great
job! Thanks!&#8221;</em><br /><a href="http://www.zenphoto.org/support/topic.php?id=2051&amp;page=2&amp;replies=84">— pixelfreak</a></p>
<p class="version_requirements"><em>&#8220;I been using zenphoto for a while, mainly because I love its versatility and how easy it is to adapt it to an existing design.&#8221;</em><br /><a	href="http://www.zenphoto.org/support/topic.php?id=2051&amp;replies=84">— zucye</a></p>
<p class="version_requirements">
<a href="http://www.zenphoto.org/pages/testimonials">Read more testimonials</a></p>
</div>
<br style="clear: both" />

</div> <!-- frontcontent end -->



<div id="ads" style="margin-left: 40px">
<script type="text/javascript">
<!--
google_ad_client = "pub-7903690389990760";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";		//-->
</script>

 <script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">		</script>

<!-- The main column ends  --> <?php include('footer.php'); ?>
