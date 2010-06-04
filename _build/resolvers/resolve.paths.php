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
 * Resolve paths
 *
 * @package gallery
 * @subpackage build
 */
function createSetting(&$modx,$key,$value) {
    $ct = $modx->getCount('modSystemSetting',array(
        'key' => 'gallery.'.$key,
    ));
    if (empty($ct)) {
        $setting = $modx->newObject('modSystemSetting');
        $setting->set('key','gallery.'.$key);
        $setting->set('value',$value);
        $setting->set('namespace','gallery');
        $setting->set('area','Paths');
        $setting->save();
    }
}
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            /* setup paths */
            createSetting($modx,'core_path',$modx->getOption('core_path').'components/gallery/');
            createSetting($modx,'assets_path',$modx->getOption('assets_path').'components/gallery/');
            createSetting($modx,'files_path',$modx->getOption('assets_path').'components/gallery/files/');
            createSetting($modx,'phpthumb_path',$modx->getOption('assets_path').'components/phpthumb/');

            @mkdir($modx->getOption('assets_path').'components/gallery/files/',0775);

            /* setup urls */
            createSetting($modx,'assets_url',$modx->getOption('assets_url').'components/gallery/');
            createSetting($modx,'files_url',$modx->getOption('assets_url').'components/gallery/files/');
            createSetting($modx,'phpthumb_url',$modx->getOption('assets_url').'components/phpthumb/');
        break;
    }
}
return true;