<?php // force UTF-8 Ø
if (!defined('WEBPATH')) die();
header('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php zp_apply_filter('theme_head'); ?>
	<title>Zenphoto hosting</title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/hosting.css" type="text/css" />
	<script type="text/javascript">
   	$(document).ready(function(){
			$(".inline").colorbox({iframe:true,innerWidth:"300px",innerHeight:"250px"});
		});

	var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-191896-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
	</script>
	</head>
<body>
<div id="site">
			<div id="header">
			The best galler CMS out there taken to a new level!
			</div>

			<div id="content">
				<h1 id="content-left">
					Zenphoto Hosting - Your site. Your content. Your Control.
				</h1>

				<div id="content-right">
				 <h2>Your benefits:</h2>
				 <p>You just manage your site, we take care of all the technical stuff for you. Besides standard hosting feature this includes:</p>
					<ul id="features">
						<li>Frequent updates included</li>
						<li>Hourly backups</li>
						<li>24/7 personal support, multi-lingual</li>
						<li>Premium themes and plugins</li>
						<li>Domain, E-Mail, unlimited space and traffic</li>
						<li>Price: $8 per month</li>
					</ul>
					<p><strong>Sign on to be notified when this new service launched. (Expected in June 2012!)</strong></p>
					<a class="inline button" href="hosting/signup-hosting.png.php">Sign Up</a>
					<p class="readmore"><a class="inline" href="hosting/learn-more.png.php">Click here to learn more</a></p>
				</div>

			</div>

			<div id="footer">

				<ul id="footer1">
					<li class="icon1">Manage images and albums!</li>
					<li class="icon2">Manage videos files!</li>
					<li class="icon3">Manage audio files!</li>
					<li class="icon4">Manage blog and pages!</li>
				</ul>

			</div>
		</div>



<?php
zp_apply_filter('theme_body_close');
?>
</body>
</html>