<?php
/**
 * @package gallery
 */
$xpdo_meta_map['galAlbumItem']= array (
  'package' => 'gallery',
  'table' => 'gallery_album_items',
  'fields' => 
  array (
    'item' => 0,
    'album' => 0,
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'item' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'album' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Album' => 
    array (
      'class' => 'galAlbum',
      'local' => 'album',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Item' => 
    array (
      'class' => 'galItem',
      'local' => 'item',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
