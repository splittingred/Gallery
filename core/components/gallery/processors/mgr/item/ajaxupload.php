<?php

$input = fopen("php://input", "r");
$temp = tmpfile();
$realSize = stream_copy_to_stream($input, $temp);
fclose($input);

/*$target = fopen($path, "w");
fseek($temp, 0, SEEK_SET);
stream_copy_to_stream($temp, $target);
fclose($target);*/

/* validate form */
$album = $modx->getOption('album',$scriptProperties,false);
$filenm = $modx->getOption('qqfile',$scriptProperties,false);
if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
if (empty($filenm)) return $modx->error->failure($modx->lexicon('gallery.item_err_ns'));

/* create item */
$scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
$modx->log(MODX_LEVEL_ERROR,print_r($scriptProperties,true));
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
$targetDir = $modx->getOption('gallery.files_path').$albumDir;

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

$target = fopen($absolutePath, "w");
fseek($temp, 0, SEEK_SET);
$bytes = stream_copy_to_stream($temp, $target);
fclose($target);

if ($bytes == 0) {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to upload the to '.$absolutePath);
    $item->remove();
    return $modx->toJSON(array('error' => 'gallery.item_err_upload'));
} else {
    $item->set('filename',str_replace(' ','',$relativePath));
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