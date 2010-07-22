<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
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
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,24);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$album = $modx->getOption('album',$_REQUEST,false);

if (empty($album)) return $this->outputArray(array(),0);

$c = $modx->newQuery('galItem');
$c->innerJoin('galAlbumItem','AlbumItems','`galItem`.`id` = `AlbumItems`.`item` AND `AlbumItems`.`album` = '.$album);
$c->innerJoin('galAlbum','Album','`Album`.`id` = `AlbumItems`.`album`');
$count = $modx->getCount('galItem',$c);

$c->leftJoin('galTag','Tags');
$c->select(array(
    'galItem.*',
    'AlbumItems.rank',
));
$c->select('`Album`.`id` AS `album`');
$c->select('(
    SELECT GROUP_CONCAT(`Tags`.`tag`) FROM '.$modx->getTableName('galTag').' AS `Tags`
    WHERE `Tags`.`item` = `galItem`.`id`
) AS `tags`');

if ($isLimit) $c->limit($limit,$start);
$c->sortby('rank','ASC');
$items = $modx->getCollection('galItem',$c);

$list = array();
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['filename'] = basename($item->get('filename'));
    $imagePath = $item->get('image_path');
    $size = @getimagesize($imagePath);
    if (is_array($size)) {
        $itemArray['image_width'] = $size[0];
        $itemArray['image_height'] = $size[1];
        $itemArray['image_type'] = $size[2];
    }
    $c = array();
    $c['h'] = $modx->getOption('gallery.backend_thumb_height',null,60);
    $c['w'] = $modx->getOption('gallery.backend_thumb_width',null,80);
    $c['zc'] = $modx->getOption('gallery.backend_thumb_zoomcrop',null,1);
    $c['far'] = $modx->getOption('gallery.backend_thumb_far',null,'C');
    $itemArray['thumbnail'] = $item->get('thumbnail',$c);
    $itemArray['image'] = $item->get('image');
    $itemArray['relativeImage'] = $item->get('relativeImage');
    $itemArray['absoluteImage'] = $item->get('absoluteImage');

    $itemArray['filesize'] = $item->get('filesize');

    $itemArray['menu'] = array();
    $itemArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.item_update'),
        'handler' => 'this.updateItem',
    );
    $itemArray['menu'][] = '-';
    $itemArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.item_delete'),
        'handler' => 'this.deleteItem',
    );

    $list[]= $itemArray;
}
return $this->outputArray($list,$count);