<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galalbumcontext.class.php');
class galAlbumContext_mysql extends galAlbumContext {}
?>