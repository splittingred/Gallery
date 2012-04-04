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
 * Get a list of Albums
 *
 * @package gallery
 * @subpackage processors
 */
class GalleryAlbumGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'galAlbum';
    public $objectType = 'gallery.album';
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('gallery:default');

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $subc = $this->modx->newQuery('galAlbumItem');
        $subc->where(array(
            'AlbumItem.album = galAlbum.id',
        ));
        $subc->query['columns'] = array();
        $subc->select(array ("COUNT(DISTINCT *)"));
        $subc->construct();
        $sql = $subc->toSQL();

        $c->select($this->modx->getSelectColumns('galAlbum','galAlbum'));
        $c->select(array(
            '('.$sql.') AS items',
        ));
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['menu'] = array();
        $objectArray['menu'][] = array(
            'text' => $this->modx->lexicon('gallery.album_update'),
            'handler' => 'this.updateAlbum',
        );
        $objectArray['menu'][] = '-';
        $objectArray['menu'][] = array(
            'text' => $this->modx->lexicon('gallery.album_remove'),
            'handler' => 'this.removeAlbum',
        );
        return $objectArray;
    }
}
return 'GalleryAlbumGetListProcessor';