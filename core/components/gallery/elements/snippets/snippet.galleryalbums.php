<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
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
$showInactive = $modx->getOption('showInactive',$scriptProperties,false);
$prominentOnly = $modx->getOption('prominentOnly',$scriptProperties,true);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
$sort = $modx->getOption('sort',$scriptProperties,'createdon');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$limit = $modx->getOption('limit',$scriptProperties,10);
$start = $modx->getOption('start',$scriptProperties,0);
$parent = $modx->getOption('parent',$scriptProperties,0);
$showAll = $modx->getOption('showAll',$scriptProperties,false);

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
$c->limit($limit,$start);
$albums = $modx->getCollection('galAlbum',$c);

/* iterate */
$output = '';
foreach ($albums as $album) {
    $albumArray = $album->toArray();
    $output .= $gallery->getChunk($rowTpl,$albumArray);
}

if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
return $output;