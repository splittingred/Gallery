<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galalbum.class.php');
class galAlbum_mysql extends galAlbum {}
?>