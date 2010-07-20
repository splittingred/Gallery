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
 * Properties for the GalleryItem snippet.
 *
 * @package gallery
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'id',
        'desc' => 'The ID of the item to display.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'toPlaceholders',
        'desc' => 'If true, will set the properties of the Item to placeholders. If false, will use the tpl property to output a chunk.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'toPlaceholdersPrefix',
        'desc' => 'Optional. The prefix to add to placeholders set by this snippet. Only works if toPlaceholders is true.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galitem',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'Name of a chunk to use when toPlaceholders is set to false.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItem',
    ),
    array(
        'name' => 'albumTpl',
        'desc' => 'Name of a chunk to use for each album that is listed for the Item.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemAlbum',
    ),
    array(
        'name' => 'albumSeparator',
        'desc' => 'A string separator for each album listed for the Item.',
        'type' => 'textfield',
        'options' => '',
        'value' => ',&nbsp;',
    ),
    array(
        'name' => 'albumRequestVar',
        'desc' => 'The REQUEST var to use when linking albums.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galAlbum',
    ),
    array(
        'name' => 'tagTpl',
        'desc' => 'Name of a chunk to use for each tag that is listed for the Item.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galItemTag',
    ),
    array(
        'name' => 'tagSeparator',
        'desc' => 'A string separator for each tag listed for the Item.',
        'type' => 'textfield',
        'options' => '',
        'value' => ',&nbsp;',
    ),
    array(
        'name' => 'tagSortDir',
        'desc' => 'A the direction to sort the tags listed for the Item.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'DESC',
    ),
    array(
        'name' => 'tagRequestVar',
        'desc' => 'The REQUEST var to use when linking tags.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'galTag',
    ),
    array(
        'name' => 'thumbWidth',
        'desc' => 'The max width of the generated thumbnail, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbHeight',
        'desc' => 'The max height of the generated thumbnail, in pixels.',
        'type' => 'textfield',
        'options' => '',
        'value' => '100',
    ),
    array(
        'name' => 'thumbZoomCrop',
        'desc' => 'Whether or not to use zoom cropping for the thumbnail.',
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
        'name' => 'imageWidth',
        'desc' => 'If being used by a plugin, the max width of the generated image.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'imageHeight',
        'desc' => 'If being used by a plugin, the max height of the generated image.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'imageZoomCrop',
        'desc' => 'Whether or not to use zoom cropping for the image.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => 0,
    ),
    array(
        'name' => 'imageFar',
        'desc' => 'The "far" value for phpThumb for the image, for aspect ratio zooming.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);