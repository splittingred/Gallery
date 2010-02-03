<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galalbumitem.class.php');
class galAlbumItem_mysql extends galAlbumItem {}
?>