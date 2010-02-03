<?php
/**
 * Resolve creating db tables
 *
 * @package gallery
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/';
            $modx->addPackage('gallery',$modelPath,'gallery_');

            $manager = $modx->getManager();

            $manager->createObjectContainer('galItem');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;