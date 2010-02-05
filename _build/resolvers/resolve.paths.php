<?php
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

            @mkdir($modx->getOption('assets_path').'components/gallery/files/',0775);

            /* setup urls */
            createSetting($modx,'assets_url',$modx->getOption('assets_url').'components/gallery/');
            createSetting($modx,'files_url',$modx->getOption('assets_url').'components/gallery/files/');
        break;
    }
}
return true;