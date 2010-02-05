<?php
/**
 * @package gallery
 * @subpackage build
 */
$modx =& $object->xpdo;
$success = true;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

$modx->log(modX::LOG_LEVEL_INFO,'Installing default Resources...');

/* add in connector resource */
$resource = null;
$setting = $modx->getObject('modSystemSetting',array('key' => 'gallery.connector_resource'));
if (!empty($setting)) {
    $resource = $modx->getObject('modResource',$setting->get('value'));
}
if (empty($resource)) {

    $modx->log(modX::LOG_LEVEL_INFO,'Adding Connector Resource...');
    $resource = $modx->newObject('modResource');
    $resource->fromArray(array(
        'context_key' => 'web',
        'class_key' => 'modDocument',
        'pagetitle' => 'Gallery Connector',
        'alias' => 'gallery-connector',
        'content' => '[[!GalleryConnector]]',
        'parent' => 0,
        'template' => 0,
        'published' => true,
        'cacheable' => false,
        'searchable' => false,
        'hidemenu' => true,
        'isfolder' => false,
    ));
    $resource->save();

    $setting = $modx->newObject('modSystemSetting');
    $setting->set('key','gallery.connector_resource');
    $setting->set('value',$resource->get('id'));
    $setting->set('namespace','gallery');
    $setting->set('xtype','textfield');
    $setting->set('area','Resource Map');
    $setting->save();
}
    case xPDOTransport::ACTION_UNINSTALL:

        $success= true;
        break;
}

return $success;