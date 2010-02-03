<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galtag.class.php');
class galTag_mysql extends galTag {}
?>