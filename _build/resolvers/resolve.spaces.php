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
 * Resolve spaces in image filenames
 *
 * @package gallery
 * @subpackage build
 */

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/';
            $modx->addPackage('gallery',$modelPath);

            $filesPath = $modx->getOption('gallery.files_path',null,$modx->getOption('assets_path',null,MODX_ASSETS_PATH).'components/gallery/files/');

            $items = $modx->getCollection('galItem');

            foreach ($items as $item) {
                $filename = $item->get('filename');
                $filenameNoSpaces = str_replace(' ','',$filename);
                if (strcmp($filenameNoSpaces,$filename) !== 0) {
                    $path = $filesPath;
                    $newFile = $path . $filenameNoSpaces;
                    $oldFile = $path . $filename;
                    if (@copy($oldFile,$newFile)) {
                        @unlink($oldFile);
                        $item->set('filename',$filenameNoSpaces);
                        $item->save();
                    }
                }
            }

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;