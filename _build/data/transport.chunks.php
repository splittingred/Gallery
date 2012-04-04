<?php
/**
 * Gallery
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
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