<?php
/**
 * @package gallery
 * @subpackage processors
 */
class GalleryAlbumGetNodesProcessor extends modObjectProcessor {
    public $nodes = array();
    public function process() {
        $id = $this->getProperty('id');
        $curNode = !empty($id) ? $id : 'root_0';
        $curNode = explode('_',trim($curNode));
        $type = $curNode[0];
        $id = isset($curNode[1]) ? $curNode[1] : 0;

        switch ($type) {
            case 'root':
            case 'album':
            default:
                $this->getAlbums($id);
                break;
        }

        return $this->toJSON($this->nodes);
    }

    public function getAlbums($id) {
        $c = $this->modx->newQuery('galAlbum');
        $c->select(array('galAlbum.*'));
        $c->select('`Parent`.`name` AS `parent_name`');
        $c->leftJoin('galAlbum','Parent');
        $c->where(array(
            'parent' => $id,
        ));
        $c->sortby('galAlbum.rank','ASC');
        $albums = $this->modx->getCollection('galAlbum',$c);

        $action = $this->modx->getObject('modAction',array(
            'controller' => 'index',
            'namespace' => 'gallery',
        ));

        /** @var galAlbum $album */
        foreach ($albums as $album) {
            $albumArray = $album->toArray();

            $albumArray['pk'] = $album->get('id');
            $albumArray['text'] = $album->get('name').' ('.$album->get('id').')';
            $albumArray['leaf'] = false;
            $albumArray['parent'] = 0;

            $version = $this->modx->getVersionData();
            if ($version['major_version'] < 3) {
                $albumArray['cls'] = 'icon-tiff'.($album->get('active') ? '' : ' gal-item-inactive');
            } else {
                $albumArray['iconCls'] = 'icon icon-tiff'.($album->get('active') ? '' : ' gal-item-inactive');
            }

            $albumArray['classKey'] = 'galAlbum';
            if (!empty($action)) {
                $albumArray['page'] = '?a='.$action->get('id').'&album='.$album->get('id').'&action=album/update';
            }

            $albumArray['menu'] = array('items' => array());
            $albumArray['menu']['items'][] = array(
                'text' => $this->modx->lexicon('gallery.album_update'),
                'handler' => 'function(itm,e) { this.updateAlbum(itm,e); }',
            );
            $albumArray['menu']['items'][] = '-';
            $albumArray['menu']['items'][] = array(
                'text' => $this->modx->lexicon('gallery.album_create'),
                'handler' => 'function(itm,e) { this.createAlbum(itm,e); }',
            );
            $albumArray['menu']['items'][] = '-';
            $albumArray['menu']['items'][] = array(
                'text' => $this->modx->lexicon('gallery.album_remove'),
                'handler' => 'function(itm,e) { this.removeAlbum(itm,e); }',
            );

            $albumArray['id'] = 'album_'.$album->get('id');
            $this->nodes[] = $albumArray;
        }
    }
}
return 'GalleryAlbumGetNodesProcessor';