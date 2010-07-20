<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
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
        'desc' => 'Will load only items from this album. Can be either the name or ID of the Album.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tag',
        'desc' => 'Will load only items with this tag.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'plugin',
        'desc' => 'The name of a plugin to use for front-end displaying. Please see the official docs for a list of available plugins.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'thumbTpl',
        'desc' => 'The Chunk to use as a tpl for each thumbnail.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemThumb',
    ),
    array(
        'name' => 'containerTpl',
        'desc' => 'An optional chunk to wrap the output in.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'itemCls',
        'desc' => 'The CSS class for each thumbnail.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'gal-item',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'If set, will set the output to a placeholder of this value, and the snippet call will output nothing.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'The width of the generated thumbnails, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'The height of the generated thumbnails, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'Whether or not the thumbnail will be zoom-cropped.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'thumbFar',
        'desc' => 'The "far" value for phpThumb for the thumbnail, for aspect ratio zooming.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => 'C',
    ),
    array(
        'name' => 'linkToImage',
        'desc' => 'If true, will link directly to the image. If false, will append ',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'imageGetParam',
        'desc' => 'The GET param to use when not linking directly to an image. Make sure this matches the getParam property in the GalleryItem snippet call.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItem',
    ),
    array(
        'name' => 'imageWidth',
        'desc' => 'If being used by a plugin, the width of the currently on-display image.',
        'type' => 'textfield',
        'options' => '',
        'value' => 500,
    ),
    array(
        'name' => 'imageHeight',
        'desc' => 'If being used by a plugin, the height of the currently on-display image.',
        'type' => 'textfield',
        'options' => '',
        'value' => 500,
    ),
    array(
        'name' => 'imageZoomCrop',
        'desc' => 'If being used by a plugin, whether or not the currently on-display image will be zoom-cropped.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'imageFar',
        'desc' => 'The "far" value for phpThumb for the image, for aspect ratio zooming.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'sort',
        'desc' => 'The field to sort images by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rank',
    ),
    array(
        'name' => 'dir',
        'desc' => 'The direction to sort images by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
    ),
    array(
        'name' => 'limit',
        'desc' => 'If set to non-zero, will only show X number of items.',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
    ),
    array(
        'name' => 'start',
        'desc' => 'The index to start grabbing from when limiting the number of items. Similar to an SQL order by start clause.',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
    ),
    array(
        'name' => 'showInactive',
        'desc' => 'If true, will also display inactive images.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'checkForRequestAlbumVar',
        'desc' => 'If true, if a REQUEST var of "album" is found, will use that as the album property for the snippet.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'albumRequestVar',
        'desc' => 'If checkForRequestAlbumVar is set to true, will look for a REQUEST var with this name to select the album.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbum',
    ),
    array(
        'name' => 'checkForRequestTagVar',
        'desc' => 'If true, if a REQUEST var of "tag" is found, will use that as the tag property for the snippet.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'tagRequestVar',
        'desc' => 'If checkForRequestTagVar is set to true, will look for a REQUEST var with this name to select the tag.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galTag',
    ),
    array(
        'name' => 'useCss',
        'desc' => 'If true, will use the CSS provided by the Gallery snippet. Set to false to not load any Gallery-provided CSS.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
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