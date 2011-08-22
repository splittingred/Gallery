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
 * Upload an item into an album
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * @package gallery
 */

/* validate form */
$album = $modx->getOption('album',$scriptProperties,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));

if (empty($_FILES['file'])) $modx->error->addField('file',$modx->lexicon('gallery.item_err_ns_file'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}


/* create item */
$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
/** @var galItem $item */
$item = $modx->newObject('galItem');
$item->fromArray($scriptProperties);
$item->set('createdby',$modx->user->get('id'));

if (empty($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_ns_file'));
}

if (!$item->save()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
}

if (!$item->upload($_FILES['file'],$scriptProperties['album'])) {
    $item->remove();
    return $modx->error->failure($modx->lexicon('gallery.item_err_upload'));
}
$item->save();

/* get count of items in album */
$total = $modx->getCount('galAlbumItem',array('album' => $scriptProperties['album']));

/* associate with album */
/** @var galAlbumItem $albumItem */
$albumItem = $modx->newObject('galAlbumItem');
$albumItem->set('album',$scriptProperties['album']);
$albumItem->set('item',$item->get('id'));
$albumItem->set('rank',$total);
$albumItem->save();

/* save tags */
if (isset($scriptProperties['tags'])) {
    $tagNames = explode(',',$scriptProperties['tags']);
    foreach ($tagNames as $tagName) {
        $tagName = trim($tagName);
        if (empty($tagName)) continue;

        /** @var galTag $tag */
        $tag = $modx->newObject('galTag');
        $tag->set('item',$item->get('id'));
        $tag->set('tag',$tagName);
        $tag->save();
    }
}


/* output to browser */
$itemArray = $item->toArray();
unset($itemArray['description']);
return $modx->error->success('',$itemArray);