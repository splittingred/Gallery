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
 * Loads a list of Albums
 *
 * @var modX $modx
 * @var Gallery $gallery
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';

/* setup default properties */
$rowTpl = $modx->getOption('rowTpl',$scriptProperties,'galAlbumRowTpl');
$rowCls = $modx->getOption('rowCls',$scriptProperties,'');
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
$showAll = $modx->getOption('showAll',$scriptProperties,false);
$albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
$albumCoverSort = $modx->getOption('albumCoverSort',$scriptProperties,'rank');
$albumCoverSortDir = $modx->getOption('albumCoverSortDir',$scriptProperties,'ASC');
$showName = $modx->getOption('showName',$scriptProperties,true);

$totalProperties = $scriptProperties;
$totalProperties['limit'] = '0';
$totalProperties['start'] = '0';
$totalAlbums = $modx->call('galAlbum', 'getList', array(&$modx, $totalProperties));
$totalVar = $modx->getOption('totalVar', $scriptProperties, 'total');
$modx->setPlaceholder($totalVar, count($totalAlbums));

/* build query */
$albums = $modx->call('galAlbum','getList',array(&$modx,$scriptProperties));

/* handle sorting for album cover */
if ($albumCoverSort == 'rank') {
    $albumCoverSort = 'AlbumItems.rank';
}
if (in_array(strtolower($albumCoverSort),array('random','rand()','rand'))) {
    $albumCoverSort = 'RAND()';
    $albumCoverSortDir = '';
}

/* get thumb properties for album cover */
$thumbProperties = $modx->getOption('thumbProperties',$scriptProperties,'');
$thumbProperties = !empty($thumbProperties) ? $modx->fromJSON($thumbProperties) : array();
$thumbProperties = array_merge(array(
    'w' => (int)$modx->getOption('thumbWidth',$scriptProperties,100),
    'h' => (int)$modx->getOption('thumbHeight',$scriptProperties,100),
    'zc' => (string)$modx->getOption('thumbZoomCrop',$scriptProperties,1),
    'far' => (string)$modx->getOption('thumbFar',$scriptProperties,'C'),
    'q' => (int)$modx->getOption('thumbQuality',$scriptProperties,90),
),$thumbProperties);

/* iterate */
$output = array();
$idx = 0;
$filesUrl = $modx->call('galAlbum','getFilesUrl',array(&$modx));
$nav = array();
/** @var galAlbum $album */
foreach ($albums as $album) {
    $albumArray = $album->toArray();
    $classes = array($rowCls);

    if (!isset($nav['first'])) {
        $nav['first'] = $albumArray['id'];
    }
    if (!isset($nav['next']) && isset($nav['current'])) {
        $nav['next'] = $albumArray['id'];
    }
    if ($_GET[$albumRequestVar] == $albumArray['id']) {
        $nav['current'] = $albumArray['id'];
        $nav['curIdx'] = $idx + 1;
        $classes[] = 'current';
    }
    if (!isset($nav['current'])) {
        $nav['prev'] = $albumArray['id'];
    }
    $nav['last'] = $albumArray['id'];

    $albumArray['cls'] = implode(' ', $classes);
    $albumArray['idx'] = $idx;
    $albumArray['showName'] = $showName;
    $albumArray['albumRequestVar'] = $albumRequestVar;
    $coverItem = $album->getCoverItem($albumCoverSort,$albumCoverSortDir);
    if ($coverItem) {
        $albumArray['image'] = $coverItem->get('thumbnail',$thumbProperties);
        $albumArray['image_absolute'] = $filesUrl.$coverItem->get('filename');
        $albumArray['total'] = $coverItem->get('total');
    }

    $albumArray['cls'] = implode(' ', $classes);
    $albumArray['idx'] = $idx;
    $albumArray['showName'] = $showName;
    $albumArray['albumRequestVar'] = $albumRequestVar;
    $output[] = $gallery->getChunk($rowTpl,$albumArray);
    $idx++;
}
if (!isset($nav['current'])) {
    unset($nav['prev']);
}
$nav['count'] = $idx;

/* set output to placeholder or return */
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");
$output = implode($outputSeparator,$output);

/* if set, place in a container tpl */
$containerTpl = $modx->getOption('containerTpl',$scriptProperties,false);
if (!empty($containerTpl)) {
    $ct = $gallery->getChunk($containerTpl,array(
        'albums' => $output,
        'nav' => $nav,
        'albumRequestVar' => $albumRequestVar
    ));
    if (!empty($ct)) $output = $ct;
}

if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
return $output;
