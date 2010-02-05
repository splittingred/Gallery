<?php
/**
 * @package gallery
 * @subpackage processors
 */
if (empty($_POST['name'])) $modx->error->addField('name',$modx->lexicon('gallery.album_err_ns_name'));
$_POST['prominent'] = !empty($_POST['prominent']) ? 1 : 0;
$_POST['active'] = !empty($_POST['active']) ? 1 : 0;

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$album = $modx->newObject('galAlbum');
$album->fromArray($_POST);
$album->set('createdby',$modx->user->get('id'));

$total = $modx->getCount('galAlbum');
$album->set('rank',$total);

if ($album->save() == false) {
    return $modx->error->failure($modx->lexicon('gallery.album_err_save'));
}

return $modx->error->success('',$album);