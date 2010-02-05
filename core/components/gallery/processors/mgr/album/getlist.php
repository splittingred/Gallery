<?php
/**
 * Get a list of Albums
 *
 * @package gallery
 * @subpackage processors
 */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

$c = $modx->newQuery('galAlbum');
$count = $modx->getCount('galAlbum',$c);
$c->select('
    `galAlbum`.*,
    (SELECT COUNT(*) FROM '.$modx->getTableName('galAlbumItem').' AS `AlbumItem`
     WHERE `AlbumItem`.`album` = `galAlbum`.`id`) AS `items`
');

if ($isLimit) $c->limit($limit,$start);
$albums = $modx->getCollection('galAlbum',$c);

$list = array();
foreach ($albums as $album) {
    $albumArray = $album->toArray();

    $albumArray['menu'] = array();
    $albumArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.album_update'),
        'handler' => 'this.updateAlbum',
    );
    $albumArray['menu'][] = '-';
    $albumArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.album_remove'),
        'handler' => 'this.removeAlbum',
    );

    $list[]= $albumArray;
}
return $this->outputArray($list,$count);