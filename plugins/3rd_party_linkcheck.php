<?php

/**
 * 3rd party link checker
 *
 * Checks links of 3rd party plugins and themes regarding accessibility.
 * If they are not the theme or plugin is considereed to be abandoned.
 * The entries then are marked unpublished and marked with the tag
 * <em>theme-abandoned</em> respectively <em>extension-abandoned</em>
 *
 * @package admin
 * @subpackage zenphoto
 */
$plugin_is_filter = 20 | ADMIN_PLUGIN;
$plugin_description = gettext('Checks info/download links of 3rd party theme and extensions entries. For Zenphoto.org only.');
$plugin_author = "Malte Müller (acrylian)";
$plugin_version = '1.4.5';

zp_register_filter('admin_utilities_buttons', 'linkcheckButton');

/*
  $linkcheckinterval = getOption('zenphoto_linkcheck_interval');
  $lastlinkcheck = getOption('zenphoto_linkcheck_last');
  if(time()-$lastlinkcheck < $linkcheckinterval) {
  setOption('zenphoto_linkcheck_last',time());
  zp_register_filter('theme_head','checkThemesAndExtensionURLs');
  }
 * ********************************************************
  Note: the asterix on option_interface below is to keep the admin pages from thinking there is an option handler for this plugin.
  Perhaps a better thing would to be either:
  a. remove all this code if it is not to be used
  or
  b. allow a real options handler but disable all the options.
 * ********************************************************
 * option_interface = 'linkchecker';

  class linkchecker {
  function linkchecker() {
  setOptionDefault('zenphoto_linkcheck_interval', 604800);
  setOptionDefault('zenphoto_linkcheck_last', time());
  }

  function getOptionsSupported() {
  return array(gettext('Linkcheck - interval') => array('key' => 'zenphoto_linkcheck_interval', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext("The interval the media should be changed. Default is 604800 (7 * 1 day (86400 seconds = 24 hrs * 60 min * 60 sec)."))
  );
  }
  }
 */

function linkcheckButton($buttons) {
	$buttons[] = array(
			'category' => gettext('Admin'),
			'enable' => true,
			'button_text' => gettext('3rd party link check'),
			'formname' => '3rd_party_linkcheck',
			'action' => FULLWEBPATH . '/' . USER_PLUGIN_FOLDER . '/3rd_party_linkcheck/3rd_party_linkcheck_utility.php',
			'icon' => FULLWEBPATH . '/' . ZENFOLDER . '/images/action.png',
			'title' => gettext('Checks links of 3rd party plugins and themes'),
			'alt' => '',
			'hidden' => '',
			'rights' => OVERVIEW_RIGHTS,
	);
	return $buttons;
}
