<?php
/**
 * @package gallery
 */
abstract class galImport {
    /** @var Gallery A reference to the Gallery object */
    public $gallery;
    /** @var xPDO A reference to the modX object */
    public $modx;
    /** @var array A configuration array of properties */
    public $config = array();
    /** @var string The source file/directory/url for the import */
    public $source;

    function __construct(Gallery &$gallery,array $config = array()) {
        $this->gallery =& $gallery;
        $this->modx =& $gallery->modx;
        $this->config = array_merge(array(

        ),$config);
        $this->initialize();
    }

    abstract public function initialize();

    abstract public function setSource($source,array $options);

    abstract public function run($albumId,array $options);
}