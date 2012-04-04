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
 * Properties for the Gallery snippet.
 *
 * @package gallery
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'album',
        'desc' => 'gallery.album_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tag',
        'desc' => 'gallery.tag_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'plugin',
        'desc' => 'gallery.plugin_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'pluginPath',
        'desc' => 'gallery.pluginpath_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbTpl',
        'desc' => 'gallery.thumbtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemThumb',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'containerTpl',
        'desc' => 'gallery.containertpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'itemCls',
        'desc' => 'gallery.itemcls_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'gal-item',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'itemCls',
        'desc' => 'gallery.activecls_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'gal-item-active',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'gallery.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'gallery.thumbwidth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'gallery.thumbheight_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'gallery.thumbzoomcrop_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbFar',
        'desc' => 'gallery.thumbfar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'C',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbQuality',
        'desc' => 'gallery.thumbquality_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'thumbProperties',
        'desc' => 'gallery.thumbproperties_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'linkToImage',
        'desc' => 'gallery.linktoimage_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageGetParam',
        'desc' => 'gallery.imagegetparam_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItem',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageWidth',
        'desc' => 'gallery.imagewidth_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 500,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageHeight',
        'desc' => 'gallery.imageheight_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 500,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageZoomCrop',
        'desc' => 'gallery.imagezoomcrop_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageFar',
        'desc' => 'gallery.imagefar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageQuality',
        'desc' => 'gallery.imagequality_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'imageProperties',
        'desc' => 'gallery.imageproperties_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'sort',
        'desc' => 'gallery.sort_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rank',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'dir',
        'desc' => 'gallery.dir_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'gallery.limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'start',
        'desc' => 'gallery.start_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'showInactive',
        'desc' => 'gallery.showinactive_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'checkForRequestAlbumVar',
        'desc' => 'gallery.checkforrequestalbumvar_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'albumRequestVar',
        'desc' => 'gallery.albumrequestvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbum',
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'checkForRequestTagVar',
        'desc' => 'gallery.checkforrequesttagvar_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'gallery:properties',
    ),
    array(
        'name' => 'tagRequestVar',
        'desc' => 'gallery.tagrequestvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galTag',
    ),
    array(
        'name' => 'useCss',
        'desc' => 'gallery.usecss_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
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