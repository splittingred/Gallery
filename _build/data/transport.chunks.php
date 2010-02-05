<?php
/**
 * @package gallery
 * @subpackage build
 */
$chunks = array();

$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'galAlbumRowTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/galalbumrowtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'galItemThumb',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/galitemthumb.chunk.tpl'),
    'properties' => '',
),'',true,true);


/*
$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'gal',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/gal.chunk.tpl'),
    'properties' => '',
),'',true,true);
 */

return $chunks;