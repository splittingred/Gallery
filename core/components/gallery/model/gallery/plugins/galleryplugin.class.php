<?php
/**
 * @package gallery
 */
abstract class GalleryPlugin {
    public $gallery = null;
    public $modx = null;
    public $config = array();

    function __construct(Gallery &$gallery,array $config = array()) {
        $this->gallery =& $gallery;
        $this->modx =& $gallery->modx;
        $this->config = array_merge(array(

        ),$config);
    }

    public function adjustSettings(array $scriptProperties = array()) {
        return $scriptProperties;
    }

    abstract public function load();
}