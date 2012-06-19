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
 * @package gallery
 */
class galAlbumContext extends xPDOSimpleObject {


    public function save($cacheFlag=null) {
        $saved = parent::save($cacheFlag);
        if ($saved) {
            if ($this->xpdo->getCacheManager()) {
                $this->xpdo->cacheManager->delete('gallery/album/'.$this->get('album_id'));
                $this->xpdo->cacheManager->delete('gallery/item/list/');
            }
        }
        return $saved;
    }
}
