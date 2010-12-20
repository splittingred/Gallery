<?php
/**
 * Get all albums as nodes.
 *
 * @package gallery
 * @subpackage processors
 */
$nodes = array();

$c = $modx->newQuery('galAlbum');
$c->select(array('galAlbum.*'));
$c->select('`Parent`.`name` AS `parent_name`');
$c->leftJoin('galAlbum','Parent');
$c->where(array(
    'parent' => $id,
));
$c->sortby('galAlbum.rank','ASC');
$albums = $modx->getCollection('galAlbum',$c);

$action = $modx->getObject('modAction',array(
    'controller' => 'index',
    'namespace' => 'gallery',
));

foreach ($albums as $album) {
    $albumArray = $album->toArray();

    $albumArray['pk'] = $album->get('id');
    $albumArray['text'] = $album->get('name').' ('.$album->get('id').')';
    $albumArray['leaf'] = false;
    $albumArray['parent'] = 0;
    $albumArray['cls'] = 'icon-tiff'.($album->get('active') ? '' : ' gal-item-inactive');
    $albumArray['classKey'] = 'galAlbum';
    if (!empty($action)) {
        $albumArray['page'] = '?a='.$action->get('id').'&album='.$album->get('id').'&action=album/update';
    }

    $albumArray['menu'] = array('items' => array());
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_update'),
        'handler' => 'function(itm,e) { this.updateAlbum(itm,e); }',
    );
    $albumArray['menu']['items'][] = '-';
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_create'),
        'handler' => 'function(itm,e) { this.createAlbum(itm,e); }',
    );
    $albumArray['menu']['items'][] = '-';
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_remove'),
        'handler' => 'function(itm,e) { this.removeAlbum(itm,e); }',
    );

    $albumArray['id'] = 'album_'.$album->get('id');
    $nodes[] = $albumArray;
}
unset($albums,$album,$albumArray,$c);

return $nodes;