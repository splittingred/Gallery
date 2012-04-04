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
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Gallery',
    'description' => '',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.gallery.php'),
),'',true,true);
$properties = include $sources['build'].'properties/properties.gallery.php';
$snippets[0]->setProperties($properties);
unset($properties);

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'GalleryAlbums',
    'description' => '',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.galleryalbums.php'),
),'',true,true);
$properties = include $sources['build'].'properties/properties.galleryalbums.php';
$snippets[1]->setProperties($properties);
unset($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'GalleryItem',
    'description' => '',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.galleryitem.php'),
),'',true,true);
$properties = include $sources['build'].'properties/properties.galleryitem.php';
$snippets[2]->setProperties($properties);
unset($properties);

return $snippets;