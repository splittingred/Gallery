<?php
/**
 * Resolve phpthumb stuff
 *
 * @package gallery
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;

            $ptCacheDir = $object->xpdo->getOption('core_path').'cache/phpthumb/';
            @mkdir($ptCacheDir,0775);

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;