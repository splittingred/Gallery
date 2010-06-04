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
 * The main Gallery snippet.
 *
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';

/* setup default properties */
$album = $modx->getOption('album',$scriptProperties,false);
$plugin = $modx->getOption('plugin',$scriptProperties,'');
$tag = $modx->getOption('tag',$scriptProperties,'');
$limit = $modx->getOption('limit',$scriptProperties,0);
$start = $modx->getOption('start',$scriptProperties,0);
$sort = $modx->getOption('sort',$scriptProperties,'rank');
$sortAlias = $modx->getOption('sortAlias',$scriptProperties,'galItem');
if ($sort == 'rank') $sortAlias = 'AlbumItems';
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$showInactive = $modx->getOption('showInactive',$scriptProperties,false);
$linkToImage = $modx->getOption('linkToImage',$scriptProperties,false);
$imageGetParam = $modx->getOption('imageGetParam',$scriptProperties,'galItem');
$albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
$tagRequestVar = $modx->getOption('tagRequestVar',$scriptProperties,'galTag');
$itemCls = $modx->getOption('itemCls',$scriptProperties,'gal-item');

/* check for REQUEST vars if property set */
if ($modx->getOption('checkForRequestAlbumVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$albumRequestVar])) $album = $_REQUEST[$albumRequestVar];
}
if ($modx->getOption('checkForRequestTagVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$tagRequestVar])) $tag = $_REQUEST[$tagRequestVar];
}
if (empty($album) && empty($tag)) return '';

/* build query */
$c = $modx->newQuery('galItem');
$c->select(array('galItem.*'));
$c->select('
    (SELECT GROUP_CONCAT(`TagsJoin`.`tag`) FROM '.$modx->getTableName('galTag').' AS `TagsJoin`
     WHERE `TagsJoin`.`item` = `galItem`.`id`) AS `tags`
');
$c->innerJoin('galAlbumItem','AlbumItems');
$c->innerJoin('galAlbum','Album','`AlbumItems`.`album` = `Album`.`id`');

/* pull by album */
if (!empty($album)) {
    $albumField = is_numeric($album) ? 'id' : 'name';

    $albumWhere = $albumField == 'name' ? array('name' => $album) : $album;
    $album = $modx->getObject('galAlbum',$albumWhere);
    if (empty($album)) return '';
    $c->where(array(
        'Album.'.$albumField => $album->get($albumField),
    ));
    $galleryId = $album->get('id');
    $galleryName = $album->get('name');
    $galleryDescription = $album->get('description');
    unset($albumWhere,$albumField);
}
if (!empty($tag)) { /* pull by tag */
    $c->innerJoin('galTag','Tags');
    $c->where(array(
        'Tags.tag' => $tag,
    ));
    if (empty($album)) {
        $galleryId = 0;
        $galleryName = $tag;
        $galleryDescription = '';
    }
}
$c->where(array(
    'galItem.mediatype' => $modx->getOption('mediatype',$scriptProperties,'image'),
));
if (!$showInactive) {
    $c->where(array(
        'galItem.active' => true,
    ));
}

$c->sortby($sortAlias.'.'.$sort,$dir);
if (!empty($limit)) $c->limit($limit,$start);
$items = $modx->getCollection('galItem',$c);

/* load plugins */
if (!empty($plugin)) {
    if (($className = $modx->loadClass('gallery.plugins.'.$plugin,$gallery->config['modelPath'],true,true))) {
        $plugin = new $className($gallery,$scriptProperties);
        $plugin->load();
        $scriptProperties = $plugin->adjustSettings($scriptProperties);
    }
} else {
    if ($modx->getOption('useCss',$scriptProperties,true)) {
        $modx->regClientCSS($gallery->config['cssUrl'].'web.css');
    }
}

/* iterate */
$output = '';
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['cls'] = $itemCls;
    $itemArray['filename'] = basename($item->get('filename'));
    $itemArray['filesize'] = $item->get('filesize');
    $itemArray['thumbnail'] = $item->get('thumbnail',array(
        'w' => $modx->getOption('thumbWidth',$scriptProperties,100),
        'h' => $modx->getOption('thumbHeight',$scriptProperties,100),
        'zc' => 1,
    ));
    $itemArray['image'] = $item->get('thumbnail',array(
        'w' => $modx->getOption('imageWidth',$scriptProperties,500),
        'h' => $modx->getOption('imageHeight',$scriptProperties,500),
        'zc' => 0,
    ));
    if (!empty($album)) $itemArray['album'] = $album->get('id');
    if (!empty($tag)) $itemArray['tag'] = $tag;
    $itemArray['linkToImage'] = $linkToImage;
    $itemArray['imageGetParam'] = $imageGetParam;
    $itemArray['albumRequestVar'] = $albumRequestVar;
    $itemArray['tagRequestVar'] = $tagRequestVar;

    $output .= $gallery->getChunk($modx->getOption('thumbTpl',$scriptProperties,'galItemThumb'),$itemArray);
}

$containerTpl = $modx->getOption('containerTpl',$scriptProperties,false);
if (!empty($containerTpl)) {
    $ct = $gallery->getChunk($containerTpl,array(
        'thumbnails' => $output,
        'album_name' => $galleryName,
        'album_description' => $galleryDescription,
    ));
    if (!empty($ct)) $output = $ct;
}

$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
    $modx->toPlaceholders(array(
        $toPlaceholder => $output,
        $toPlaceholder.'.id' => $galleryId,
        $toPlaceholder.'.name' => $galleryName,
        $toPlaceholder.'.description' => $galleryDescription,
    ));
} else {
    $modx->toPlaceholders(array(
        'gallery.id' => $galleryId,
        'gallery.name' => $galleryName,
        'gallery.description' => $galleryDescription,
    ));
    return $output;
}
return '';