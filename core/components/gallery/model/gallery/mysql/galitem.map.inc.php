<?php
/**
 * @package gallery
 */
$xpdo_meta_map['galItem']= array (
  'package' => 'gallery',
  'table' => 'items',
  'fields' => 
  array (
    'name' => '',
    'filename' => '',
    'description' => '',
    'mediatype' => 'image',
    'createdon' => NULL,
    'createdby' => 0,
    'active' => 0,
    'duration' => '',
    'streamer' => '',
    'watermark_pos' => 'tl',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'filename' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'text',
      'null' => false,
      'default' => '',
    ),
    'mediatype' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => 'image',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'duration' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'streamer' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'watermark_pos' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
      'default' => 'tl',
    ),
  ),
  'aggregates' => 
  array (
    'CreatedBy' => 
    array (
      'class' => 'modUser',
      'local' => 'createdby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'AlbumItems' => 
    array (
      'class' => 'galAlbumItem',
      'local' => 'id',
      'foreign' => 'item',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Tags' => 
    array (
      'class' => 'galTag',
      'local' => 'id',
      'foreign' => 'item',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
