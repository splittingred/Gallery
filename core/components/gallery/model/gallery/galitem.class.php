<?php
/**
 * Gallery
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
 *
 * Gallery is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gallery is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Gallery; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gallery
 */
/**
 * @package gallery
 */
class galItem extends xPDOSimpleObject {
    private $mediaSource = false;

    private function getMediaSource() {
        if($this->mediaSource) return $this->mediaSource;
        //get modMediaSource
        $mediaSource = $this->xpdo->getOption('gallery.mediaSource',null,1);

        $def = $this->xpdo->getObject('sources.modMediaSource',array(
            'id' => $mediaSource,
        ));

        $def->initialize();

        $this->mediaSource = $def;

        return $this->mediaSource;
    }

    public function get($k, $format = null, $formatTemplate= null) {
        switch ($k) {
            case 'thumbnail':
                $value = $this->getPhpThumbUrl();
                if (empty($format)) $format = array();
                $filename = $this->get('filename');
                if ($this->get('absolute_filename')) {
                    $format['src'] = $filename;
                } else {
                    $format['src'] = $this->getSiteUrl();
                    $format['src'] .= $this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$filename;
                }

                $ms = $this->getMediaSource();
                if($ms->getBaseUrl() != '/') {
                    $format['src'] = $ms->getBaseUrl().$this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$filename;
                }

                $url = $value.'&'.http_build_query($format,'','&');
                if ($this->xpdo->getOption('xhtml_urls',null,false)) {
                    $value = str_replace('&','&amp;',$url);
                    $value = str_replace('&amp;amp;','&amp;',$value);
                } else {
                    $value =  $url;
                }
                break;
            case 'image':
                if (empty($format)) $format = array();
                $filename = $this->get('filename');
                if ($this->get('absolute_filename')) {
                    $format['src'] = $filename;
                } else {
                    $format['src'] = $this->getSiteUrl();
                    $format['src'] .= $this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$filename;
                }

                $ms = $this->getMediaSource();
                if($ms->getBaseUrl() != '/') {
                    $format['src'] = $ms->getBaseUrl().$this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$filename;
                }

                $value = $this->getPhpThumbUrl().'&'.http_build_query($format,'','&');
                $value = $this->xpdo->getOption('xhtml_urls',null,false) ? str_replace('&','&amp;',$value) : $value;
                break;
            case 'absoluteImage':
                $siteUrl = $this->getSiteUrl();
                $value = $siteUrl.$this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$this->get('filename');

                // $ms = $this->getMediaSource();
                // if($ms->getBaseUrl() != '/') {
                //     $value = $ms->getBaseUrl().$this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$filename;
                // }

                break;
            case 'relativeImage':
                $baseUrl = $this->getOption('base_url');
                $path = $this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$this->get('filename');
                if ($baseUrl == '/') {
                    $value = ltrim($path,'/');
                } else {
                    $value = str_replace($baseUrl,'',$path);
                }

                // $ms = $this->getMediaSource(); // for absolute + relative the link NEEDS the http:// domain
                // if($ms->getBaseUrl() != '/') {
                //     $value = $ms->getBaseUrl().$this->xpdo->call('galAlbum','getFilesUrl',array(&$this->xpdo)).$baseUrl;
                // }

                break;
            case 'filesize':
                $filename = $this->xpdo->call('galAlbum','getFilesPath',array(&$this->xpdo)).$this->get('filename');
                $value = @filesize($filename);
                $value = $this->formatFileSize($value);
                break;
            case 'image_path':
                $value = $this->xpdo->call('galAlbum','getFilesPath',array(&$this->xpdo)).$this->get('filename');
                break;
            case 'base_url':
                $ms = $this->getMediaSource();
                $value='';
                if($ms->getBaseUrl() != '/') {
                    $value = $ms->getBaseUrl();
                }

                break;
            default:
                $value = parent::get($k,$format,$formatTemplate);
                break;
        }
        return $value;
    }

    public function getPath($absolute = true) {
        $path = $this->get('filename');
        if ($absolute) {
            $path = $this->xpdo->call('galAlbum','getFilesPath',array(&$this->xpdo)).$path;
        }
        return $path;
    }

    public function getPhpThumbUrl() {
        $assetsUrl = $this->xpdo->getOption('gallery.assets_url',null,$this->xpdo->getOption('assets_url',null,MODX_ASSETS_URL).'components/gallery/');
        $assetsUrl .= 'connector.php?action=web/phpthumb&ctx='.$this->xpdo->context->get('key');
        return $assetsUrl;
    }

