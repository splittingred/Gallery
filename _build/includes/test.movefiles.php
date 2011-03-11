<?php
$modelPath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/';
$modx->addPackage('gallery',$modelPath);
$modx->getCacheManager();

$results = array();
$galleryFileStructureVersion = (float)$modx->getOption('gallery.file_structure_version',NULL,0);
if ($galleryFileStructureVersion < 1) {
    $c = $modx->newQuery('galItem');
    $c->select(array(
        'galItem.*',
        'AlbumItems.album',
    ));
    $c->innerJoin('galAlbumItem','AlbumItems');
    $c->sortby('album','DESC');
    $c->sortby('filename','DESC');
    $items = $modx->getCollection('galItem',$c);

    $basePath = $modx->getOption('gallery.files_path');

    $currentAlbum = 0;
    $albumPath = '';
    foreach ($items as $item) {
        $oldFullPath = $basePath.$item->get('filename');
        if (!file_exists($oldFullPath)) continue;

        if ($item->get('album') != $currentAlbum) {
            $albumRelativePath = $item->get('album').'/';
            $albumFullPath = $basePath.$albumRelativePath;

            /* if directory doesn't exist, create it */
            if (!file_exists($albumFullPath) || !is_dir($albumFullPath)) {
                if (!$modx->cacheManager->writeTree($albumFullPath)) {
                   $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$albumFullPath);
                   continue;
                }
            }
            /* make sure directory is readable/writable */
            if (!is_readable($albumFullPath) || !is_writable($albumFullPath)) {
                $modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$albumFullPath);
                continue;
            }
            $currentAlbum = $item->get('album');
        }

        /* calculate new file paths */
        $ext = pathinfo($oldFullPath,PATHINFO_EXTENSION);
        $newFileName = $albumRelativePath.$item->get('id').'.'.$ext;
        $newFullPath = $basePath.$newFileName;

        /* already moved? */
        if ($newFileName == $item->get('filename')) continue;

        /* move old file to this new location */
        if (@copy($oldFullPath,$newFullPath)) {
            $item->set('filename',$newFileName);
            $item->save();

            /* remove old file */
            @unlink($oldFullPath);
        }
        $results[] = array(
            'oldFullPath' => $oldFullPath,
            'newFullPath' => $newFullPath,
            'newFileName' => $newFileName,
        );
    }

    /* create structure version to prevent script from running again */
    $setting = $modx->getObject('modSystemSetting',array('key' => 'gallery.file_structure_version'));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
        $setting->set('key','gallery.file_structure_version');
        $setting->set('namespace','gallery');
        $setting->set('xtype','textfield');
        $setting->set('area','system');
    }
    $setting->set('value','1.0');
    $setting->save();
}

var_dump($results);
return '';

