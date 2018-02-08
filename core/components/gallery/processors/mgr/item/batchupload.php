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
 * Batch upload items into an Album via a directory
 *
 * @package gallery
 */

/* validate form */
$album = $modx->getOption('album',$scriptProperties,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));

$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;

if (empty($scriptProperties['directory'])) $modx->error->addField('directory',$modx->lexicon('gallery.directory_err_ns'));
$directory = str_replace(array(
    '{base_path}',
    '{assets_path}',
    '{core_path}',
),array(
    $modx->getOption('base_path',null,MODX_BASE_PATH),
    $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
    $modx->getOption('core_path',null,MODX_CORE_PATH),
),$scriptProperties['directory']);

if (empty($directory) || !is_dir($directory)) $modx->error->addField('directory',$modx->lexicon('gallery.directory_err_nf'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}


/* get sanitized base path and current path */
$modx->getService('fileHandler','modFileHandler');
$fullpath = $modx->fileHandler->sanitizePath($directory);


$targetDir = $modx->call('galAlbum','getFilesPath',array(&$modx)).$scriptProperties['album'].'/';

$imagesExts = array('jpg','jpeg','png','gif','bmp');
$use_multibyte = $modx->getOption('use_multibyte',null,false);
$encoding = $modx->getOption('modx_charset',null,'UTF-8');
/* iterate */
$images = array();
$errors = array();
$files = array();
foreach (new DirectoryIterator($fullpath) as $file) {
    if (in_array($file,array('.','..','.svn','_notes'))) continue;
    if (!$file->isReadable() || $file->isDir()) continue;

    $files[$file->getFilename()] = array(
        'pathname' => $file->getPathname()
    );
}
ksort($files);
foreach ($files as $f_name => $file) {
    $fileName = $f_name;
    $filePathName = $file['pathname'];

    $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
    $fileExtension = $use_multibyte ? mb_strtolower($fileExtension,$encoding) : strtolower($fileExtension);
    if (!in_array($fileExtension,$imagesExts)) continue;

    /* create item */
    $item = $modx->newObject('galItem');
    $item->set('name',$fileName);
    $item->set('createdby',$modx->user->get('id'));
    $item->set('mediatype','image');
    $item->set('active',$scriptProperties['active']);
    if (!$item->save()) {
        $errors[] = $modx->lexicon('gallery.item_err_save');
        continue;
    }

    $newFileName = $item->get('id').'.'.$fileExtension;
    $newRelativePath = $scriptProperties['album'].'/'.$newFileName;
    $newAbsolutePath = $targetDir.'/'.$newFileName;

    $file = array("name" => $newRelativePath, "tmp_name" => $filePathName, "error" => "0"); // emulate a $_FILES object

    $success = $item->upload($file,$scriptProperties['album']);
    if(!$success) {
        $errors[] = $modx->lexicon('gallery.file_err_move',array(
            'file' => $newFileName,
            'target' => $newAbsolutePath,
        ));
        $item->remove();
        continue;
    } else {
        $item->set('filename',$newRelativePath);
        $item->save();
    }

    /* get count of items in album */
    $total = $modx->getCount('galAlbumItem',array('album' => $_POST['album']));

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

if (!empty($errors)) {
    return $modx->error->failure(implode(',',$errors));
}

/* output to browser */
return $modx->error->success('',$images);
