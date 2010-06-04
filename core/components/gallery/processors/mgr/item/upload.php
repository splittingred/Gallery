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
 * Upload an item into an album
 *
 * @package gallery
 */

/* validate form */
$album = $modx->getOption('album',$_POST,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));

if (empty($_FILES['file'])) $modx->error->addField('file',$modx->lexicon('gallery.item_err_ns_file'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}


/* create item */
$_POST['active'] = !empty($_POST['active']) ? 1 : 0;
$item = $modx->newObject('galItem');
$item->fromArray($_POST);
$item->set('createdby',$modx->user->get('id'));

if (empty($_FILES['file'])) return $modx->error->failure($modx->lexicon('gallery.item_err_ns_file'));
if (!$item->upload($_FILES['file'])) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_upload'));
}

if (!$item->save()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
}

/* get count of items in album */
$total = $modx->getCount('galAlbumItem',array('album' => $_POST['album']));

/* associate with album */
$albumItem = $modx->newObject('galAlbumItem');
$albumItem->set('album',$_POST['album']);
$albumItem->set('item',$item->get('id'));
$albumItem->set('rank',$total);
$albumItem->save();

/* save tags */
if (isset($_POST['tags'])) {
    $tagNames = explode(',',$_POST['tags']);
    foreach ($tagNames as $tagName) {
        $tagName = trim($tagName);
        if (empty($tagName)) continue;

        $tag = $modx->newObject('galTag');
        $tag->set('item',$item->get('id'));
        $tag->set('tag',$tagName);
        $tag->save();
    }
}


/* output to browser */
return $modx->error->success('',$item);