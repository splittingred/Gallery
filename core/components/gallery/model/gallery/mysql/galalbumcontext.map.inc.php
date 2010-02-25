<?php
/**
 * @package gallery
 */
$xpdo_meta_map['galAlbumContext']= array (
  'package' => 'gallery',
  'table' => 'gallery_album_contexts',
  'fields' => 
  array (
    'album' => 0,
    'context_key' => 'web',
  ),
  'fieldMeta' => 
  array (
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
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'web',
      'index' => 'index',
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
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
