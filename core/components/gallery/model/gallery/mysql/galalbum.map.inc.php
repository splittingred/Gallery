<?php
/**
 * @package gallery
 */
$xpdo_meta_map['galAlbum']= array (
  'package' => 'gallery',
  'table' => 'albums',
  'fields' => 
  array (
    'name' => '',
    'description' => '',
    'createdon' => NULL,
    'createdby' => 0,
    'rank' => 0,
    'active' => 0,
    'prominent' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
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
    'prominent' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
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
      'foreign' => 'album',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Contexts' => 
    array (
      'class' => 'galAlbumContext',
      'local' => 'id',
      'foreign' => 'album',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
