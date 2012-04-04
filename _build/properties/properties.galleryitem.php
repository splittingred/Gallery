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
 * Properties for the GalleryItem snippet.
 *
 * @package gallery
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'id',
        'desc' => 'galleryitem.id_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'toPlaceholders',
        'desc' => 'galleryitem.toplaceholders_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'toPlaceholdersPrefix',
        'desc' => 'galleryitem.toplaceholdersprefix_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galitem',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'galleryitem.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItem',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumTpl',
        'desc' => 'galleryitem.albumtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemAlbum',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumSeparator',
        'desc' => 'galleryitem.albumseparator_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => ',&nbsp;',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumRequestVar',
        'desc' => 'galleryitem.albumrequestvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbum',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tagTpl',
        'desc' => 'galleryitem.tagtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemTag',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tagSeparator',
        'desc' => 'galleryitem.tagseparator_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => ',&nbsp;',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tagSortDir',
        'desc' => 'galleryitem.tagsortdir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'DESC',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tagRequestVar',
        'desc' => 'galleryitem.tagrequestvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galTag',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'galleryitem.thumbwidth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'galleryitem.thumbheight_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'galleryitem.thumbzoomcrop_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbFar',
        'desc' => 'galleryitem.thumbfar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'C',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbQuality',
        'desc' => 'galleryitem.thumbquality_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbProperties',
        'desc' => 'galleryitem.thumbproperties_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageWidth',
        'desc' => 'galleryitem.imagewidth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageHeight',
        'desc' => 'galleryitem.imageheight_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageZoomCrop',
        'desc' => 'galleryitem.imagezoomcrop_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => 0,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageFar',
        'desc' => 'galleryitem.imagefar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageQuality',
        'desc' => 'galleryitem.imagequality_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageProperties',
        'desc' => 'galleryitem.imageproperties_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
);

return $properties;