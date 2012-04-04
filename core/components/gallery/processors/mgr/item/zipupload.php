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

/* load import class */
$loaded = $modx->gallery->loadImporter('galZipImport');
if ($loaded !== true) return $modx->error->failure($loaded);

/* attempt to set source zip */
if (!$modx->gallery->galZipImport->setSource($_FILES['zip'])) {
    $modx->error->addField('zip',$modx->lexicon('gallery.zip_err_ns'));
}

$targetSet = $modx->gallery->galZipImport->setTarget($scriptProperties['album']);
if (!$targetSet) {
    $modx->error->addField('zip',$targetSet);
}
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* run import */
$success = $modx->gallery->galZipImport->run($scriptProperties);
if ($success !== true) {
    return $modx->error->failure($success);
}

/* output to browser */
return $modx->error->success('',$modx->gallery->galZipImport->results);