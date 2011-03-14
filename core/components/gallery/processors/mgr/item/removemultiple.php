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
 * Delete an item entirely
 *
 * @package gallery
 */
if (empty($scriptProperties['ids'])) return $modx->error->failure($modx->lexicon('gallery.item_err_ns'));

$separator = $modx->getOption('separator',$scriptProperties,',');
$ids = explode($separator,$scriptProperties['ids']);

$errors = array();
foreach ($ids as $id) {
    /* get item */
    $item = $modx->getObject('galItem',$id);
    if (empty($item)) {
        $errors[] = $modx->lexicon('gallery.item_err_nf').': '.$id;
        continue;
    }

    /* remove item */
    if (!$item->remove()) {
        $errors[] = $modx->lexicon('gallery.item_err_remove').': '.$id;
    }
}

if (!empty($errors)) return $modx->error->failure(implode('<br />',$errors));

return $modx->error->success();