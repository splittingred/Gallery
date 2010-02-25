<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
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