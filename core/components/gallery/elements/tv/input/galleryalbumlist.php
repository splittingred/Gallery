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
$modx->lexicon->load('tv_widget','gallery:default');
$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (empty($params)) $params = array();

/* setup default properties */
$sort = $modx->getOption('sort',$params,'rank');
$dir = $modx->getOption('dir',$params,'ASC');
$limit = (int)$modx->getOption('limit',$params,0);
$start = (int)$modx->getOption('start',$params,0);
$showNone = (boolean)$modx->getOption('showNone',$params,true);
$showCover = (boolean)$modx->getOption('showCover',$params,true);
$parent = $modx->getOption('parent',$params,'');
$subchilds = $modx->getOption('subchilds',$params,false);

/* get albums */
$c = $modx->newQuery('galAlbum');
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
$albums = $modx->getCollection('galAlbum',$c);

if(($parent != '') && ($subchilds == true)){
        $album = $modx->getObject('galAlbum',(int)$parent);
        if($album != null){
            $albumsWithSubs = array();
            $gallery = new Gallery($modx);
            $gallery->getAllChilds($album, $albumsWithSubs, $sort, $dir, -1);
            
            $albums = $albumsWithSubs;
        }
}

/* setup thumb properties */
$thumbProperties = array(
    'w' => (int)$modx->getOption('thumbWidth',$params,40),
    'h' => (int)$modx->getOption('thumbHeight',$params,40),
    'zc' => (boolean)$modx->getOption('thumbZoomCrop',$params,1),
    'far' => (string)$modx->getOption('thumbFar',$params,'C'),
    'q' => (int)$modx->getOption('thumbQuality',$params,90),
    'f' => 'png',
);

/* iterate over albums */
$coverItem = false;
$list = array();
if ($showNone) {
    $list[] = array('','('.$modx->lexicon('none').')');
}
foreach ($albums as $album) {
    if ($showCover) {
        $albumArray = $album->toArray();
        $c = $modx->newQuery('galItem');
        $c->innerJoin('galAlbumItem','AlbumItems');
        $c->where(array(
            'AlbumItems.album' => $album->get('id'),
        ));
        $c->sortby('rank','ASC');
        $c->limit(1);
        $coverItem = $modx->getObject('galItem',$c);
    }

    $albumArray = array(
        $album->get('id'),
        $album->get('name'),
        $album->get('description'),
    );
    if ($showCover && $coverItem) {
        $albumArray[] = $coverItem->get('thumbnail',$thumbProperties);
    }
    $list[] = $albumArray;
}
$modx->smarty->assign('list',$modx->toJSON($list));

return $modx->smarty->fetch($corePath.'elements/tv/galleryalbumlist.input.tpl');