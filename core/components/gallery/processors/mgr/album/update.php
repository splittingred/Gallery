<?php
/**
 * @package gallery
 * @subpackage processors
 */
/* get board */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
$album = $modx->getObject('galAlbum',$_REQUEST['id']);
if (!$album) return $modx->error->failure($modx->lexicon('gallery.album_err_nf'));

$_POST['active'] = !empty($_POST['active']) ? 1 : 0;
$_POST['prominent'] = !empty($_POST['prominent']) ? 1 : 0;
$album->fromArray($_POST);

if ($album->save() == false) {
    return $modx->error->failure($modx->lexicon('gallery.album_err_save'));
}

/* output */
$albumArray = $album->toArray('',true);
return $modx->error->success('',$albumArray);