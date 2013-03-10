<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php bb_language_attributes( '1.1' ); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php bb_title() ?></title>
	<?php bb_feed_head(); ?> 
	<link rel="stylesheet" href="<?php bb_stylesheet_uri(); ?>" type="text/css" />
<?php if ( 'rtl' == bb_get_option( 'text_direction' ) ) : ?>
	<link rel="stylesheet" href="<?php bb_stylesheet_uri( 'rtl' ); ?>" type="text/css" />
<?php endif; ?>

<?php bb_head(); ?>

</head>

<body id="<?php bb_location(); ?>">
	
	<div id="wrapper">
	
	<div id="banner">
	 	<ul>
	 		<li><a href="http://www.zenphoto.org/support" title="Forum" style="color: black">Forum</a></li>
	 		<li><a href="/trac/report/10" title="bugtracker">Bugtracker</a></li>
	 		<li><a href="http://www.zenphoto.org/get-involved" title="Get involved">Get involved!</a></li>
	 	</ul>
		<div id="header_logo"> 
			<a href="/"><h1 id="logo" title="ZenPhoto">ZenPhoto Forums</h1></a>
      <p><em>simpler</em> web gallery management</p>
		</div>
	</div>
	<div id="mainnav">
		<ul>
		  <li><a href="http://www.zenphoto.org/index.php">Download</a></li>
			<li><a href="http://www.zenphoto.org/category/News">News</a></li>
	 		<li><a href="http://www.zenphoto.org/zenphoto">Demo</a></li>
	 		<li><a href="http://www.zenphoto.org/zp/screenshots/">Screenshots</a></li>
	 		<li><a href="http://www.zenphoto.org/category/User-Guide">User Guide</a></li>
	 		<li><a href="http://www.zenphoto.org/zp/theme/">Themes</a></li>
	 		<li><a href="http://www.zenphoto.org/category/Extensions">Extensions</a></li>
	 		<li><a href="http://www.zenphoto.org/zp/showcase/">Showcase</a></li>
		</ul>
	</div>

		<div id="main"> 
	  <?php include("sidebar.php"); ?>
		</div>
		
			<div id="content">
			<?php login_form(); ?>
			<?php if ( is_bb_profile() ) profile_menu(); ?>