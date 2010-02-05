<?php
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