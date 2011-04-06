<?php
/**
 * Gallery
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
 * Properties for the GalleryAlbums snippet.
 *
 * @package gallery
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'rowTpl',
        'desc' => 'galleryalbums.rowtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbumRowTpl',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'rowCls',
        'desc' => 'galleryalbums.rowcls_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'sort',
        'desc' => 'galleryalbums.sort_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rank',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'dir',
        'desc' => 'galleryalbums.dir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'DESC',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'galleryalbums.limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '10',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'start',
        'desc' => 'galleryalbums.start_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'galleryalbums.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'showInactive',
        'desc' => 'galleryalbums.showinactive_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'prominentOnly',
        'desc' => 'galleryalbums.prominentonly_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'parent',
        'desc' => 'galleryalbums.parent_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'showAll',
        'desc' => 'galleryalbums.showall_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'showName',
        'desc' => 'galleryalbums.showname_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumRequestVar',
        'desc' => 'galleryalbums.albumrequestvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbum',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumCoverSort',
        'desc' => 'galleryalbums.albumcoversort_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rank',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumCoverSortDir',
        'desc' => 'galleryalbums.albumcoversortdir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'galleryalbums.thumbwidth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'galleryalbums.thumbheight_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'galleryalbums.thumbzoomcrop_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbFar',
        'desc' => 'galleryalbums.thumbfar_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => 'C',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbQuality',
        'desc' => 'galleryalbums.thumbquality_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbProperties',
        'desc' => 'galleryalbums.thumbproperties_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
/*
    array(
        'name' => '',
        'desc' => '',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    */
);

return $properties;