<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
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
$plugins = array();

$plugins[0]= $modx->newObject('modPlugin');
$plugins[0]->fromArray(array(
    'id' => 0,
    'name' => 'GalleryCustomTV',
    'description' => '',
    'plugincode' => file_get_contents($sources['plugins'] . 'gallerycustomtv.plugin.php'),
),'',true,true);
$events = include $sources['data'].'events/events.gallerycustomtv.php';
if (is_array($events) && !empty($events)) {
    $modx->log(modX::LOG_LEVEL_INFO,'Added '.count($events).' events to GalleryCustomTV plugin.');
    $plugins[0]->addMany($events);
}
unset($events);

return $plugins;