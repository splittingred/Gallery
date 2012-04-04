<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

/* validate form */
$album = $modx->getOption('album',$scriptProperties,false);
$filenm = $modx->getOption('qqfile',$scriptProperties,false);
/* If $filenm is an array, it means we've used IE and we need to use a different name. */
if (is_array($filenm)) $filenm = $filenm['name'];

if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
if (empty($filenm)) return $modx->error->failure($modx->lexicon('gallery.item_err_ns'));

/* create item */
$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
/** @var galItem $item */
$item = $modx->newObject('galItem');
$item->fromArray($scriptProperties);
$item->set('createdby',$modx->user->get('id'));
$item->set('name',$filenm);

if (!$item->save()) {
    return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
}

/* Upload */
$albumDir = $album.'/';
$targetDir = $modx->call('galAlbum','getFilesPath',array(&$modx)).$albumDir;

$cacheManager = $modx->getCacheManager();
/* if directory doesnt exist, create it */
if (!file_exists($targetDir) || !is_dir($targetDir)) {
    if (!$cacheManager->writeTree($targetDir)) {
       $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$targetDir);
       return $modx->toJSON(array('error' => 'Could not create directory: ' . $targetDir));
    }
}
/* make sure directory is readable/writable */
if (!is_readable($targetDir) || !is_writable($targetDir)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$targetDir);
    return $modx->toJSON(array('error' => 'Could not write to directory: ' . $targetDir));
}

/* upload the file */
$extension = end(explode('.', $filenm));
$filename = $item->get('id').'.'.$extension;
$relativePath = $albumDir.$filename;
$absolutePath = $targetDir.$filename;

if (@file_exists($absolutePath)) {
    @unlink($absolutePath);
}

if (!empty($_FILES['qqfile'])) {
    if (!$item->upload($_FILES['qqfile'],$scriptProperties['album'])) {
        $item->remove();
        return $modx->error->failure($modx->lexicon('gallery.item_err_upload'));
    }
} else {
    /* Using AJAX upload */
    $input = fopen("php://input", "r");
    $target = fopen($absolutePath, "w");
    $bytes = stream_copy_to_stream($input, $target);
    fclose($input);
    fclose($target);
    
    if ($bytes == 0) {
        $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to upload the file to '.$absolutePath);
        $item->remove();
        return $modx->toJSON(array('error' => 'gallery.item_err_upload'));
    } else {
        $item->set('filename',str_replace(' ','',$relativePath));
    }
}

$item->save();

/* get count of items in album */
$total = $modx->getCount('galAlbumItem',array('album' => $scriptProperties['album']));

/* associate with album */
/** @var galAlbumItem $albumItem */
$albumItem = $modx->newObject('galAlbumItem');
$albumItem->set('album',$scriptProperties['album']);
$albumItem->set('item',$item->get('id'));
$albumItem->set('rank',$total);
$albumItem->save();

/* save tags */
if (isset($scriptProperties['tags'])) {
    $tagNames = explode(',',$scriptProperties['tags']);
    foreach ($tagNames as $tagName) {
        $tagName = trim($tagName);
        if (empty($tagName)) continue;

        /** @var galTag $tag */
        $tag = $modx->newObject('galTag');
        $tag->set('item',$item->get('id'));
        $tag->set('tag',$tagName);
        $tag->save();
    }
}

/* output to browser */
return $modx->toJSON(array('success' => true));

?>