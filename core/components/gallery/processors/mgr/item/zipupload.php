<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
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
 * Batch upload items into an Album via a Zip file
 *
 * @package gallery
 */

/* validate form */
$album = $modx->getOption('album',$scriptProperties,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));

$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
if (empty($_FILES['zip']) || $_FILES['zip']['error'] !== UPLOAD_ERR_OK) {
    $modx->error->addField('zip',$modx->lexicon('gallery.zip_err_ns'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* get sanitized base path and current path */
$modx->getService('fileHandler','modFileHandler');
$targetDir = $modx->getOption('gallery.files_path').$scriptProperties['album'].'/';
$cacheManager = $modx->getCacheManager();

/* if directory doesnt exist, create it */
if (!file_exists($targetDir) || !is_dir($targetDir)) {
    if (!$cacheManager->writeTree($targetDir)) {
       $modx->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$targetDir);
       return $modx->error->failure($modx->lexicon('gallery.directory_err_create',array('directory' => $targetDir)));
    }
}
/* make sure directory is readable/writable */
if (!is_readable($targetDir) || !is_writable($targetDir)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$targetDir);
    return $modx->error->failure($modx->lexicon('gallery.directory_err_write',array('directory' => $targetDir)));
}

/* unpack zip file */
if (!$modx->loadClass('compression.xPDOZip',$modx->getOption('core_path').'xpdo/',true,true)) {
    return $modx->error->failure($modx->lexicon('gallery.xpdozip_err_nf'));
}
$file = $_FILES['zip'];
$archive = new xPDOZip($modx,$file['tmp_name']);
if (!$archive) {
    return $modx->error->failure($modx->lexicon('gallery.zip_err_unpack'));
}
$archive->unpack($targetDir);
$archive->close();

/* iterate */
$imagesExts = array('jpg','jpeg','png','gif','bmp');
$use_multibyte = $modx->getOption('use_multibyte',null,false);
$encoding = $modx->getOption('modx_charset',null,'UTF-8');
$images = array();
$errors = array();
$invDirs = array('.','..','.svn','.git','__MACOSX','.DS_Store');
/* iterate over zipped files and move them into main dir */
foreach (new DirectoryIterator($targetDir) as $dir) {
    if (in_array($dir->getFilename(),$invDirs)) continue;
    if ($dir->isDir()) {
        foreach (new DirectoryIterator($dir->getPathname()) as $file) {
            if (in_array($file->getFilename(),$invDirs)) continue;

            $fileName = $file->getFilename();
            $filePathName = $file->getPathname();

            $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
            $fileExtension = $use_multibyte ? mb_strtolower($fileExtension,$encoding) : strtolower($fileExtension);
            if (!in_array($fileExtension,$imagesExts)) continue;

            /* create item */
            $item = $modx->newObject('galItem');
            $item->set('name',$fileName);
            $item->set('createdby',$modx->user->get('id'));
            $item->set('mediatype','image');
            $item->set('active',$_POST['active']);

            /* upload the file */
            $fileNameLower = str_replace(' ','',strtolower($fileName));
            $location = strtr($targetDir.'/'.$fileNameLower,'\\','/');
            $location = str_replace('//','/',$location);
            if (@file_exists($location.$fileNameLower)) {
                @unlink($location.$fileNameLower);
            }
            if (!@copy($filePathName,$location)) {
                $errors[] = $modx->lexicon('gallery.file_err_move',array(
                    'file' => $fileNameLower,
                    'target' => $location,
                ));
                continue;
            } else {
                $item->set('filename',$dateFolder.$fileNameLower);
            }

            if (!$item->save()) {
                $errors[] = $modx->lexicon('gallery.item_err_save');
                continue;
            }

            /* get count of items in album */
            $total = $modx->getCount('galAlbumItem',array('album' => $scriptProperties['album']));

            /* associate with album */
            $albumItem = $modx->newObject('galAlbumItem');
            $albumItem->set('album',$scriptProperties['album']);
            $albumItem->set('item',$item->get('id'));
            $albumItem->set('rank',$total);
            $albumItem->save();

            /* save tags */
            if (isset($scriptProperties['tags'])) {
                $tagNames = explode(',',$scriptProperties['tags']);
                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (empty($tagName)) continue;

                    $tag = $modx->newObject('galTag');
                    $tag->set('item',$item->get('id'));
                    $tag->set('tag',$tagName);
                    $tag->save();
                }
            }

            $images[] = $fileName;
        }
        /* delete subdir */
        $modx->cacheManager->deleteTree($dir->getPathname(),array('deleteTop' => true, 'skipDirs' => false, 'extensions' => '*'));
    }
}

if (!empty($errors)) {
    return $modx->error->failure(implode(',',$errors));
}

/* output to browser */
return $modx->error->success('',$images);