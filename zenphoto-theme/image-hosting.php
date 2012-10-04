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
	<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/hosting.css" type="text/css" />		</head>
<body>

<div>
	<div id="sign-on">
		<h2>Happy April Fools' Day!</h2>
		<h3>by the Zenphoto team</h3>
		<p>Sorry, no such hosting! But if you like using Zenphoto please support our work by donating or visiting our <a href="http://www.zenphoto.org/sponsors">sponsors</a> (or by <a href="http://www.zenphoto.org/pages/become-a-sponsor">becoming a sponsor</a>).</p>
		<p><small>If you don't know this fun tradition please see here:
		<a href="http://en.wikipedia.org/wiki/April_Fools'_Day">April Fools' Day</a>
		</small>
		</p>
	</div>
</div>
<?php
zp_apply_filter('theme_body_close');
?>
</body>
</html>