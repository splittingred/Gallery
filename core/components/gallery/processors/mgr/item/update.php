<?php
/**
 * Delete an item entirely
 *
 * @package gallery
 */

/* get item */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('gallery.item_err_ns'));
$item = $modx->getObject('galItem',$_POST['id']);
if (empty($item)) return $modx->error->failure($modx->lexicon('gallery.item_err_nf'));


$item->fromArray($_POST);

/* remove item */
if (!$item->save()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
}

if (isset($_POST['tags'])) {
    $tagNames = explode(',',$_POST['tags']);

    $oldTags = $item->getMany('Tags');
    foreach ($oldTags as $oldTag) $oldTag->remove();

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