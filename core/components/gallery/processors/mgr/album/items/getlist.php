<?php
/**
 * Get a list of Album Items
 *
 * @package gallery
 * @subpackage processors
 */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
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
    `Album`.`id` AS `album`,
    (
        SELECT GROUP_CONCAT(`Tags`.`tag`) FROM '.$modx->getTableName('galTag').' AS `Tags`
        WHERE `Tags`.`item` = `galItem`.`id`
    ) AS `tags`
');

if ($isLimit) $c->limit($limit,$start);
$items = $modx->getCollection('galItem',$c);

$list = array();
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['thumbnail'] = $item->get('thumbnail');
    $itemArray['filesize'] = $item->get('filesize');

    $itemArray['menu'] = array();
    $itemArray['menu'][] = array(
        'text' => 'Update Item',
        'handler' => 'this.updateAlbum',
    );
    $itemArray['menu'][] = '-';
    $itemArray['menu'][] = array(
        'text' => 'Remove Item From Album',
        'handler' => 'this.removeItemFromAlbum',
    );
    $itemArray['menu'][] = array(
        'text' => 'Delete Item',
        'handler' => 'this.deleteItem',
    );

    $list[]= $itemArray;
}
return $this->outputArray($list,$count);