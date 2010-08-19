<?php
/**
 * @package gallery
 */
/* load phpThumb */
require_once $modx->gallery->config['modelPath'].'gallery/galphpthumb.class.php';
$phpThumb = new galPhpThumb($modx,$scriptProperties);

/* do initial setup */
$phpThumb->initialize();

/* set source and generate thumbnail */
$phpThumb->set($modx->getOption('src',$scriptProperties,''));

/* check to see if there's a cached file of this already */
if ($phpThumb->checkForCachedFile()) {
    $phpThumb->loadCache();
    return '';
}

/* generate thumbnail */
$phpThumb->generate();

/* cache the thumbnail and output */
$phpThumb->cache();
$phpThumb->output();
return '';