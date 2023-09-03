<?php

// force UTF-8 Ø
class ThemeOptions {

	function __construct() {
		if (class_exists('cacheManager')) {
			/* cacheManager::deleteThemeCacheSizes($me);
			  cacheManager::addThemeCacheSize($me, NULL, 580, 580, NULL, NULL, NULL, NULL, NULL, false, NULL, true);
			  cacheManager::addThemeCacheSize($me, 95, NULL, NULL, getThemeOption('thumb_crop_width'), getThemeOption('thumb_crop_height'), NULL, NULL, true, NULL, NULL, NULL); */
			setThemeOptionDefault('zporg_devbuildlink_name', 'Support build (GitHub master)');
			setThemeOptionDefault('zporg_devbuildlink_url', 'https://github.com/zenphoto/zenphoto/archive/master.zip');
		}
	}

	function getOptionsSupported() {

		return array(
				gettext('Forum url') => array(
						'key' => 'zporg_forumurl',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Set the full url to the forum')
						),
				gettext('Demo url') => array(
						'key' => 'zporg_demourl',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Set the full url to the demo install')
						),
				gettext('Support build Link - Name') => array(
						'key' => 'zporg_supportbuildlink_name',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Define here what it should be named.')
						),
				gettext('Support build Link - URL') => array(
						'key' => 'zporg_supportbuildlink_url',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Define the URL here.')
						),
				gettext('Development build Link - Name') => array(
						'key' => 'zporg_devbuildlink_name',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Define here what it should be named.')
						),
				gettext('Development build Link - URL') => array(
						'key' => 'zporg_devbuildlink_url',
						'type' => OPTION_TYPE_TEXTBOX,
						'desc' => gettext('Define the URL here.')
						)
		);
	}

	function handleOption($option, $currentValue) {
		
	}

}

?>