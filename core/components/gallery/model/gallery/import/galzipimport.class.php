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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'galimport.class.php';
/**
 * Gallery import derivative class for zip-based imports. Supports both zip files that have a directory inside
 * and files that just contain images.
 *
 * @package gallery
 */
class galZipImport extends galImport {
    const OPT_IGNORE_DIRECTORIES = 'ignore_directories';
    
    /**
     * Initialize the zip import class and setup directory based options.
     *
     * @abstract
     * @return void
     */
    public function initialize() {
        $this->config[galZipImport::OPT_IGNORE_DIRECTORIES] = explode(',',$this->modx->getOption('gallery.import_ignore_directories',null,'.,..,.svn,.git,__MACOSX,.DS_Store'));
    }

    /**
     * Set the source zip file for the import.
     *
     * @param string|array $source
     * @param array $options
     * @return boolean
     */
    public function setSource($source,array $options = array()) {
        if (empty($source) || $source['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        $this->source = $source;
        return true;
    }

    /**
     * Run the import script.
     * 
     * @param array $options
     * @return bool|string
     */
    public function run(array $options) {
        $unpacked = $this->unpack();
        if ($unpacked !== true) return $unpacked;

        /* iterate */
        $this->errors = array();
        /* iterate over zipped files and move them into main dir */
        foreach (new DirectoryIterator($this->target) as $dir) {
            if ($dir->isDir()) {
                if (in_array($dir->getFilename(),$this->config[galZipImport::OPT_IGNORE_DIRECTORIES])) {
                    continue;
                }
                foreach (new DirectoryIterator($dir->getPathname()) as $file) {
                    $this->importFile($file,$options);
                }
                /* delete subdir */
                $this->modx->cacheManager->deleteTree($dir->getPathname(),array('deleteTop' => true, 'skipDirs' => false, 'extensions' => '*'));
            } else {
                $this->importFile($dir,$options);
                @unlink($dir->getPathname());
            }
        }
        if (!empty($this->errors)) {
            return implode(',',$this->errors);
        }
        return true;
    }

    /**
     * Import a specific file into the current album
     * 
     * @param object $file A DirectoryIterator item that represents the file
     * @param array $options
     * @return bool
     */
    public function importFile($file,array $options = array()) {
        if (in_array($file->getFilename(),$this->config[galZipImport::OPT_IGNORE_DIRECTORIES])) return false;

        $fileName = $file->getFilename();
        $filePathName = $file->getPathname();

        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
        $fileExtension = $this->config[galImport::OPT_USE_MULTIBYTE] ? mb_strtolower($fileExtension,$this->config[galImport::OPT_ENCODING]) : strtolower($fileExtension);
        if (!in_array($fileExtension,$this->config[galImport::OPT_EXTENSIONS])) return false;

        /* create item */
        $item = $this->modx->newObject('galItem');
        $item->set('name',$fileName);
        $item->set('createdby',$this->modx->user->get('id'));
        $item->set('mediatype','image');
        $item->set('active',$options['active']);
        if (!$item->save()) {
            $this->errors[] = $this->modx->lexicon('gallery.item_err_save');
            return false;
        }

        $newFileName = $item->get('id').'.'.$fileExtension;
        $newRelativePath = $this->albumId.'/'.$newFileName;
        $newAbsolutePath = $this->target.'/'.$newFileName;

        $file = array("name" => $newRelativePath, "tmp_name" => $filePathName, "error" => "0"); // emulate a $_FILES object

        $success = $item->upload($file,$options['album']);
        if(!$success) {
            $errors[] = $this->modx->lexicon('gallery.file_err_move',array(
                'file' => $newFileName,
                'target' => $newAbsolutePath,
            ));
            $item->remove();
            return false;
        } else {
            $item->set('filename',$newRelativePath);
            $item->save();
        }

        $this->associateToAlbum($item->get('id'));
        /* save tags */
        if (!empty($options['tags'])) {
            $this->processTags($options['tags'],$item->get('id'));
        }
        $this->results[] = $fileName;
        return true;
    }

    /**
     * Unpack the zip file using the xPDOZip class
     * 
     * @return bool|string
     */
    public function unpack() {
        if (!$this->modx->loadClass('compression.xPDOZip',$this->modx->getOption('core_path').'xpdo/',true,true)) {
            return $this->modx->lexicon('gallery.xpdozip_err_nf');
        }
        /* unpack zip file */
        $archive = new xPDOZip($this->modx,$this->source['tmp_name']);
        if (!$archive) {
            return $this->modx->lexicon('gallery.zip_err_unpack');
        }
        $archive->unpack($this->target);
        $archive->close();
        return true;
    }
}