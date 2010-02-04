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

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return true;