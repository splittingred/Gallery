<?php
/**
 * @package gallery
 */
class GalleryAlbumsMediaSource extends modMediaSource implements modMediaSourceInterface {
    /** @var Gallery $gallery */
    public $gallery;
    /**
     * Initialize the source, preparing it for usage.
     *
     * @return boolean
     */
    public function initialize() {
        $this->gallery = $this->xpdo->getService('gallery','Gallery',$this->xpdo->getOption('gallery.core_path',null,$this->xpdo->getOption('core_path').'components/gallery/').'model/gallery/');
        if (!($this->gallery instanceof Gallery)) return false;
        $this->xpdo->lexicon->load('gallery:default','gallery:source');
        return true;
    }

    /**
     * Return an array of containers at this current level in the container structure. Used for the tree
     * navigation on the files tree.
     *
     * @abstract
     * @param string $path
     * @return array
     */
    public function getContainerList($path) {
        $properties = $this->getPropertyList();
        $list = array();
        if ($path == '/') {
            if (!empty($properties['album'])) {

            } else {
                $c = $this->xpdo->newQuery('galAlbum');
                $c->where(array('parent' => 0));
                $c->sortby('rank','ASC');
                $albums = $this->xpdo->getCollection('galAlbum',$c);
                /** @var galAlbum $album */
                foreach ($albums as $album) {
                    $list[] = array(
                        'id' => 'album-'.$album->get('id'),
                        'text' => $album->get('name'),
                        'cls' => 'icon-album icon-avi',
                        'type' => 'gallery-album',
                        'data' => $album->toArray(),
                        'leaf' => false,
                        'treeHandler' => 'gal-tree-handler',
                    );
                }
            }
        } else {
            $id = explode('-',$path);
            $id = (int)$id[1];

            /* get albums */

            $c = $this->xpdo->newQuery('galAlbum');
            $c->where(array('parent' => $id));
            $c->sortby('rank','ASC');
            $albums = $this->xpdo->getCollection('galAlbum',$c);
            /** @var galAlbum $album */
            foreach ($albums as $album) {
                $list[] = array(
                    'id' => 'album-'.$album->get('id'),
                    'text' => $album->get('name'),
                    'cls' => 'icon-album icon-avi',
                    'type' => 'gallery-album',
                    'data' => $album->toArray(),
                    'leaf' => false,
                    'treeHandler' => 'gal-tree-handler',
                );
            }

            /* get items */
            $c = $this->xpdo->newQuery('galItem');
            $c->innerJoin('galAlbumItem','AlbumItems');
            $c->leftJoin('galTag','Tags');
            $c->select($this->xpdo->getSelectColumns('galItem','galItem'));
            $c->select(array(
                'AlbumItems.rank',
                '(SELECT GROUP_CONCAT(Tags.tag) FROM '.$this->xpdo->getTableName('galTag').' Tags WHERE Tags.item = galItem.id) AS tags',
            ));
            $c->select(array());
            $c->where(array(
                'AlbumItems.album' => $id,
            ));
            $c->sortby('AlbumItems.rank','ASC');
            $items = $this->xpdo->getCollection('galItem',$c);
            /** @var galItem $item */
            foreach ($items as $item) {
                $itemArray = $item->toArray();
                $itemArray['image'] = $item->get('image');
                $itemArray['absoluteImage'] = $item->get('absoluteImage');
                $itemArray['relativeImage'] = $item->get('relativeImage');

                $list[] = array(
                    'id' => 'item-'.$item->get('id'),
                    'text' => $item->get('name'),
                    'cls' => 'icon-album-item icon-jpg',
                    'type' => 'gallery-item',
                    'leaf' => true,
                    'data' => $itemArray,
                    'qtip' => '<img src="'.$item->get('image').'" alt="'.$item->get('name').'" />',
                    'treeHandler' => 'gal-tree-handler',
                );
            }
        }
        return $list;
    }

