<?php // force UTF-8 Ø
if (!defined('WEBPATH')) die();
header('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php zp_apply_filter('theme_head'); ?>
	<title><?php echo zp_printPageHeaderTitle(); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo getOption('charset'); ?>" />
	<!-- touch icons for tablet and smartphone bookmarks -->
	<link rel="apple-touch-icon" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_zp_themeroot; ?>/images/apple-touch-icon-144x144.png" />
	<link rel="stylesheet" media="screen" href="<?php echo $_zp_themeroot; ?>/style.css" type="text/css" />
	<!--
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />	
	<link rel="stylesheet" media="media screen and (max-width:768px)" href="<?php echo $_zp_themeroot; ?>/style-w760.css" type="text/css" />
	<link rel="stylesheet" media="media screen and (device-width: 768px) and (orientation: portrait)" href="<?php echo $_zp_themeroot; ?>/style-w760.css" type="text/css" />
	<link rel="stylesheet" media="media screen and (max-device-width: 1024px) and (orientation: landscape)" href="<?php echo $_zp_themeroot; ?>/style-w1000.css" type="text/css" />

	<link rel="stylesheet" media="media screen and (min-device-width : 320px) and (max-device-width : 800px)" href="<?php echo $_zp_themeroot; ?>/style-w320.css" type="text/css" />
	<link rel="stylesheet" media="media screen and (min-device-width : 321px) and (max-device-width : 800px)" href="<?php echo $_zp_themeroot; ?>/style-w320.css" type="text/css" />
	<link rel="stylesheet" media="media screen and (max-device-width : 320px)" href="<?php echo $_zp_themeroot; ?>/style-w320.css" type="text/css" />
   -->
		
	<?php printZenpageRSSHeaderLink('NewsWithImages','','',''); ?>
	<?php if($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
		<script src="<?php echo $_zp_themeroot; ?>/js/jquery-syntax/jquery.syntax.min.js" type="text/javascript"></script>
	<?php } ?>
	<?php if($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
		<script src="<?php echo $_zp_themeroot; ?>/js/jquery.tableofcontents-mod.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){ 
				if($("#entrybody h4").length == 0) {
					$("#toc").remove();
				}
				$("#toc").tableOfContents('#entrybody', {
						startLevel:3,
						depth: 5,
						topLinks: true,
						topBodyId: top
				}); 
			});
		</script>
		<?php } ?>
		
		<?php if($_zp_gallery_page == 'index.php') { ?>
			<script type="text/javascript" src="<?php echo WEBPATH.'/'.ZENFOLDER.'/'.PLUGIN_FOLDER; ?>/slideshow/jquery.cycle.all.js"></script>
			<script type="text/javascript">
			$(function() {
			
					$('#slideshow').cycle({
							fx:      'fade',
							speed: 1500,
							timeout: 6500,
							prev:    '#slideprev',
							next:    '#slidenext',
							pager:   '#slidenav',
							pagerAnchorBuilder: pagerFactory
					});
			
					function pagerFactory(idx, slide) {
							var s = idx > 2 ? '' : '';
							return '<li'+s+'><a href="#">'+(idx+1)+'</a></li>';
					};
					$('#slidepause').click(function() { $('#slideshow').cycle('pause'); return false; });
					$('#slideplay').click(function() { $('#slideshow').cycle('resume'); return false; });
			
			});
			</script>
	<?php } ?>
	
	<script type="text/javascript">
   	$(document).ready(function(){
   	 		<?php if($_zp_gallery_page == 'news.php' || $_zp_gallery_page == 'pages.php') { ?>
    			$("pre").addClass("syntax php");
    			$("code").addClass("syntax php");
        	// This function highlights (by default) pre and code tags which are annotated correctly.
       	 	$.syntax();
        <?php } ?>

				<?php if($_zp_gallery_page != 'index.php') { ?>
					$("a.colorbox,a.zenpage_fullimagelink").colorbox({maxWidth:"98%", maxHeight:"98%"});
				<?php } ?>
				
				$('#search #search_input').attr('value','Search site (except forum)');
  			$('#search #search_input').focus(function() {
  				$(this).val('');
  			});
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
<body id="top">
<?php zp_apply_filter('theme_body_open'); ?>
<div id="container">
	<div id="banner">
	 	<ul>
	 		<li><a href="http://www.zenphoto.org/pages/bugtracker" title="Zenphoto bugtracker">Bugtracker</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Get involved!</a></li>
	 		<li><a href="#stay-tuned" title="Get involved!">Stay tuned!</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/paid-support" title="Paid support">Paid support</a></li>
	 		<li><a class="sponsors" href="http://www.zenphoto.org/hosting" title="Hosting">Hosting</a></li>
	 	</ul>
		<div id="header_logo">
			<h1 id="logo" title="ZenPhoto">Zenphoto</h1>
            <p>The <em>simpler</em> media website CMS</p>
		</div>
	</div>
	<?php
	$uralbumname = null;
	if(!is_null($_zp_current_album)) {
		$uralbum = getUrAlbum($_zp_current_album);
		$uralbumname = $uralbum->name;
	}
	?>
	<div id="mainnav">
		<ul>
		  <li<?php if($_zp_gallery_page == 'index.php') echo ' id="activelink"'; ?>><a href="<?php echo getGalleryIndexURL(); ?>">Download</a></li>
			<li<?php
			if((is_null($_zp_current_category) && $_zp_gallery_page == 'news.php' && !is_NewsArticle()) || zp_inNewsCategory('news')) echo ' id="activelink"'; ?>>
			<a href="<?php echo WEBPATH; ?>/news">News</a></li>
	 		<li><a href="/demo">Demo</a></li>
	 		<li<?php if($uralbumname == 'screenshots') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/screenshots/">Screenshots</a></li>
	 		<li<?php if(zp_inNewsCategory('user-guide')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/user-Guide">User Guide</a></li>
	 		<li><a href="/support" title="Zenphoto forum">Forum</a></li>
	 		<li<?php if($uralbumname == 'theme') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/theme/">Themes</a></li>
	 		<li<?php if(zp_inNewsCategory('extensions')) echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/news/category/extensions">Extensions</a></li>
	 		<li<?php if($uralbumname == 'showcase') echo ' id="activelink"'; ?>><a href="<?php echo WEBPATH; ?>/showcase/">Showcase</a></li>
		</ul>
	</div>