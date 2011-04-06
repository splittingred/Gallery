<?php
/**
 * Gallery
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';

/* setup default properties */
$rowTpl = $modx->getOption('rowTpl',$scriptProperties,'galAlbumRowTpl');
$rowCls = $modx->getOption('rowCls',$scriptProperties,'');
$showInactive = $modx->getOption('showInactive',$scriptProperties,false);
$prominentOnly = $modx->getOption('prominentOnly',$scriptProperties,true);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
$sort = $modx->getOption('sort',$scriptProperties,'rank');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$limit = $modx->getOption('limit',$scriptProperties,10);
$start = $modx->getOption('start',$scriptProperties,0);
$parent = $modx->getOption('parent',$scriptProperties,0);
$showAll = $modx->getOption('showAll',$scriptProperties,false);
$albumRequestVar = $modx->getOption('albumRequestVar',$scriptProperties,'galAlbum');
$albumCoverSort = $modx->getOption('albumCoverSort',$scriptProperties,'rank');
$albumCoverSortDir = $modx->getOption('albumCoverSortDir',$scriptProperties,'ASC');
$showName = $modx->getOption('showName',$scriptProperties,true);

/* add random sorting for albums */
if (in_array(strtolower($sort),array('random','rand()','rand'))) {
    $sort = 'RAND()';
    $dir = '';
}

/* handle sorting for album cover */
if ($albumCoverSort == 'rank') {
    $albumCoverSort = 'AlbumItems.rank';
}
if (in_array(strtolower($albumCoverSort),array('random','rand()','rand'))) {
    $albumCoverSort = 'RAND()';
    $albumCoverSortDir = '';
}

/* build query */
$c = $modx->newQuery('galAlbum');
if (!$showInactive) {
    $c->where(array(
        'active' => true,
    ));
}
if ($prominentOnly) {
    $c->where(array(
        'prominent' => true,
    ));
}
if (empty($showAll)) {
    $c->where(array(
        'parent' => $parent,
    ));
}
$c->sortby($sort,$dir);
if ($limit > 0) { $c->limit($limit,$start); }
$albums = $modx->getCollection('galAlbum',$c);

/* get thumb properties for album cover */
$thumbProperties = $modx->getOption('thumbProperties',$scriptProperties,'');
$thumbProperties = !empty($thumbProperties) ? $modx->fromJSON($thumbProperties) : array();
$thumbProperties = array_merge(array(
    'w' => (int)$modx->getOption('thumbWidth',$scriptProperties,100),
    'h' => (int)$modx->getOption('thumbHeight',$scriptProperties,100),
    'zc' => (boolean)$modx->getOption('thumbZoomCrop',$scriptProperties,1),
    'far' => (string)$modx->getOption('thumbFar',$scriptProperties,'C'),
    'q' => (int)$modx->getOption('thumbQuality',$scriptProperties,90),
),$thumbProperties);

/* iterate */
$output = array();
$idx = 0;
foreach ($albums as $album) {
    $albumArray = $album->toArray();
    $c = $modx->newQuery('galItem');
    $c->innerJoin('galAlbumItem','AlbumItems');
    $c->where(array(
        'AlbumItems.album' => $album->get('id'),
    ));
    $c->sortby($albumCoverSort,$albumCoverSortDir);
    $c->limit(1);
    $coverItem = $modx->getObject('galItem',$c);
    
    if ($coverItem) {
        $albumArray['image'] = $coverItem->get('thumbnail',$thumbProperties);
    }

    $albumArray['cls'] = $rowCls;
    $albumArray['idx'] = $idx;
    $albumArray['showName'] = $showName;
    $albumArray['albumRequestVar'] = $albumRequestVar;
    $output[] = $gallery->getChunk($rowTpl,$albumArray);
    $idx++;
}

/* set output to placeholder or return */
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");
$output = implode($outputSeparator,$output);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
return $output;