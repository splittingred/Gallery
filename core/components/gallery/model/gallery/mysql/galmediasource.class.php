<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galmediasource.class.php');
class galMediaSource_mysql extends galMediaSource {}
?>