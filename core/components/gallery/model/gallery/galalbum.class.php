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
class galAlbum extends xPDOSimpleObject {

    /**
     * Override set to trim string fields
     *
     * {@inheritDoc}
     */
    public function set($k, $v= null, $vType= '') {
        switch ($k) {
            case 'name':
            case 'description':
                if (is_string($v)) {
                    $v = trim($v);
                }
                break;
        }
        return parent::set($k,$v,$vType);
    }

    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('createdon')) {
            $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }

    public function remove(array $ancestors = array()) {
        $c = $this->xpdo->newQuery('galItem');
        $c->innerJoin('galAlbumItem','AlbumItems');
        $c->where(array(
            'AlbumItems.album' => $this->get('id'),
        ));
        $items = $this->xpdo->getCollection('galItem',$c);

        $removed = parent::remove($ancestors);

        if ($removed) {
            /** @var galAlbumItem $item */
            foreach ($items as $item) {
                $count = $this->xpdo->getCount('galAlbumItem',array(
                    'item' => $item->get('id'),
                    'album:!=' => $this->get('id'),
                ));
                if ($count <= 0) {
                    $item->remove();
                }
            }
        }
        return $removed;
    }

    public function getPath($absolute = true) {
        $path = $this->get('id').'/';
        if ($absolute) {
            $path = $this->xpdo->call('galAlbum','getFilesPath',array(&$this->xpdo)).$path;
        }
        return $path;
    }

    public static function getFilesPath(xPDO &$modx) {
        $path = $modx->getOption('gallery.files_path',null,$modx->getOption('base_path',null,MODX_BASE_PATH).'assets/gallery/');
        $path = str_replace(array(
            '[[++assets_path]]',
            '{assets_path}',
            '[[++base_path]]',
            '{base_path}',
            '[[++core_path]]',
            '{core_path}',
        ),array(
            $modx->getOption('assets_path',null,MODX_BASE_PATH.'assets/'),
            $modx->getOption('assets_path',null,MODX_BASE_PATH.'assets/'),
            $modx->getOption('base_path',null,MODX_BASE_PATH),
            $modx->getOption('base_path',null,MODX_BASE_PATH),
            $modx->getOption('core_path',null,MODX_CORE_PATH),
            $modx->getOption('core_path',null,MODX_CORE_PATH),
        ),$path);
        return $path;
    }

    public static function getFilesUrl(xPDO &$modx) {
        $path = $modx->getOption('gallery.files_url',null,$modx->getOption('base_url',null,MODX_BASE_URL).'assets/gallery/');
        $path = str_replace(array(
            '[[++assets_url]]',
            '{assets_url}',
            '[[++base_url]]',
            '{base_url}',
        ),array(
            $modx->getOption('assets_url',null,MODX_BASE_URL.'assets/'),
            $modx->getOption('assets_url',null,MODX_BASE_URL.'assets/'),
            $modx->getOption('base_url',null,MODX_BASE_URL),
            $modx->getOption('base_url',null,MODX_BASE_URL),
        ),$path);
        return $path;
    }

    /**
     * Reorder an album to a new rank
     * @param int $newRank
     * @return boolean
     */
    public function reorder($newRank) {
        $oldRank = $this->get('rank');

        $this->set('rank',$newRank);

        $movingDown = $newRank > $oldRank;
        if ($movingDown) {
            $sql = 'UPDATE '.$this->xpdo->getTableName('galAlbum').' SET rank = rank - 1 WHERE rank >= '.$oldRank.' AND rank <= '.$newRank.' AND parent = '.$this->get('parent');
            $this->xpdo->exec($sql);
        } else {
            $sql = 'UPDATE '.$this->xpdo->getTableName('galAlbum').' SET rank = rank + 1 WHERE rank > '.$newRank.' AND rank <= '.$oldRank.' AND parent = '.$this->get('parent');
            $this->xpdo->exec($sql);
        }

        return $this->save();
    }

    /**
     * Move an album to a new parent
     * @param int $newParent
     * @return boolean
     */
    public function move($newParent) {
        $oldParent = $this->get('parent');
        $oldRank = $this->get('rank');

        $this->set('parent',$newParent);
        /* set the new rank as the last album in the parent */
        $this->set('rank',$this->xpdo->getCount('galAlbum',array('parent' => $newParent)));

        $moved = $this->save();

        if ($moved) {
            /* Recalculate ranks of old parent */
            $sql = 'UPDATE '.$this->xpdo->getTableName('galAlbum').' SET rank = rank - 1 WHERE rank >= '.$oldRank.' AND parent = '.$oldParent;
            $this->xpdo->exec($sql);
        }
        return $moved;
    }

    /**
     * Check to see if the storage path for the album is writable
     * @return boolean
     */
    public function isPathWritable() {
        $path = $this->getPath();
        return is_readable($path) && is_writable($path);
    }

    /**
     * Ensure the storage path for this album exists
     * @return boolean
     */
    public function ensurePathExists() {
        $exists = true;
        $path = $this->getPath();
        if (!file_exists($path) || !is_dir($path)) {
            $cacheManager = $this->xpdo->getCacheManager();
            if (!$cacheManager->writeTree($path)) {
                $exists = false;
            }
        }
        return $exists;
    }

    public function uploadItem($filePath,$name) {
        $fileName = false;

        $albumDir = $this->getPath(false);
        $targetDir = $this->getPath();

        /* if directory doesnt exist, create it */
        if (!$this->ensurePathExists()) {
           $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$targetDir);
           return $fileName;
        }
        if (!$this->isPathWritable()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$targetDir);
            return $fileName;
        }

        /* upload the file */
        $extension = pathinfo($name,PATHINFO_EXTENSION);
        $shortName = $this->get('id').'.'.$extension;
        $relativePath = $albumDir.$shortName;
        $absolutePath = $targetDir.$shortName;

        if (@file_exists($absolutePath)) {
            @unlink($absolutePath);
        }
        if (!@move_uploaded_file($filePath,$absolutePath)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to upload the file: '.$filePath.' to '.$absolutePath);
        } else {
            $fileName = str_replace(' ','',$relativePath);
        }
        return $fileName;
    }
}