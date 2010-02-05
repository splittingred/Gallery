<?php
/**
 * @package gallery
 */
class galAlbum extends xPDOSimpleObject {

    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('createdon')) {
            $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }



    public function remove(array $ancestors = array()) {
        $removed = parent::remove($ancestors);
        if ($removed) {
            $c = $this->xpdo->newQuery('galItem');
            $c->innerJoin('galAlbumItem','AlbumItems');
            $c->where(array(
                'AlbumItems.album' => $this->get('id'),
            ));
            $items = $this->xpdo->getCollection('galItem',$c);
            foreach ($items as $item) {
                $count = $this->xpdo->getCount('galAlbumItem',array(
                    'item' => $item->get('id'),
                    'album:!=' => $this->get('id'),
                ));
                if ($count <= 0) {
                    $item->remove();
                }
            }
        }
        return $removed;
    }

}