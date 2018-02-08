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

/* check for REQUEST vars if property set */
$imageGetParam = $modx->getOption('imageGetParam',$scriptProperties,'galItem');
$albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
$tagRequestVar = $modx->getOption('tagRequestVar',$scriptProperties,'galTag');
if ($modx->getOption('checkForRequestAlbumVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$albumRequestVar])) $scriptProperties['album'] = $_REQUEST[$albumRequestVar];
}
if ($modx->getOption('checkForRequestTagVar',$scriptProperties,true)) {
    if (!empty($_REQUEST[$tagRequestVar])) $scriptProperties['tag'] = $_REQUEST[$tagRequestVar];
}
if (empty($scriptProperties['album']) && empty($scriptProperties['tag'])) return '';

$data = $modx->call('galItem','getList',array(&$modx,$scriptProperties));
$totalVar = $modx->getOption('totalVar', $scriptProperties, 'total');
$modx->setPlaceholder($totalVar,$data['total']);

/* load plugins */
$plugin = $modx->getOption('plugin',$scriptProperties,'');
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
$output = array();
$filesUrl = $modx->call('galAlbum','getFilesUrl',array(&$modx));
$filesPath = $modx->call('galAlbum','getFilesPath',array(&$modx));
$itemCls = $modx->getOption('itemCls',$scriptProperties,'gal-item');
$imageAttributes = $modx->getOption('imageAttributes',$scriptProperties,'');
$linkAttributes = $modx->getOption('linkAttributes',$scriptProperties,'');
$linkToImage = $modx->getOption('linkToImage',$scriptProperties,false);
$activeCls = $modx->getOption('activeCls',$scriptProperties,'gal-item-active');
$highlightItem = $modx->getOption($imageGetParam,$_REQUEST,false);
$defaultThumbTpl = $modx->getOption('thumbTpl',$scriptProperties,'galItemThumb');

/** @var galItem $item */

if (!is_array($data)) return '';

// prep for &thumbTpl_N
$keys = array_keys($scriptProperties);
$nthTpls = array();
foreach($keys as $key) {
    $keyBits = $gallery->explodeAndClean($key, '_');
    if (isset($keyBits[0]) && $keyBits[0] === 'thumbTpl') {
        if ($i = (int) $keyBits[1]) $nthTpls[$i] = $scriptProperties[$key];
    }
}
ksort($nthTpls);

foreach ($data['items'] as $item) {
    $itemArray = $item->toArray();
    $itemArray['idx'] = $idx;
    $itemArray['cls'] = $itemCls;
    if ($itemArray['id'] == $highlightItem) {
        $itemArray['cls'] .= ' '.$activeCls;
    }
    $itemArray['filename'] = basename($item->get('filename'));
    $itemArray['image_absolute'] = $item->get('base_url').$filesUrl.$item->get('filename');
    $itemArray['fileurl'] = $itemArray['image_absolute'];
    $itemArray['filepath'] = $filesPath.$item->get('filename');
    $itemArray['filesize'] = $item->get('filesize');
    $itemArray['thumbnail'] = $item->get('thumbnail',$thumbProperties);
    $itemArray['image'] = $item->get('thumbnail',$imageProperties);
    $itemArray['image_attributes'] = $imageAttributes;
    $itemArray['link_attributes'] = $linkAttributes;
    if (!empty($scriptProperties['album'])) $itemArray['album'] = $scriptProperties['album'];
    if (!empty($scriptProperties['tag'])) $itemArray['tag'] = $scriptProperties['tag'];
    $itemArray['linkToImage'] = $linkToImage;
    $itemArray['url'] = $item->get('url');
    $itemArray['imageGetParam'] = $imageGetParam;
    $itemArray['albumRequestVar'] = $albumRequestVar;
    $itemArray['tagRequestVar'] = $tagRequestVar;
    $itemArray['tag'] = '';
    if ($plugin) {
        $plugin->renderItem($itemArray);
    }

    $thumbTpl = $defaultThumbTpl;
    if (isset($nthTpls[$idx])) {
        $thumbTpl = $nthTpls[$idx];
    } else {
        foreach ($nthTpls as $int => $tpl) {
            if ( ($idx % $int) === 0 ) $thumbTpl = $tpl;
        }
    }

    $output[] = $gallery->getChunk($thumbTpl,$itemArray);

    $idx++;
}
$output = implode("\n",$output);

/* if set, place in a container tpl */
$containerTpl = $modx->getOption('containerTpl',$scriptProperties,false);
if (!empty($containerTpl)) {
    $ct = $gallery->getChunk($containerTpl,array(
        'thumbnails' => $output,
        'album_name' => $data['album']['name'],
        'album_description' => $data['album']['description'],
        'album_year' => isset($data['album']['year']) ? $data['album']['year'] : '',
        'albumRequestVar' => $albumRequestVar,
        'albumId' => $data['album']['id'],
    ));
    if (!empty($ct)) $output = $ct;
}

/* set to placeholders or output directly */
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
    $modx->toPlaceholders(array(
        $toPlaceholder => $output,
        $toPlaceholder.'.id' => $data['album']['id'],
        $toPlaceholder.'.name' => $data['album']['name'],
        $toPlaceholder.'.year' => isset($data['album']['year']) ? $data['album']['year'] : '',
        $toPlaceholder.'.description' => $data['album']['description'],
        $toPlaceholder.'.total' => $data['total'],
        $toPlaceholder.'.next' => $data['album']['id'] + 1,
        $toPlaceholder.'.prev' => $data['album']['id'] - 1,
    ));
} else {
    $placeholderPrefix = $modx->getOption('placeholderPrefix',$scriptProperties,'gallery.');
    $modx->toPlaceholders(array(
        $placeholderPrefix.'id' => $data['album']['id'],
        $placeholderPrefix.'name' => $data['album']['name'],
        $placeholderPrefix.'year' => isset($data['album']['year']) ? $data['album']['year'] : '',
        $placeholderPrefix.'description' => $data['album']['description'],
        $placeholderPrefix.'total' => $data['total'],
        $placeholderPrefix.'next' => $data['album']['id'] + 1,
        $placeholderPrefix.'prev' => $data['album']['id'] - 1,
    ));
    return $output;
}
return '';
