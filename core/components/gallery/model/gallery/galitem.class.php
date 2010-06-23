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
 * @package gallery
 */
class galItem extends xPDOSimpleObject {

    public function get($k, $format = null, $formatTemplate= null) {
        switch ($k) {
            case 'thumbnail':
                $value = $this->getPhpThumbUrl();
                if (empty($format)) $format = array();
                $format['src']= MODX_URL_SCHEME.MODX_HTTP_HOST.$this->xpdo->getOption('gallery.files_url').$this->get('filename');
                $url = $value.'?'.http_build_query($format);
                $value = str_replace('&','&amp;',$url);
                break;
            case 'image':
                $value = $this->getPhpThumbUrl().'?src='.MODX_URL_SCHEME.MODX_HTTP_HOST.$this->xpdo->getOption('gallery.files_url').$this->get('filename');
                break;
            case 'filesize':
                $filename = $this->xpdo->getOption('gallery.files_path').$this->get('filename');
                $value = @filesize($filename);
                $value = $this->formatFileSize($value);
                break;
            case 'image_path':
                $value = $this->xpdo->getOption('gallery.files_path').$this->get('filename');
                break;
            default:
                $value = parent::get($k,$format,$formatTemplate);
                break;
        }
        return $value;
    }

    public function getPhpThumbUrl() {
        return MODX_URL_SCHEME.MODX_HTTP_HOST.$this->xpdo->getOption('gallery.phpthumb_url').'phpThumb.php';
    }


    public function upload($file) {
        if (empty($file) || empty($file['tmp_name']) || empty($file['name'])) return false;
        $uploaded = false;

        $dateFolder = date('Y').'/'.date('m').'/';
        $targetDir = $this->xpdo->getOption('gallery.files_path').$dateFolder;

        $cacheManager = $this->xpdo->getCacheManager();
        /* if directory doesnt exist, create it */
        if (!file_exists($targetDir) || !is_dir($targetDir)) {
            if (!$cacheManager->writeTree($targetDir)) {
               $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$targetDir);
               return $uploaded;
            }
        }
        /* make sure directory is readable/writable */
        if (!is_readable($targetDir) || !is_writable($targetDir)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$targetDir);
            return $uploaded;
        }

        /* upload the file */
        $fileNameLower = strtolower($file['name']);
        $location = strtr($targetDir.'/'.$fileNameLower,'\\','/');
        $location = str_replace('//','/',$location);
        if (@file_exists($location.$fileNameLower)) {
            @unlink($location.$fileNameLower);
        }
        if (!@move_uploaded_file($file['tmp_name'],$location)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to upload the file: '.$file['tmp_name'].' to '.$location);
        } else {
            $uploaded = true;
            $this->set('filename',$dateFolder.$fileNameLower);
        }

        return $uploaded;
    }

    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('createdon')) {
            $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        if ($this->isNew() && !$this->get('createdby')) {
            if (!empty($this->xpdo->user) && $this->xpdo->user instanceof modUser) {
                if ($this->xpdo->user->isAuthenticated()) {
                    $this->set('createdby',$this->xpdo->user->get('id'));
                }
            }
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }

    public function remove(array $ancestors = array()) {
        $filename = $this->get('filename');
        if (!empty($filename)) {
            $filename = $this->xpdo->getOption('gallery.files_path').$filename;
            if (!@unlink($filename)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to remove the attachment file at: '.$filename);
            }
        }
        return parent::remove($ancestors);
    }

    protected function formatFileSize($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}