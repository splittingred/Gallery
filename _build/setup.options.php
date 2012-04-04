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
 * Build the setup options form.
 *
 * @package gallery
 * @subpackage build
 */
$message = '';
$galleryFileStructureVersion = (float)$modx->getOption('gallery.file_structure_version',NULL,0);
if ($galleryFileStructureVersion < 1) {
    $path = $modx->getOption('gallery.files_path',null,'');
    if (!empty($path)) {
        $message = 'Make sure to backup your Gallery albums in their paths before proceeding, as Gallery in this update will move them to album-centric storage. Your albums are at: '.$path;
    }
}
return $message;