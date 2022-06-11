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
 * Input TV render for Gallery's GalleryAlbumList TV
 *
 * @package gallery
 * @subpackage tv
 */
class GalleryAlbumListInputRender extends modTemplateVarInputRender
{
    /**
     * Return the template path to load
     *
     * @return string
     */
    public function getTemplate()
    {
        $corePath = $this->modx->getOption('gallery.core_path', null, $this->modx->getOption('core_path') . 'components/gallery/');
        return $corePath . 'elements/tv/galleryalbumlist.input.tpl';
    }

    /**
     * Get lexicon topics
     *
     * @return array
     */
    public function getLexiconTopics()
    {
        return ['gallery:default'];
    }

    /**
     * Process Input Render
     *
     * @param string $value
     * @param array $params
     * @return void
     */
    public function process($value, array $params = [])
    {
        $this->setPlaceholder('base_url', $this->modx->getOption('base_url'));

        $corePath = $this->modx->getOption('gallery.core_path',null,$this->modx->getOption('core_path').'components/gallery/');
        $this->modx->addPackage('gallery',$corePath.'model/');

        if (empty($params)) $params = array();

        /* setup default properties */
        $sort = $this->modx->getOption('sort',$params,'rank');
        $dir = $this->modx->getOption('dir',$params,'ASC');
        $limit = (int)$this->modx->getOption('limit',$params,0);
        $start = (int)$this->modx->getOption('start',$params,0);
        $showNone = (boolean)$this->modx->getOption('showNone',$params,true);
        $showCover = (boolean)$this->modx->getOption('showCover',$params,true);
        $parent = $this->modx->getOption('parent',$params,'');
        $subchilds = $this->modx->getOption('subchilds',$params,false);

        /* get albums */
        $c = $this->modx->newQuery('galAlbum');
        if ($parent != '') {
            $c->where(array(
                'parent' => (int)$parent,
            ));
        }
        $c->where(array(
            'active' => 1,
        ));
        $c->sortby($sort,$dir);
        if ($limit > 0) {
            $c->limit($limit,$start);
        }
        $albums = $this->modx->getCollection('galAlbum',$c);

        if(($parent !== '') && $subchilds){
                $album = $this->modx->getObject('galAlbum',(int)$parent);
                if($album !== null){
                    $albumsWithSubs = array();
                    $gallery = new Gallery($this->modx);
                    $gallery->getAllChilds($album, $albumsWithSubs, $sort, $dir, -1);

                    $albums = $albumsWithSubs;
                }
        }

        /* setup thumb properties */
        $thumbProperties = array(
            'w' => (int)$this->modx->getOption('thumbWidth',$params,40),
            'h' => (int)$this->modx->getOption('thumbHeight',$params,40),
            'zc' => (boolean)$this->modx->getOption('thumbZoomCrop',$params,1),
            'far' => (string)$this->modx->getOption('thumbFar',$params,'C'),
            'q' => (int)$this->modx->getOption('thumbQuality',$params,90),
            'f' => 'png',
        );

        /* iterate over albums */
        $coverItem = false;
        $list = array();
        if ($showNone) {
            $list[] = array('','('.$this->modx->lexicon('none').')');
        }
        foreach ($albums as $album) {
            if ($showCover) {
                $albumArray = $album->toArray();
                $c = $this->modx->newQuery('galItem');
                $c->innerJoin('galAlbumItem','AlbumItems');
                $c->where(array(
                    'AlbumItems.album' => $album->get('id'),
                ));
                $c->sortby('rank','ASC');
                $c->limit(1);
                $coverItem = $this->modx->getObject('galItem',$c);
            }

            /** @noinspection SuspiciousAssignmentsInspection */
            $albumArray = [
                $album->get('id'),
                $album->get('name'),
                $album->get('description'),
            ];
            if ($showCover && $coverItem) {
                $albumArray[] = $coverItem->get('thumbnail',$thumbProperties);
            }
            $list[] = $albumArray;
        }
        $this->setPlaceholder('list', $this->modx->toJSON($list));
    }
}

return 'GalleryAlbumListInputRender';
