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

if ($isLimit) $c->limit($limit,$start);
$albums = $modx->getCollection('galAlbum',$c);

$list = array();
foreach ($albums as $album) {
    $albumArray = $album->toArray();

    $albumArray['menu'] = array();
    $albumArray['menu'][] = array(
        'text' => 'Update Album',
        'handler' => 'this.updateAlbum',
    );
    $albumArray['menu'][] = '-';
    $albumArray['menu'][] = array(
        'text' => 'Remove Album',
        'handler' => 'this.removeAlbum',
    );

    $list[]= $albumArray;
}
return $this->outputArray($list,$count);