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
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function(){
			jQuery('.confirmclick').click(function() {
				jQuery('.confirm').show();
				jQuery('.confirmclick').hide();
			});
		});
	</script>
</head>

<body id="<?php bb_location(); ?>">

	<div id="wrapper">

	<div id="banner">
	 	<ul>
	 		<li><a href="http://www.zenphoto.org/trac/report" title="Zenphoto bugtracker">Bugtracker</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/get-involved" title="Get involved!">Get involved!</a></li>
	 		<li><a href="#stay-tuned" title="Get involved!">Stay tuned!</a></li>
	 		<li><a href="http://www.zenphoto.org/pages/paid-support" title="Paid support">Paid support</a></li>
	 		<li><a class="sponsors" href="http://www.zenphoto.org/hosting" title="Hosting">Hosting</a></li>
	 	</ul>
		<div id="header_logo">
			<a href="http://www.zenphoto.org"><h1 id="logo" title="ZenPhoto">Zenphoto Forums</h1></a>
      <p>The <em>simpler</em> media website CMS</p>
		</div>
	</div>
	<div id="mainnav">
		<ul>
		  <li><a href="http://www.zenphoto.org">Download</a></li>
			<li><a href="http://www.zenphoto.org/news">News</a></li>
	 		<li><a href="http://www.zenphoto.org/demo">Demo</a></li>
	 		<li><a href="http://www.zenphoto.org/screenshots/">Screenshots</a></li>
	 		<li><a href="http://www.zenphoto.org/news/category/user-guide">User Guide</a></li>
	 		<li id="activelink"><a href="http://www.zenphoto.org/support">Forum</a></li>
	 		<li><a href="http://www.zenphoto.org/theme/">Themes</a></li>
	 		<li><a href="http://www.zenphoto.org/news/category/extensions">Extensions</a></li>
	 		<li><a href="http://www.zenphoto.org/showcase/">Showcase</a></li>
		</ul>
	</div>

		<div id="main">
	  <?php include("sidebar.php"); ?>
		</div>

			<div id="content">
			<?php login_form(); ?>
			<?php if ( is_bb_profile() ) profile_menu(); ?>