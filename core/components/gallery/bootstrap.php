<?php

/**
 * @var \MODX\Revolution\modX $modx
 * @var array $namespace
 */

use Gallery\Gallery;
use xPDO\xPDO;

try {
    // Add the package and model classes
    $modx->addPackage('Gallery\Model', $namespace['path'] . 'src/', null, 'Gallery\\');
}
catch (Exception $e) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage());
}
