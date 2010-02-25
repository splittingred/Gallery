<?php
/**
 * @package gallery
 */
$xpdo_meta_map['galTag']= array (
  'package' => 'gallery',
  'table' => 'gallery_tags',
  'fields' => 
  array (
    'item' => 0,
    'tag' => '',
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
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
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
