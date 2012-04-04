<?php
/**
 * Gallery
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
 *
 * Gallery is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gallery is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Gallery; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gallery
 */
/**
 * Abstract base class for different import methods. Not to be initialized directly, but rather
 * extended in derivative classes for various implementation methods.
 * 
 * @package gallery
 */
abstract class galImport {
    const OPT_EXTENSIONS = 'extensions';
    const OPT_USE_MULTIBYTE = 'use_multibyte';
    const OPT_ENCODING = 'encoding';
    
    /** @var Gallery A reference to the Gallery object */
    public $gallery;
    /** @var xPDO A reference to the modX object */
    public $modx;
    /** @var array A configuration array of properties */
    public $config = array();
    /** @var string The source file/directory/url for the import */
    public $source;
    /** @var string The target directory for the imported items */
    public $target;
    /** @var integer The Album ID number to import into */
    public $albumId;
    /** @var array An array of results of the import */
    public $results = array();
    /** @var array An array of errors returned by the import */
    public $errors = array();

    function __construct(Gallery &$gallery,array $config = array()) {
        $this->gallery =& $gallery;
        $this->modx =& $gallery->modx;
        $this->config = array_merge(array(
            galImport::OPT_EXTENSIONS => explode(',',$this->modx->getOption('gallery.import_allowed_extensions',null,'jpg,jpeg,png,gif,bmp')),
            galImport::OPT_USE_MULTIBYTE => $this->modx->getOption('use_multibyte',null,false),
            galImport::OPT_ENCODING => $this->modx->getOption('modx_charset',null,'UTF-8'),
        ),$config);
        $this->initialize();
    }

    /**
     * Initialize the derivative import class and run any pre-import setup options.
     * 
     * @abstract
     * @return void
     */
    abstract public function initialize();

    /**
     * Set the source directory/url/file for the import. Must be implemented in your derivative class.
     *
     * @abstract
     * @param string|array $source
     * @param array $options
     * @return bool
     */
    abstract public function setSource($source,array $options = array());

    /**
     * Set the target album for the import
     * 
     * @param string|int $albumId
     * @param array $options
     * @return bool|string
     */
    public function setTarget($albumId,array $options = array()) {
        $this->albumId = $albumId;
        $this->target = $this->modx->call('galAlbum','getFilesPath',array(&$this->modx)).$albumId.'/';
        /* get sanitized base path and current path */
        $cacheManager = $this->modx->getCacheManager();
        /* if directory doesnt exist, create it */
        if (!file_exists($this->target) || !is_dir($this->target)) {
            if (!$cacheManager->writeTree($this->target)) {
               $this->modx->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$this->target);
               return $this->modx->lexicon('gallery.directory_err_create',array('directory' => $this->target));
            }
        }
        /* make sure directory is readable/writable */
        if (!is_readable($this->target) || !is_writable($this->target)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$this->target);
            return $this->modx->lexicon('gallery.directory_err_write',array('directory' => $this->target));
        }
        return true;
    }

    /**
     * Process tags and add them to item
     * 
     * @param string|array $tags
     * @param string|int $itemId
     * @return bool
     */
    public function processTags($tags,$itemId) {
        $tagNames = is_array($tags) ? $tags : explode(',',$tags);
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) continue;

            $tag = $this->modx->newObject('galTag');
            $tag->set('item',$itemId);
            $tag->set('tag',$tagName);
            $tag->save();
        }
        return true;
    }

    /**
     * Associate an item with an album
     * @param string|int $itemId
     * @return bool
     */
    public function associateToAlbum($itemId) {
        /* get count of items in album */
        $total = $this->modx->getCount('galAlbumItem',array('album' => $this->albumId));

        /* associate with album */
        $albumItem = $this->modx->newObject('galAlbumItem');
        $albumItem->set('album',$this->albumId);
        $albumItem->set('item',$itemId);
        $albumItem->set('rank',$total);
        return $albumItem->save();
    }

    /**
     * Run the import script. Return a non-true value to display an error.
     *
     * @abstract
     * @param array $options
     * @return bool|string
     */
    abstract public function run(array $options);
}