    /**
     * Return a detailed list of objects in a specific path. Used for thumbnails in the Browser.
     *
     * @param string $path
     * @return array
     */
    public function getObjectsInContainer($path) {
        $properties = $this->getPropertyList();
        $list = array();
        if ($path == '/') {
            // Nothing to do?  Perhaps displaying all gallery items, or a list of albums might be good.
        }
        else {
            $id = explode('-',$path);
            $id = (int)$id[1];

            /* get items */
            $c = $this->xpdo->newQuery('galItem');
            $c->innerJoin('galAlbumItem','AlbumItems');
            $c->leftJoin('galTag','Tags');
            $c->select($this->xpdo->getSelectColumns('galItem','galItem'));
            $c->select(array(
                'AlbumItems.rank',
                '(SELECT GROUP_CONCAT(Tags.tag) FROM '.$this->xpdo->getTableName('galTag').' Tags WHERE Tags.item = galItem.id) AS tags',
            ));
            $c->select(array());
            $c->where(array(
                'AlbumItems.album' => $id,
            ));
            $c->sortby('AlbumItems.rank','ASC');
            $items = $this->xpdo->getCollection('galItem',$c);

            $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
            $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);
            $thumbHeight = $this->ctx->getOption('filemanager_thumb_height', 60);
            $thumbWidth = $this->ctx->getOption('filemanager_thumb_width', 80);

            /** @var galItem $item */
            foreach ($items as $item) {
                $itemArray = $item->toArray();
                $itemArray['image'] = $item->get('image');
                $itemArray['absoluteImage'] = $item->get('absoluteImage');
                $itemArray['relativeImage'] = $item->get('relativeImage');

                $size = @getimagesize($itemArray['image']);
                if (is_array($size)) {
                    $imageWidth = $size[0] > 800 ? 800 : $size[0];
                    $imageHeight = $size[1] > 600 ? 600 : $size[1];
                }

                /* ensure max h/w */
                if ($thumbWidth > $imageWidth) $thumbWidth = $imageWidth;
                if ($thumbHeight > $imageHeight) $thumbHeight = $imageHeight;

                $list[] = array(
                    'id' => 'item-'.$item->get('id'),
                    'name' => $item->get('name'),
                    'cls' => 'icon-album-item icon-jpg',
                    'type' => 'gallery-item',
                    'leaf' => true,
                    'image' => $item->get('image'),
                    'image_width' => $imageWidth,
                    'image_height' => $imageHeight,
                    'thumb' => $item->get('thumbnail'),
                    'thumb_width' => $thumbWidth,
                    'thumb_height' => $thumbHeight,
                    'url' => $itemArray['image'],
                    'relativeUrl' => $itemArray['relativeImage'],
                    'fullRelativeUrl' => $itemArray['relativeImage'],
                );
            }
        }
        return $list;
    }

    /**
     * Create a container at the passed location with the passed name
     *
     * @abstract
     * @param string $name
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name,$parentContainer) {
        /** @var galAlbum $album */
        $album = $this->xpdo->newObject('galAlbum');
        $album->set('name',$name);
        $album->set('createdby',$this->xpdo->user->get('id'));

        $total = $this->xpdo->getCount('galAlbum');
        $album->set('rank',$total);

        if (!($saved = $album->save())) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not create album: '.print_r($album->toArray(),true));
        }

        return $saved;
    }

    /**
     * Remove the specified container
     *
     * @abstract
     * @param string $path
     * @return boolean
     */
    public function removeContainer($path) {
        $removed = false;
        /** @var galAlbum $album */
        $album = $this->xpdo->getObject('galAlbum',$path);
        if ($album) {
            if (!($removed = $album->remove())) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not remove album.');
            }
        }
        return $removed;
    }

    /**
     * Rename a container
     *
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return boolean
     */
    public function renameContainer($oldPath,$newName) { }

    /**
     * Upload objects to a specific container
     *
     * @abstract
     * @param string $container
     * @param array $objects
     * @return boolean
     */
    public function uploadObjectsToContainer($container,array $objects = array()) { }

    /**
     * Get the contents of an object
     *
     * @abstract
     * @param string $objectPath
     * @return boolean
     */
    public function getObjectContents($objectPath) { }

    /**
     * Update the contents of a specific object
     *
     * @abstract
     * @param string $objectPath
     * @param string $content
     * @return boolean
     */
    public function updateObject($objectPath,$content) { }

    /**
     * Create an object from a path
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($objectPath,$name,$content) { }

    /**
     * Remove an object
     *
     * @abstract
     * @param string $objectPath
     * @return boolean
     */
    public function removeObject($objectPath) { }

    /**
     * Rename a file/object
     *
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameObject($oldPath,$newName) { }

    /**
     * Get the openTo path for this source, used with TV input types and Static Elements/Resources
     *
     * @param string $value
     * @param array $parameters
     * @return string
     */
    public function getOpenTo($value,array $parameters = array()) { }

    /**
     * Get the base path for this source. Only applicable to sources that are streams, used for determining
     * the base path with Static objects.
     *
     * @param string $object An optional file to find the base path with
     * @return string
     */
    public function getBasePath($object = '') { }

    /**
     * Get the base URL for this source. Only applicable to sources that are streams; used for determining the base
     * URL with Static objects and downloading objects.
     *
     * @abstract
     * @param string $object
     * @return void
     */
    public function getBaseUrl($object = '') { }

    /**
     * Get the URL for an object in this source. Only applicable to sources that are streams; used for determining
     * the base URL with Static objects and downloading objects.
     *
     * @abstract
     * @param string $object
     * @return void
     */
    public function getObjectUrl($object = '') { }

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from The location to move from
     * @param string $to The location to move to
     * @param string $point
     * @return boolean
     */
    public function moveObject($from,$to,$point = 'append') {
        $from = explode('-',$from);
        if (empty($from[1])) return false;

        $to = explode('-',$to);
        if (empty($to[1])) return false;

        /** If reordering */
        if ($from[0] == 'item' && $to[0] == 'item') {
            /** @TODO Eventually find a way to send the item; may need to refactor Revo code to do this. This current
             * code will break when we add multi-album support for items */
            /** @var galAlbumItem $fromItem */
            $fromItem = $this->xpdo->getObject('galAlbumItem',array('item' => $from[1]));
            if (empty($fromItem)) return false;
            /** @var galAlbumItem $toItem */
            $toItem = $this->xpdo->getObject('galAlbumItem',array('item' => $to[1]));
            if (empty($toItem)) return false;

            /* if dragging between items in an album */
            if ($fromItem->get('album') != $toItem->get('album')) {
                $from = implode('-',$from);
                return $this->moveObject($from,'album-'.$toItem->get('album'),$point);
            }

            switch ($point) {
                case 'below':
                    /* move FROM to below TO */
                    $newRank = $toItem->get('rank');
                    $fromItem->reorder($newRank);
                    break;
                case 'above':
                    /* move FROM to above TO */
                    $newRank = $toItem->get('rank')-1;
                    $newRank = $newRank > 0 ? $newRank : 0;
                    $fromItem->reorder($newRank);
                    break;
            }

        /** Moving item to different album */
        } else if ($from[0] == 'item' && $to[0] == 'album') {
            /** @var galItem $fromItem */
            $fromItem = $this->xpdo->getObject('galItem',$from[1]);
            if (empty($fromItem)) return false;
            /** @var galAlbum $toAlbum */
            $toAlbum = $this->xpdo->getObject('galAlbum',$to[1]);
            if (empty($toAlbum)) return false;

            $fromItem->move($toAlbum->get('id'));

        /** Moving album */
        } else if ($from[0] == 'album') {
            /** @var galAlbum $fromAlbum */
            $fromAlbum = $this->xpdo->getObject('galAlbum',$from[1]);
            if (empty($fromAlbum)) return false;
            /** @var galAlbum $toAlbum */
            $toAlbum = $this->xpdo->getObject('galAlbum',$to[1]);
            if (empty($toAlbum)) return false;

            switch ($point) {
                case 'below':
                    /* move FROM to below TO */
                    $newRank = $toAlbum->get('rank');
                    $fromAlbum->reorder($newRank);
                    break;
                case 'above':
                    /* move FROM to above TO */
                    $newRank = $toAlbum->get('rank')-1;
                    $fromAlbum->reorder($newRank);
                    break;
                case 'append':
                    /* move FROM inside TO */
                    $fromAlbum->move($toAlbum->get('id'));
                    break;
            }

        }
        return true;
    }

    /**
     * Get the name of this source type, ie, "File System"
     * @return string
     */
    public function getTypeName() {
        $this->xpdo->lexicon->load('gallery:source');
        return $this->xpdo->lexicon('gallery.source_name');
    }

    /**
     * Get a short description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('gallery:source');
        return $this->xpdo->lexicon('gallery.source_desc');
    }

    /**
     * Get the default properties for this source. Override this in your custom source driver to provide custom
     * properties for your source type.
     * @return array
     */
    public function getDefaultProperties() {
        return array(

        );
    }
}