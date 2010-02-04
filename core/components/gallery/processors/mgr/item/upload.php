<?php
/**
 * Upload an item into an album
 *
 * @package gallery
 */

/* validate form */
$album = $modx->getOption('album',$_POST,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));

//if (empty($_POST['name'])) $modx->error->addField('name',$modx->lexicon('gallery.item_err_ns_name'));
if (empty($_FILES['file'])) $modx->error->addField('name',$modx->lexicon('gallery.item_err_ns_file'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create item */
$_POST['active'] = !empty($_POST['active']) ? 1 : 0;
$item = $modx->newObject('galItem');
$item->fromArray($_POST);

if (empty($_FILES['file'])) return $modx->error->failure($modx->lexicon('gallery.item_err_file_nf'));
if (!$item->upload($_FILES['file'])) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_upload'));
}

if (!$item->save()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
}

/* associate with album */
$albumItem = $modx->newObject('galAlbumItem');
$albumItem->set('album',$_POST['album']);
$albumItem->set('item',$item->get('id'));
$albumItem->save();

/* output to browser */
return $modx->error->success('',$item);