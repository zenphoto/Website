<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo getBareGalleryTitle(); ?></title>
	<?php zp_apply_filter('theme_head'); ?>
	<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/slideshow.css" type="text/css" />

	<?php printSlideShowJS(); ?>
</head>

<body>
		<?php zp_apply_filter('theme_body_open'); ?>
	<div id="slideshowpage">
			<?php printSlideShow(true,true); ?>
	</div>
	<?php zp_apply_filter('theme_body_close'); ?>
</body>
</html>