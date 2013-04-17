<?php
/** sets the titl of the gallery to include the version
 *
 * @package plugins
 * @subpackage zenphoto
 */
$plugin_is_filter = 5|THEME_PLUGIN;
$plugin_description = gettext('Provides a dynamic gallery title.');
$plugin_author = "Stephen Billard (sbillard)";

$_zp_gallery->setTitle('Zenphoto v'.ZENPHOTO_VERSION.' demo');
?>
