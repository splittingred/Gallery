<?php
/**
 * Sort galleries via drag/drop
 *
 * @package gallery
 * @subpackage processors
 */
class GalleryAlbumSortProcessor extends modObjectProcessor {
    public $classKey = 'galAlbum';
    public $objectType = 'gallery.album';
    public $languageTopics = array('gallery:default');

    public $data = array();
    public $nodes = array();

    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->data = is_array($data) ? $data : $this->modx->fromJSON(urldecode($data));

        return parent::initialize();
    }

    public function process() {
        $this->formatNodes($this->data);

        /* readjust cache */
        foreach ($this->nodes as $nodeArray) {
            if (empty($nodeArray['classKey']) || empty($nodeArray['id'])) continue;
            /** @var galItem|galAlbum $node */
            $node = $this->modx->getObject($nodeArray['classKey'],$nodeArray['id']);
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

    }

    public function formatNodes($curLevel,$parent = 0) {
        if (!is_array($curLevel)) return array();
        $order = 0;
        foreach ($curLevel as $id => $curNode) {
            $ar = explode('_',$id);
            if (!empty($ar[1]) && $ar[0] != 'root') {
                $par = explode('_',$parent);
                $this->nodes[] = array(
                    'id' => $ar[1],
                    'classKey' => 'gal'.ucfirst($ar[0]),
                    'parent' => !empty($parent) ? $par[1] : 0,
                    'parentClassKey' => !empty($parent) ? 'gal'.ucfirst($par[0]) : '',
                    'rank' => $order,
                );
                $order++;
            }
            $this->formatNodes($curNode['children'],$id);
        }
        return $this->nodes;
    }
}
return 'GalleryAlbumSortProcessor';