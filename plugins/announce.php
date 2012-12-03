<?php
/**
 * Use to send announcements to the Zenphoto announce group
 *
 * Only articles in the "announcements" category are enabled for this mailing. The currently logged in
 * administrator's name and e-mail are used as the "sender" of the mail.
 *
 * The content of the article is used for the body of the e-mail. Beginning and ending paragraph tags are
 * replaced by newlines. BR tags are also replaced by newlines.
 *
 * @package plugins
 * @author Stephen Billard (sbillard)
 * @subpackage tools
 */
$plugin_is_filter = 9|ADMIN_PLUGIN;
$plugin_description = gettext('Forward announcements to the zenphoto-announce Google group.');
$plugin_author = "Stephen Billard (sbillard)";

zp_register_filter('general_zenpage_utilities', 'zp_announce::checkbox');
zp_register_filter('save_article_custom_data', 'zp_announce::execute');

class zp_announce {

	static function checkbox($before, $object, $prefix=NULL) {
		if (get_class($object)=='ZenpageNews' && $object->inNewsCategory('announcements')) {
			$before .=  '<p class="checkbox">'."\n".'<label title="'.gettext("Send to the Google Zenphoto announce group.").'">'."\n".'<input type="checkbox" name="announce_me'.$prefix.'" id="announce_me'.$prefix.'" value="1" /> <img src="'.WEBPATH.'/'.ZENFOLDER.'/images/icon_mail.png" alt="" /> '.gettext('Announce')."\n</label>\n</p>\n";
		}
		return $before;
	}

	static function execute($custom, $object) {
		global $_zp_current_admin_obj;
		if (isset($_POST['announce_me'])) {
			$content = $object->getContent();
			$content = str_replace("\n", '', $content);
			$content = str_replace("\r", '', $content);
			$content = preg_replace('|<p[^>]*>?|i', "\r\n", $content);
			$content = preg_replace('|</p>?|i', "\r\n", $content);
			$content = preg_replace('|<ul[^>]*>?|i', "\r\n", $content);
			$content = preg_replace('|</ul>?|i', "\r\n", $content);
			$content = preg_replace('|<li[^>]*>?|i', " - ", $content);
			$content = preg_replace('|</li>?|i', "\r\n", $content);
			$content = preg_replace('|<br[^>]*/>?|i', "\r\n", $content);
			$content = trim(strip_tags($content), "\r\n");
			$result = zp_apply_filter('sendmail', '', array('zenphoto-announce'=>'zenphoto-announce@googlegroups.com'), strip_tags($object->getTitle()), $content, $_zp_current_admin_obj->getEmail(), $_zp_current_admin_obj->getName(), array(), NULL);
		}
		return $custom;
	}

}

?>