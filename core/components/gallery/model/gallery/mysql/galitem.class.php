<?php
/**
 * @package gallery
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/galitem.class.php');
class galItem_mysql extends galItem {}
?>