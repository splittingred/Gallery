<?php
/**
 * @package gallery
 * @subpackage processors
 */
/* get board */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
$album = $modx->getObject('galAlbum',$_REQUEST['id']);
if (!$album) return $modx->error->failure($modx->lexicon('gallery.album_err_nf'));

/* output */
$albumArray = $album->toArray('',true);
return $modx->error->success('',$albumArray);