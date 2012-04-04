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
 * Upload an item into an album
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * @package gallery
 */
class GalleryItemUploadProcessor extends modObjectProcessor {
    public $classKey = 'galItem';
    public $objectType = 'gallery.item';
    public $languageTopics = array('gallery:default');
    /** @var galItem $object */
    public $object;

    public function initialize() {
        $album = $this->getProperty('album',false);
        if (empty($album)) return $this->modx->lexicon('gallery.album_err_ns');
        return parent::initialize();
    }

    public function process() {
        $this->setCheckbox('active');
        if (empty($_FILES['file'])) $this->addFieldError('file',$this->modx->lexicon('gallery.item_err_ns_file'));
        if ($this->hasErrors()) {
            return $this->failure();
        }

        /** @var galItem $item */
        $this->object = $this->modx->newObject('galItem');
        $this->object->fromArray($this->getProperties());
        $this->object->set('createdby',$this->modx->user->get('id'));

        if (empty($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
            return $this->failure($this->modx->lexicon('gallery.item_err_ns_file'));
        }

        if (!$this->object->save()) {
            return $this->failure($this->modx->lexicon('gallery.item_err_save'));
        }

        if (!$this->object->upload($_FILES['file'],$this->getProperty('album'))) {
            $this->object->remove();
            return $this->failure($this->modx->lexicon('gallery.item_err_upload'));
        }
        $this->object->save();

        $this->associateToAlbum();
        $this->setTags();
        return $this->cleanup();
    }

    public function associateToAlbum() {
        $album = $this->getProperty('album');

        /* get count of items in album */
        $total = $this->modx->getCount('galAlbumItem',array('album' => $album));

        /** @var galAlbumItem $albumItem */
        $albumItem = $this->modx->newObject('galAlbumItem');
        $albumItem->set('album',$album);
        $albumItem->set('item',$this->object->get('id'));
        $albumItem->set('rank',$total);
        return $albumItem->save();
    }

    public function setTags() {
        /* save tags */
        $tags = $this->getProperty('tags');
        if (isset($tags)) {
            $tagNames = explode(',',$tags);
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                /** @var galTag $tag */
                $tag = $this->modx->newObject('galTag');
                $tag->set('item',$this->object->get('id'));
                $tag->set('tag',$tagName);
                $tag->save();
            }
        }
    }

    public function cleanup() {
        $itemArray = $this->object->toArray();
        unset($itemArray['description']);
        return $this->success('',$itemArray);
    }
}
return 'GalleryItemUploadProcessor';