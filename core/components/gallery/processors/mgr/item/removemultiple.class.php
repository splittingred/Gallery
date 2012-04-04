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
 * Delete an item entirely
 *
 * @package gallery
 */
class GalleryItemRemoveMultipleProcessor extends modObjectProcessor {
    public $classKey = 'galItem';
    public $languageTopics = array('gallery:default');

    public function process() {
        $ids = $this->getProperty('ids');
        if (empty($ids)) return $this->failure($this->modx->lexicon('gallery.item_err_ns'));

        $separator = $this->getProperty('separator',',');
        $ids = explode($separator,$ids);

        $errors = array();
        foreach ($ids as $id) {
            /* @var galItem $item */
            $item = $this->modx->getObject('galItem',$id);
            if (empty($item)) {
                $errors[] = $this->modx->lexicon('gallery.item_err_nf').': '.$id;
                continue;
            }
            if (!$item->remove()) {
                $errors[] = $this->modx->lexicon('gallery.item_err_remove').': '.$id;
            }
        }

        if (!empty($errors)) return $this->failure(implode('<br />',$errors));

        return $this->success();
    }
}
return 'GalleryItemRemoveMultipleProcessor';