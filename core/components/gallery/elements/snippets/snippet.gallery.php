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
 * The main Gallery snippet.
 *
 * @var modX $modx
 * @var Gallery $gallery
 * 
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';
$modx->lexicon->load('gallery:web');

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
$activeCls = $modx->getOption('activeCls',$scriptProperties,'gal-item-active');
$highlightItem = $modx->getOption($imageGetParam,$_REQUEST,false);

/* check for REQUEST vars if property set */
if ($modx->getOption('checkForRequestAlbumVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$albumRequestVar])) $album = $_REQUEST[$albumRequestVar];
}
if ($modx->getOption('checkForRequestTagVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$tagRequestVar])) $tag = $_REQUEST[$tagRequestVar];
}
if (empty($album) && empty($tag)) return '';

/* build query */
$tagc = $modx->newQuery('galTag');
$tagc->setClassAlias('TagsJoin');
$tagc->select('GROUP_CONCAT('.$modx->getSelectColumns('galTag','TagsJoin','',array('tag')).')');
$tagc->where($modx->getSelectColumns('galTag','TagsJoin','',array('item')).' = '.$modx->getSelectColumns('galItem','galItem','',array('id')));
$tagc->prepare();
$tagSql = $tagc->toSql();

$c = $modx->newQuery('galItem');
$c->innerJoin('galAlbumItem','AlbumItems');
$c->innerJoin('galAlbum','Album',$modx->getSelectColumns('galAlbumItem','AlbumItems','',array('album')).' = '.$modx->getSelectColumns('galAlbum','Album','',array('id')));

/* pull by album */
if (!empty($album)) {
    $albumField = is_numeric($album) ? 'id' : 'name';

    $albumWhere = $albumField == 'name' ? array('name' => $album) : $album;
    /** @var galAlbum $album */
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

$count = $modx->getCount('galItem',$c);
$c->select(array('galItem.*'));
$c->select(array(
    '('.$tagSql.') AS `tags`'
));
if (strcasecmp($sort,'rand')==0) {
    $c->sortby('RAND()',$dir);
} else {
    $c->sortby($sortAlias.'.'.$sort,$dir);
}
if (!empty($limit)) $c->limit($limit,$start);
$items = $modx->getCollection('galItem',$c);

/* load plugins */
if (!empty($plugin)) {
    $pluginPath = $modx->getOption('pluginPath',$scriptProperties,'');
    if (empty($pluginPath)) {
        $pluginPath = $gallery->config['modelPath'].'gallery/plugins/';
    }
    /** @var GalleryPlugin $plugin */
    if (($className = $modx->loadClass($plugin,$pluginPath,true,true))) {
        $plugin = new $className($gallery,$scriptProperties);
        $plugin->load();
        $scriptProperties = $plugin->adjustSettings($scriptProperties);
    } else {
        return $modx->lexicon('gallery.plugin_err_load',array('name' => $plugin,'path' => $pluginPath));
    }
} else {
    if ($modx->getOption('useCss',$scriptProperties,true)) {
        $modx->regClientCSS($gallery->config['cssUrl'].'web.css');
    }
}

/* iterate */
$output = '';

$imageProperties = $modx->getOption('imageProperties',$scriptProperties,'');
$imageProperties = !empty($imageProperties) ? $modx->fromJSON($imageProperties) : array();
$imageProperties = array_merge(array(
    'w' => (int)$modx->getOption('imageWidth',$scriptProperties,500),
    'h' => (int)$modx->getOption('imageHeight',$scriptProperties,500),
    'zc' => (boolean)$modx->getOption('imageZoomCrop',$scriptProperties,0),
    'far' => (string)$modx->getOption('imageFar',$scriptProperties,false),
    'q' => (int)$modx->getOption('imageQuality',$scriptProperties,90),
),$imageProperties);

$thumbProperties = $modx->getOption('thumbProperties',$scriptProperties,'');
$thumbProperties = !empty($thumbProperties) ? $modx->fromJSON($thumbProperties) : array();
$thumbProperties = array_merge(array(
    'w' => (int)$modx->getOption('thumbWidth',$scriptProperties,100),
    'h' => (int)$modx->getOption('thumbHeight',$scriptProperties,100),
    'zc' => (boolean)$modx->getOption('thumbZoomCrop',$scriptProperties,1),
    'far' => (string)$modx->getOption('thumbFar',$scriptProperties,'C'),
    'q' => (int)$modx->getOption('thumbQuality',$scriptProperties,90),
),$thumbProperties);

$idx = 0;
$filesUrl = $modx->call('galAlbum','getFilesUrl',array(&$modx));
$filesPath = $modx->call('galAlbum','getFilesPath',array(&$modx));
/** @var galItem $item */
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['idx'] = $idx;
    $itemArray['cls'] = $itemCls;
    if ($itemArray['id'] == $highlightItem) {
        $itemArray['cls'] .= ' '.$activeCls;
    }
    $itemArray['filename'] = basename($item->get('filename'));
    $itemArray['image_absolute'] = $filesUrl.$item->get('filename');
    $itemArray['fileurl'] = $itemArray['image_absolute'];
    $itemArray['filepath'] = $filesPath.$item->get('filename');
    $itemArray['filesize'] = $item->get('filesize');
    $itemArray['thumbnail'] = $item->get('thumbnail',$thumbProperties);
    $itemArray['image'] = $item->get('thumbnail',$imageProperties);
    if (!empty($album)) $itemArray['album'] = $album->get('id');
    if (!empty($tag)) $itemArray['tag'] = $tag;
    $itemArray['linkToImage'] = $linkToImage;
    $itemArray['imageGetParam'] = $imageGetParam;
    $itemArray['albumRequestVar'] = $albumRequestVar;
    $itemArray['tagRequestVar'] = $tagRequestVar;
    $itemArray['tag'] = '';

    $output .= $gallery->getChunk($modx->getOption('thumbTpl',$scriptProperties,'galItemThumb'),$itemArray);
    $idx++;
}

/* if set, place in a container tpl */
$containerTpl = $modx->getOption('containerTpl',$scriptProperties,false);
if (!empty($containerTpl)) {
    $ct = $gallery->getChunk($containerTpl,array(
        'thumbnails' => $output,
        'album_name' => $galleryName,
        'album_description' => $galleryDescription,
        'albumRequestVar' => $albumRequestVar,
        'albumId' => $galleryId,
    ));
    if (!empty($ct)) $output = $ct;
}

/* set to placeholders or output directly */
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
    $modx->toPlaceholders(array(
        $toPlaceholder => $output,
        $toPlaceholder.'.id' => $galleryId,
        $toPlaceholder.'.name' => $galleryName,
        $toPlaceholder.'.description' => $galleryDescription,
        $toPlaceholder.'.total' => $count,
    ));
} else {
    $placeholderPrefix = $modx->getOption('placeholderPrefix',$scriptProperties,'gallery.');
    $modx->toPlaceholders(array(
        $placeholderPrefix.'id' => $galleryId,
        $placeholderPrefix.'name' => $galleryName,
        $placeholderPrefix.'description' => $galleryDescription,
        $placeholderPrefix.'total' => $count,
    ));
    return $output;
}
return '';