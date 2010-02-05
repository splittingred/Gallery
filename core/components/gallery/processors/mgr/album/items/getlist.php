<?php
/**
 * Get a list of Album Items
 *
 * @package gallery
 * @subpackage processors
 */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,24);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$album = $modx->getOption('album',$_REQUEST,false);

if (empty($album)) return $this->outputArray(array(),0);

$c = $modx->newQuery('galItem');
$c->innerJoin('galAlbumItem','AlbumItems','`galItem`.`id` = `AlbumItems`.`item` AND `AlbumItems`.`album` = '.$album);
$c->innerJoin('galAlbum','Album','`Album`.`id` = `AlbumItems`.`album`');
$count = $modx->getCount('galItem',$c);

$modx->setLogTarget('ECHO');
$c->leftJoin('galTag','Tags');
$c->select('
    `galItem`.*,
    `AlbumItems`.`rank` AS `rank`,
    `Album`.`id` AS `album`,
    (
        SELECT GROUP_CONCAT(`Tags`.`tag`) FROM '.$modx->getTableName('galTag').' AS `Tags`
        WHERE `Tags`.`item` = `galItem`.`id`
    ) AS `tags`
');

if ($isLimit) $c->limit($limit,$start);
$c->sortby('rank','ASC');
$items = $modx->getCollection('galItem',$c);

$list = array();
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['filename'] = basename($item->get('filename'));
    $imagePath = $item->get('image_path');
    $size = @getimagesize($imagePath);
    if (is_array($size)) {
        $itemArray['image_width'] = $size[0];
        $itemArray['image_height'] = $size[1];
        $itemArray['image_type'] = $size[2];
    }
    $c = array();
    $c['h'] = 100;
    $c['w'] = 100;
    $c['zc'] = 'C';
    $itemArray['thumbnail'] = $item->get('thumbnail',$c);
    $itemArray['image'] = $item->get('image');

    $itemArray['filesize'] = $item->get('filesize');

    $itemArray['menu'] = array();
    $itemArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.item_update'),
        'handler' => 'this.updateItem',
    );
    $itemArray['menu'][] = '-';
    /*
    $itemArray['menu'][] = array(
        'text' => 'Remove Item From Album',
        'handler' => 'this.removeItemFromAlbum',
    );
    */
    $itemArray['menu'][] = array(
        'text' => $modx->lexicon('gallery.item_delete'),
        'handler' => 'this.deleteItem',
    );

    $list[]= $itemArray;
}
return $this->outputArray($list,$count);