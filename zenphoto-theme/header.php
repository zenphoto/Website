<?php
// force UTF-8 Ø
if (!defined('WEBPATH'))
	die();
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
?>
<!DOCTYPE>
<html>
	<head>
		<?php zp_apply_filter('theme_head'); ?>
		<title><?php echo zp_printPageHeaderTitle(); ?></title>
		<meta charset="<?php echo LOCAL_CHARSET; ?>">
		<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
		<!-- touch icons for tablet and smartphone bookmarks -->
		<link rel="apple-touch-icon" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-144x144.png" />
		<link rel="stylesheet" media="screen" href="<?php echo $_zp_themeroot; ?>/style.css" type="text/css" />
		<!--
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		-->

		<?php if ($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
			<script src="<?php echo $_zp_themeroot; ?>/js/jquery-syntax/jquery.syntax.min.js" type="text/javascript"></script>
		<?php } ?>
		<?php if ($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
			<script src="<?php echo $_zp_themeroot; ?>/js/jquery.tableofcontents-mod.min.js" type="text/javascript" charset="utf-8"></script>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					if ($("#entrybody h4").length == 0) {
						$("#toc").remove();
					}
					$("#toc").tableOfContents('#entrybody', {
						startLevel: 3,
						depth: 5,
						topLinks: true,
						topBodyId: top
					});
				});
			</script>
		<?php } ?>

		<script type="text/javascript">
			$(document).ready(function() {
<?php if ($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
					$("pre").addClass("syntax php");
					$("code").addClass("syntax php");
					// This function highlights (by default) pre and code tags which are annotated correctly.
					$.syntax({
						brush: 'php',
						tabWidth: 2,
						layout: 'fixed'
					});
<?php } ?>

<?php if ($_zp_gallery_page != 'index.php') { ?>
					$("a.colorbox,a.zenpage_fullimagelink").colorbox({
						maxWidth: "98%",
						maxHeight: "98%"
					});
<?php } ?>

				$('#search #search_input').attr('value', 'Search site (except forum)');
				$('#search #search_input').click(function() {
					$(this).val('');
				});
			});

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-191896-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();



		</script>
	</head>
	<body id="top">
		<?php zp_apply_filter('theme_body_open'); ?>
		<div id="container">
			<div id="header">
				<ul>
					<li><a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Bugtracker <small>(GitHub)</small></a></li>
					<li><?php printPageURL('Get involved', 'get-involved', '', '', NULL); ?></li>
					<li><a href="#stay-tuned" title="Get involved!">Stay tuned!</a></li>
					<li><?php printPageURL('Paid support', 'paid-support', '', '', NULL); ?></li>
					<li><a class="sponsors" href="<?php echo WEBPATH; ?>/hosting" title="Hosting">Hosting</a></li>
				</ul>
				<div id="header_logo">
					<h1 id="logo"><a href="<?php echo getGalleryIndexURL(); ?>"><img src="<?php echo $_zp_themeroot; ?>/images/logo-new.png" alt="Zenphoto" /><span>Zenphoto</span></a></h1>
					<p>The <em>simpler</em> media website CMS</p>
				</div>
			</div>
			<?php
			$uralbumname = null;
			if (!is_null($_zp_current_album)) {
				$uralbum = getUrAlbum($_zp_current_album);
				$uralbumname = $uralbum->name;
			}
			?>
			<div id="mainnav">
				<ul>
					<li<?php if ($_zp_gallery_page == 'index.php') echo ' id="activelink"'; ?>><a href="<?php echo getGalleryIndexURL(); ?>">Download</a></li>
					<li<?php if ((is_null($_zp_current_category) && $_zp_gallery_page == 'news.php' && !is_NewsArticle()) || zp_inNewsCategory('news')) echo ' id="activelink"'; ?>>
						<a href="<?php echo WEBPATH; ?>/news">News</a></li>
					<li><a href="<?php echo WEBPATH; ?>/demo">Demo</a></li>
					<li<?php if ($uralbumname == 'screenshots') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/screenshots/">Screenshots</a></li>
					<li<?php if (zp_inNewsCategory('user-guide')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/user-guide">User Guide</a></li>
					<li><a href="/support" title="Zenphoto forum">Forum</a></li>
					<li<?php if ($uralbumname == 'theme') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/theme/">Themes</a></li>
					<li<?php if (zp_inNewsCategory('extensions')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/extensions">Extensions</a></li>
					<li<?php if ($uralbumname == 'showcase') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/showcase/">Showcase</a></li>
				</ul>
			</div>