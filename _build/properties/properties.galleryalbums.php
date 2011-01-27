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
 * Properties for the GalleryAlbums snippet.
 *
 * @package gallery
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'rowTpl',
        'desc' => 'The Chunk to use for each album row.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbumRowTpl',
    ),
    array(
        'name' => 'sort',
        'desc' => 'The field to sort the results by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'createdon',
    ),
    array(
        'name' => 'dir',
        'desc' => 'The direction to sort the results by.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'DESC',
    ),
    array(
        'name' => 'limit',
        'desc' => 'If set to non-zero, will limit the number of results returned.',
        'type' => 'textfield',
        'options' => '',
        'value' => '10',
    ),
    array(
        'name' => 'start',
        'desc' => 'The index to start from in the results.',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'If not empty, will set the output to a placeholder with this value.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'showInactive',
        'desc' => 'If true, will show inactive galleries as well.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'prominentOnly',
        'desc' => 'If true, will only display albums marked with a "prominent" status.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'parent',
        'desc' => 'Grab only the albums with a parent album with this ID.',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
    ),
    array(
        'name' => 'showAll',
        'desc' => 'If true, will show all albums regardless of their parent.',
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
        'name' => 'albumCoverSort',
        'desc' => 'The field which to use when sorting to get the Album Cover. To get the first image, use "rank". To get a random image, use "random".',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rank',
    ),
    array(
        'name' => 'albumCoverSortDir',
        'desc' => 'The direction to use when sorting to get the Album Cover. Accepts "ASC" or "DESC".',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ASC',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'The width of the generated album cover thumbnail, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'The height of the generated album cover thumbnail, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'Whether or not the album coverthumbnail will be zoom-cropped.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'thumbFar',
        'desc' => 'The "far" value for phpThumb for the album cover thumbnail, for aspect ratio zooming.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => 'C',
    ),
    array(
        'name' => 'thumbQuality',
        'desc' => 'The "q" value for phpThumb for the album cover thumbnail, for quality.',
        'type' => 'textfield',
        'options' => '',
        'value' => 90,
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