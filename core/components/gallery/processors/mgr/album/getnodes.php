<?php
/**
 * @package gallery
 * @subpackage processors
 */
$curNode = !empty($_REQUEST['id']) ? $_REQUEST['id'] : 'root_0';
$curNode = explode('_',trim($curNode));
$type = $curNode[0];
$id = isset($curNode[1]) ? $curNode[1] : 0;

$nodes = array();

switch ($type) {
    case 'root':
    case 'album':
    default:
        $nodes = include dirname(__FILE__).'/getnodes.root.php';
        break;
}

return $this->toJSON($nodes);
