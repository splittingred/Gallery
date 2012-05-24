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
 * Update an item
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package gallery
 */
class GalleryItemUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'galItem';
    public $objectType = 'gallery.item';
    public $languageTopics = array('gallery:default');

    public function beforeSet() {
        $this->setCheckbox('active',true);
        return parent::beforeSet();
    }

    public function afterSave() {
        $tags = $this->getProperty('tags',null);
        if ($tags !== null) {
            $tagNames = explode(',',$tags);

            $oldTags = $this->object->getMany('Tags');
            /** @var galTag $oldTag */
            foreach ($oldTags as $oldTag) $oldTag->remove();

            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                /** @var galTag $tag */
                $tag = $this->modx->newObject('galTag');
                $tag->set('item',$this->object->get('id'));
                $tag->set('tag',$tagName);
                $tag->save();
            }
        }
        return parent::afterSave();
    }
    public function cleanup() {
        $objectArray = $this->object->toArray('',true);
        unset($objectArray['description']);
        return $this->success('',$objectArray);
    }
}
return 'GalleryItemUpdateProcessor';