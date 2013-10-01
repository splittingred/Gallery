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
 * Get a list of Album Items
 *
 * @package gallery
 * @subpackage processors
 */
class GalleryItemGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'galItem';
    public $objectType = 'gallery.item';
    public $defaultSortField = 'rank';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('gallery:default');

    public function initialize() {
        $this->setDefaultProperties(array(
            'album' => false,
        ));
        if (!$this->getProperty('album',false)) return false;
        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('galAlbumItem','AlbumItems',array(
            'galItem.id = AlbumItems.item',
            'AlbumItems.album' => $this->getProperty('album')
        ));
        $c->innerJoin('galAlbum','Album',array(
            'Album.id = AlbumItems.album',
        ));
        $c->leftJoin('galTag','Tags');
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('galItem','galItem'));
        $c->select(array(
            'AlbumItems.rank',
            'album' => 'Album.id',
            '(
                SELECT GROUP_CONCAT(Tags.tag) FROM '.$this->modx->getTableName('galTag').' AS Tags
                WHERE Tags.item = galItem.id
            ) AS tags'
        ));
        $c->groupBy('id');
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $itemArray = $object->toArray();
        $itemArray['album'] = $this->getProperty('album');
        $itemArray['filename'] = basename($object->get('filename'));
        $imagePath = $object->get('image_path');
        $size = @getimagesize($imagePath);
        if (is_array($size)) {
            $itemArray['image_width'] = $size[0];
            $itemArray['image_height'] = $size[1];
            $itemArray['image_type'] = $size[2];
        }
        $c = array();
        $c['h'] = $this->modx->getOption('gallery.backend_thumb_height',null,60);
        $c['w'] = $this->modx->getOption('gallery.backend_thumb_width',null,80);
        $c['zc'] = $this->modx->getOption('gallery.backend_thumb_zoomcrop',null,1);
        $c['far'] = $this->modx->getOption('gallery.backend_thumb_far',null,'C');
        $itemArray['thumbnail'] = $object->get('thumbnail',$c);
        $itemArray['image'] = $object->get('image');
        $itemArray['relativeImage'] = $object->get('relativeImage');
        $itemArray['absoluteImage'] = $object->get('absoluteImage');

        $itemArray['filesize'] = $object->get('filesize');

        $itemArray['menu'] = array();
        $itemArray['menu'][] = array(
            'text' => $this->modx->lexicon('gallery.item_update'),
            'handler' => 'this.updateItem',
        );
        $itemArray['menu'][] = '-';
        $itemArray['menu'][] = array(
            'text' => $this->modx->lexicon('gallery.item_delete'),
            'handler' => 'this.deleteItem',
        );

        return $itemArray;
    }
}
return 'GalleryItemGetListProcessor';