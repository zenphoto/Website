<?php

// force UTF-8 Ø

class ThemeOptions {

	function __construct() {
		if (class_exists('cacheManager')) {
			/* cacheManager::deleteThemeCacheSizes($me);
			  cacheManager::addThemeCacheSize($me, NULL, 580, 580, NULL, NULL, NULL, NULL, NULL, false, NULL, true);
			  cacheManager::addThemeCacheSize($me, 95, NULL, NULL, getThemeOption('thumb_crop_width'), getThemeOption('thumb_crop_height'), NULL, NULL, true, NULL, NULL, NULL); */
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
						)
		);
	}

	function handleOption($option, $currentValue) {
		
	}

}

?>