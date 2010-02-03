<?php
/**
 * @package gallery
 * @subpackage controllers
 */
require_once dirname(dirname(__FILE__)).'/model/gallery/gallery.class.php';
$gallery = new Gallery($modx);
return $gallery->initialize('mgr');