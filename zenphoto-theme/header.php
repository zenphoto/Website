<?php
// force UTF-8 Ø
if (!defined('WEBPATH'))
	die();
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php zp_apply_filter('theme_head'); ?>
		<title><?php echo zporg::printPageHeaderTitle(); ?></title>
		<meta charset="<?php echo LOCAL_CHARSET; ?>">
		<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
		<meta name="author" content="The Zenphoto team and contributors">
		<meta name="copyright" content="(c) http://www.zenphoto.org – The Zenphoto team">
		<?php 
			if(is_Pages()) { 
				$parents = $_zp_current_zenpage_page->getParents();
				if(in_array('all-contributors',$parents)) {
					$googleprofile = getCodeblock(1);
					if($googleprofile) {
						?>
						<link rel="me" href="<?php echo html_encode($googleprofile); ?>?rel=author">
						<?php
					}
				}	
			}
		?>
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
			<script src="<?php echo $_zp_themeroot; ?>/js/jquery-syntax/jquery.syntax.min.js"></script>
		<?php } ?>
		<?php if ($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
			<script src="<?php echo $_zp_themeroot; ?>/js/jquery.tableofcontents-mod.min.js"></script>
			<script>
				$(document).ready(function() {
					if ($("#entrybody h2").length == 0) {
						$("#toc").remove();
					}
					$("#toc").tableOfContents('#entrybody', {
						startLevel: 2,
						depth: 2,
						topLinks: true,
						topBodyId: top
					});
				});
			</script>
		<?php } ?>

		<script>
			$(document).ready(function() {


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
		</script>
	</head>
	<body id="top">
		<?php zp_apply_filter('theme_body_open'); ?>
		<div id="container">
			
			<header id="header">
				<div class="header_top">
					<nav class="secondarynav">
						<ul>
							<li><a href="https://github.com/zenphoto/zenphoto/issues?state=open" title="Bugtracker (GitHub)">Bugtracker <small>(GitHub)</small></a></li>
							<li><?php printPageURL('Get involved', 'get-involved', '', '', NULL); ?></li>
							<li><a href="#footer" title="Get involved!">Stay tuned!</a></li>
							<li><?php printPageURL('Paid support', 'paid-support', '', '', NULL); ?></li>
							<li><a class="sponsors" href="<?php echo WEBPATH; ?>/sponsors/" title="Sponsors">Sponsors</a></li>
						</ul>
					</nav>
					<?php printSearchForm(); ?>
				</div>
				<div id="header_logo">
					<?php if ($_zp_gallery_page == 'index.php') { ?>
							<h1 id="logo"><a href="<?php echo getGalleryIndexURL(); ?>"><img src="<?php echo $_zp_themeroot; ?>/images/logo-new.png" alt="ZenphotoCMS" /><span>Zenphoto</span></a></h1>
					<p>The <em>simpler</em> media website CMS</p>
					<?php } else { ?>
						<div id="logo"><a href="<?php echo getGalleryIndexURL(); ?>"><img src="<?php echo $_zp_themeroot; ?>/images/logo-new.png" alt="ZenphotoCMS" /><span>ZenphotoCMS</span></a></div>
					<p>The <em>simpler</em> media website CMS</p>
					
					<?php } ?>
				
				</div>
			</header>
			
			<?php
			$uralbumname = null;
			if (!is_null($_zp_current_album)) {
				$uralbum = getUrAlbum($_zp_current_album);
				$uralbumname = $uralbum->name;
			}
			?>
			<nav id="mainnav">
				<ul>
					<li<?php if ($_zp_gallery_page == 'index.php') echo ' id="activelink"'; ?>><a href="<?php echo getGalleryIndexURL(); ?>">Home</a></li>
					<li<?php if ((is_null($_zp_current_category) && $_zp_gallery_page == 'news.php' && !is_NewsArticle()) || zporg::inNewsCategory('news')) echo ' id="activelink"'; ?>>
						<a href="<?php echo WEBPATH; ?>/news">News</a></li>
					<li><a href="http://demo.zenphoto.org" target="_blank">Demo</a></li>
					<li<?php if ($uralbumname == 'screenshots') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/screenshots/">Screenshots</a></li>
					<li<?php if (zporg::inNewsCategory('user-guide')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/user-guide">User Guide</a></li>
					<li><a href="http://forum.zenphoto.org" title="Zenphoto forum">Forum</a></li>
					<li<?php if ($uralbumname == 'theme') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/theme/">Themes</a></li>
					<li<?php if (zporg::inNewsCategory('extensions')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/extensions">Extensions</a></li>
					<li<?php if ($uralbumname == 'showcase') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/showcase/">Showcase</a></li>
				</ul>
			</nav>