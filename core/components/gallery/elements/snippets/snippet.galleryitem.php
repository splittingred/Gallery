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
 * Display a single Gallery Item
 *
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';

/* get ID of item */
$id = (int)$modx->getOption('id',$scriptProperties,false);
if ($modx->getOption('checkForRequestVar',$scriptProperties,true)) {
    $getParam = $modx->getOption('getParam',$scriptProperties,'galItem');
    if (!empty($_REQUEST[$getParam])) { $id = (int)$_REQUEST[$getParam]; }
}
if (empty($id)) return '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'galItem');
$toPlaceholders = $modx->getOption('toPlaceholders',$scriptProperties,true);
$toPlaceholdersPrefix = $modx->getOption('toPlaceholdersPrefix',$scriptProperties,'galitem');
$albumTpl = $modx->getOption('albumTpl',$scriptProperties,'galItemAlbum');
$albumSeparator = $modx->getOption('albumSeparator',$scriptProperties,',&nbsp;');
$albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
$tagTpl = $modx->getOption('tagTpl',$scriptProperties,'galItemTag');
$tagSeparator = $modx->getOption('tagSeparator',$scriptProperties,',&nbsp;');
$tagSortDir = $modx->getOption('tagSortDir',$scriptProperties,'DESC');
$tagRequestVar = $modx->getOption('tagRequestVar',$scriptProperties,'galTag');
/* get item */
$c = $modx->newQuery('galItem');
$c->select($modx->getSelectColumns('galItem','galItem'));
$c->where(array(
    'id' => $id,
));
$item = $modx->getObject('galItem',$c);
if (empty($item)) return '';

/* setup placeholders */
$itemArray = $item->toArray();
$itemArray['filename'] = basename($item->get('filename'));
$itemArray['filesize'] = $item->get('filesize');

/* get image+thumbnail */
$thumbProperties = $modx->getOption('thumbProperties',$scriptProperties,'');
$thumbProperties = !empty($thumbProperties) ? $modx->fromJSON($thumbProperties) : array();
$thumbProperties = array_merge(array(
    'w' => (int)$modx->getOption('thumbWidth',$scriptProperties,100),
    'h' => (int)$modx->getOption('thumbHeight',$scriptProperties,100),
    'zc' => (boolean)$modx->getOption('thumbZoomCrop',$scriptProperties,0),
    'far' => (string)$modx->getOption('thumbFar',$scriptProperties,'C'),
    'q' => (int)$modx->getOption('thumbQuality',$scriptProperties,90),
),$thumbProperties);
$itemArray['thumbnail'] = $item->get('thumbnail',$thumbProperties);

$imageProperties = $modx->getOption('imageProperties',$scriptProperties,'');
$imageProperties = !empty($imageProperties) ? $modx->fromJSON($imageProperties) : array();
$imageProperties = array_merge(array(
    'w' => (int)$modx->getOption('imageWidth',$scriptProperties,500),
    'h' => (int)$modx->getOption('imageHeight',$scriptProperties,500),
    'zc' => (boolean)$modx->getOption('imageZoomCrop',$scriptProperties,0),
    'far' => (string)$modx->getOption('imageFar',$scriptProperties,false),
    'q' => (int)$modx->getOption('imageQuality',$scriptProperties,90),
),$imageProperties);
$itemArray['image'] = $item->get('thumbnail',$imageProperties);

/* get albums */
$c = $modx->newQuery('galAlbum');
$c->innerJoin('galAlbumItem','AlbumItems',$modx->getSelectColumns('galAlbumItem','AlbumItems','',array('album')).' = '.$modx->getSelectColumns('galAlbum','galAlbum','',array('id'))
    .' AND '.$modx->getSelectColumns('galAlbumItem','AlbumItems','',array('item')).' = '.$item->get('id'));
$c->sortby('AlbumItems.rank','ASC');
$albums = $modx->getCollection('galAlbum',$c);
$itemArray['albums'] = array();
$i = 0;
foreach ($albums as $album) {
    $albumArray = $album->toArray('',true,true);
    $albumArray['idx'] = $i;
    $albumArray['albumRequestVar'] = $albumRequestVar;
    $itemArray['albums'][] = $gallery->getChunk($albumTpl,$albumArray);
    $i++;
}
$itemArray['albums'] = implode($albumSeparator,$itemArray['albums']);

/* get tags */
$c = $modx->newQuery('galTag');
$c->where(array(
    'item' => $item->get('id'),
));
$c->sortby('tag',$tagSortDir);
$tags = $modx->getCollection('galTag',$c);
$i = 0;
$itemArray['tags'] = array();
foreach ($tags as $tag) {
    $tagArray = $tag->toArray();
    $tagArray['idx'] = $i;
    $tagArray['tagRequestVar'] = $tagRequestVar;
    $itemArray['tags'][] = $gallery->getChunk($tagTpl,$tagArray);
    $i++;
}
$itemArray['tags'] = implode($tagSeparator,$itemArray['tags']);

/* if outputting to placeholders, use this, otherwise, use tpl */
if ($toPlaceholders) {
    $modx->toPlaceholders($itemArray,$toPlaceholdersPrefix);
    return '';
}

if (empty($tpl)) return '';
$output .= $gallery->getChunk($tpl,$itemArray);
return $output;