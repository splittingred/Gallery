<?php
/**
 * @package gallery
 * @subpackage processors
 */
if (empty($_POST['name'])) $modx->error->addField('name','Please enter a valid name.');
$_POST['prominent'] = !empty($_POST['prominent']) ? 1 : 0;

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$album = $modx->newObject('galAlbum');
$album->fromArray($_POST);

if ($album->save() == false) {
    return $modx->error->failure($modx->lexicon('gallery.album_err_save'));
}

return $modx->error->success('',$album);