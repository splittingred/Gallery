<?php
/**
 * Sort galleries via drag/drop
 *
 * @package gallery
 * @subpackage processors
 */
if (empty($_POST['data'])) return $modx->error->failure('Invalid data.');
$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

/* readjust cache */
foreach ($nodes as $nodeArray) {
    if (empty($nodeArray['classKey']) || empty($nodeArray['id'])) continue;
    $node = $modx->getObject($nodeArray['classKey'],$nodeArray['id']);
    if (empty($node)) continue;

    switch ($nodeArray['classKey']) {
        case 'galAlbum':
        default:
            $node->set('parent',$nodeArray['parent']);
            break;
    }

    $node->set('rank',$nodeArray['rank']);
    $node->save();
}

function getNodesFormatted(&$nodes,$curLevel,$parent = 0) {
    if (!is_array($curLevel)) return array();
    $order = 0;
    foreach ($curLevel as $id => $curNode) {

        $ar = explode('_',$id);
        if (!empty($ar[1]) && $ar[0] != 'root') {
            $par = explode('_',$parent);
            $nodes[] = array(
                'id' => $ar[1],
                'classKey' => 'gal'.ucfirst($ar[0]),
                'parent' => !empty($parent) ? $par[1] : 0,
                'parentClassKey' => !empty($parent) ? 'gal'.ucfirst($par[0]) : '',
                'rank' => $order,
            );
            $order++;
        }
        getNodesFormatted($nodes,$curNode['children'],$id);
    }
}

return $modx->error->success();