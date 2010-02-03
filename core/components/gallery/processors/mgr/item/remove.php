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

/* remove item */
if (!$item->remove()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_remove'));
}

/* output to browser */
return $modx->error->success('',$item);