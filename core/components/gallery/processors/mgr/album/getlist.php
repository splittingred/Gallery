<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

$c = $modx->newQuery('galAlbum');
$count = $modx->getCount('galAlbum',$c);
$c->select('
    `galAlbum`.*,
    (SELECT COUNT(*) FROM '.$modx->getTableName('galAlbumItem').' AS `AlbumItem`
     WHERE `AlbumItem`.`album` = `galAlbum`.`id`) AS `items`
');

if ($isLimit) $c->limit($limit,$start);
$albums = $modx->getCollection('galAlbum',$c);

$list = array();
foreach ($albums as $album) {
    $albumArray = $album->toArray();

    $albumArray['menu'] = array();
    $albumArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.album_update'),
        'handler' => 'this.updateAlbum',
    );
    $albumArray['menu'][] = '-';
    $albumArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.album_remove'),
        'handler' => 'this.removeAlbum',
    );

    $list[]= $albumArray;
}
return $this->outputArray($list,$count);