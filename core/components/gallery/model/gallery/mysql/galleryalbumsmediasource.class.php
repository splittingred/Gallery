<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galleryalbumsmediasource.class.php');
class GalleryAlbumsMediaSource_mysql extends GalleryAlbumsMediaSource {}
?>