    private function getSiteUrl() {
        $url = '';
        if ($this->xpdo->getOption('gallery.thumbs_prepend_site_url',null,false)) {
            $url = MODX_URL_SCHEME.$_SERVER['HTTP_HOST'];
        }
        return $url;
    }

    /**
     * Override set to trim string fields
     *
     * {@inheritDoc}
     */
    public function set($k, $v= null, $vType= '') {
        switch ($k) {
            case 'name':
            case 'description':
                if (is_string($v)) {
                    $v = trim($v);
                }
                break;
        }
        return parent::set($k,$v,$vType);
    }
    
    /**
     * Upload a file to an album
     *
     * @var array $file
     * @var int $albumId
     * @return boolean
     */
    public function upload($file,$albumId) {
        if (empty($file) || empty($file['tmp_name']) || empty($file['name'])) return false;
        if (in_array($this->get('id'),array(0,null,''))) return false;
        /** @var galAlbum $album */
        $album = $this->xpdo->getObject('galAlbum',$albumId);
        if (empty($album)) return false;

        $fileName = $album->uploadItem($this,$file['tmp_name'], $file['name'], $this->getMediaSource());
        if (empty($fileName)) {
            return false;
        }
        $this->set('filename',$fileName);
        return true;
    }

    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('createdon')) {
            $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        if ($this->isNew() && !$this->get('createdby')) {
            if (!empty($this->xpdo->user) && $this->xpdo->user instanceof modUser) {
                if ($this->xpdo->user->isAuthenticated()) {
                    $this->set('createdby',$this->xpdo->user->get('id'));
                }
            }
        }
        $saved= parent :: save($cacheFlag);
        if ($saved) {
            if ($this->xpdo->getCacheManager()) {
                $this->xpdo->cacheManager->delete('gallery/item/list/');
            }
        }
        return $saved;
    }

    public function remove(array $ancestors = array()) {
        $filename = $this->get('filename');
        if (!empty($filename)) {
            $filename = $this->xpdo->call('galAlbum','getFilesPath',array(&$this->xpdo)).$filename;
            $filename = str_ireplace(MODX_BASE_PATH, '', $filename);
            $ms = $this->getMediaSource();
            if (!@$ms->removeObject($filename)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] An error occurred while trying to remove the attachment file at: '.$filename);
            }
        }
        return parent::remove($ancestors);
    }

    protected function formatFileSize($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function getSize() {
        $imagePath = $this->get('image_path');
        $size = @getimagesize($imagePath);
        if (is_array($size)) {
            $this->set('image_width',$size[0]);
            $this->set('image_height',$size[1]);
            $this->set('image_type',$size[2]);
        }
    }

    /**
     * Move the item to a new album
     *
     * @param int|galAlbum $album
     * @return boolean
     */
    public function move($album) {
        /** @var galAlbum $newAlbum */
        $newAlbum = is_object($album) && $album instanceof galAlbum ? $album : $this->xpdo->getObject('galAlbum',$album);
        if (empty($newAlbum)) return false;

        /** @var galAlbumItem $albumItem */
        $albumItem = $this->xpdo->getObject('galAlbumItem',array(
            'item' => $this->get('id'),
        ));
        if (empty($albumItem)) return false;

        /* set new related object */
        $oldRank = $albumItem->get('rank');
        $oldAlbum = $albumItem->get('album');
        $albumItem->set('album',$newAlbum->get('id'));
        $albumItem->set('rank',$this->xpdo->getCount('galAlbumItem',array('album' => $newAlbum->get('id'))));
        if (!$albumItem->save()) {
            return false;
        }

        /* fix old album ranks */
        $sql = 'UPDATE '.$this->xpdo->getTableName('galAlbumItem').' SET rank = rank - 1 WHERE rank >= '.$oldRank.' AND album = '.$oldAlbum;
        $this->xpdo->exec($sql);

        /* move actual file */
        $oldPath = $this->getPath();
        $newPath = $newAlbum->getPath().basename($oldPath);
        if ($oldPath != $newPath) {
            if (!@copy($oldPath,$newPath)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not move Item from '.$oldPath.' to '.$newPath);
                return false;
            }
            @unlink($oldPath);
        }
        $this->set('filename',$newAlbum->get('id').'/'.basename($oldPath));
        $this->save();
        return true;
    }

    public static function getList(modX &$modx,array $scriptProperties = array()) {
		$sort = $modx->getOption('sort',$scriptProperties,'rank');		
        $cacheKey = 'gallery/item/list/'.md5(serialize($scriptProperties));
        if ($modx->getCacheManager() && $cache = $modx->cacheManager->get($cacheKey)) {
            $items = array();
            foreach ($cache['items'] as $data) {
                /** @var galItem $item */
                $item = $modx->newObject('galItem');
                $item->fromArray($data,'',true,true);
                $items[] = $item;
            }
			
			if (in_array(strtolower($sort),array('random','rand()','rand'))) {
			shuffle($items);
			}
            
			$data = array(
                'items' => $items,
                'total' => $cache['total'],
                'album' => $cache['album'],
            );
        } else {

            $album = $modx->getOption('album',$scriptProperties,false);
            $tag = $modx->getOption('tag',$scriptProperties,'');
            $limit = $modx->getOption('limit',$scriptProperties,0);
            $start = $modx->getOption('start',$scriptProperties,0);
            /* Fix to make it work with getPage which uses "offset" instead of "start" */
            $offset = $modx->getOption('offset',$scriptProperties,0);
            if ($offset > 0) { $start = $offset; }
            $sortAlias = $modx->getOption('sortAlias',$scriptProperties,'galItem');
            if ($sort == 'rank') $sortAlias = 'AlbumItems';
            $dir = $modx->getOption('dir',$scriptProperties,'ASC');
            $showInactive = $modx->getOption('showInactive',$scriptProperties,false);
            $activeAlbum = array(
                'id' => '',
                'name' => '',
                'description' => '',
            );

            $tagc = $modx->newQuery('galTag');
            $tagc->setClassAlias('TagsJoin');
            $tagc->select('GROUP_CONCAT('.$modx->getSelectColumns('galTag','TagsJoin','',array('tag')).')');
            $tagc->where($modx->getSelectColumns('galTag','TagsJoin','',array('item')).' = '.$modx->getSelectColumns('galItem','galItem','',array('id')));
            $tagc->prepare();
            $tagSql = $tagc->toSql();

            $c = $modx->newQuery('galItem');
            $c->innerJoin('galAlbumItem','AlbumItems');
            $c->innerJoin('galAlbum','Album',$modx->getSelectColumns('galAlbumItem','AlbumItems','',array('album')).' = '.$modx->getSelectColumns('galAlbum','Album','',array('id')));

            /* pull by album */
            if (!empty($album)) {
                $albumField = is_numeric($album) ? 'id' : 'name';

                $albumWhere = $albumField == 'name' ? array('name' => $album) : $album;
                /** @var galAlbum $album */
                $album = $modx->getObject('galAlbum',$albumWhere);
                if (empty($album)) return '';
                $c->where(array(
                    'Album.'.$albumField => $album->get($albumField),
                ));
                $activeAlbum['id'] = $album->get('id');
                $activeAlbum['name'] = $album->get('name');
                $activeAlbum['description'] = $album->get('description');
                $activeAlbum['year'] = $album->get('year');
                unset($albumWhere,$albumField);
            }
            if (!empty($tag)) { /* pull by tag */
                $c->innerJoin('galTag','Tags');
                $c->where(array(
                    'Tags.tag' => $tag,
                ));
                if (empty($album)) {
                    $activeAlbum['id'] = 0;
                    $activeAlbum['name'] = $tag;
                    $activeAlbum['description'] = '';
                }
            }
            $c->where(array(
                'galItem.mediatype' => $modx->getOption('mediatype',$scriptProperties,'image'),
            ));
            if (!$showInactive) {
                $c->where(array(
                    'galItem.active' => true,
                ));
            }

            $count = $modx->getCount('galItem',$c);
            $c->select($modx->getSelectColumns('galItem','galItem'));
            $c->select(array(
                '('.$tagSql.') AS tags',
            ));
            if (in_array(strtolower($sort),array('random','rand()','rand'))) {
                $c->sortby('RAND()',$dir);
            } else {
                $c->sortby($sortAlias.'.'.$sort,$dir);
            }
            if (!empty($limit)) $c->limit($limit,$start);
            $items = $modx->getCollection('galItem',$c);

            $data = array(
                'items' => $items,
                'total' => $count,
                'album' => $activeAlbum,
            );

            $cache = array(
                'items' => array(),
                'total' => $count,
                'album' => $activeAlbum,
            );
            /** @var galItem $item */
            foreach ($items as $item) {
                $cache['items'][] = $item->toArray('',true);
            }

            $modx->cacheManager->set($cacheKey,$cache);
        }
        return $data;
    }
}
