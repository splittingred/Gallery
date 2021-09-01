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
 * Sort two items
 *
 * @package gallery
 * @subpackage processors
 */
$source = $modx->getObject('galAlbumItem',array(
    'album' => $scriptProperties['album'],
    'item' => $scriptProperties['source'],
));
if (empty($source)) return $modx->error->failure();

$target = $modx->getObject('galAlbumItem',array(
    'album' => $scriptProperties['album'],
    'item' => $scriptProperties['target'],
));
if (empty($target)) return $modx->error->failure();

if ($source->get('rank') < $target->get('rank')) {
    $modx->exec("
        UPDATE {$modx->getTableName('galAlbumItem')}
            SET rank = rank - 1
        WHERE
            album = ".$scriptProperties['album']."
        AND rank < {$target->get('rank')}
        AND rank > {$source->get('rank')}
        AND rank > 0
    ");
    $newRank = $target->get('rank')-1;
} else {
    $modx->exec("
        UPDATE {$modx->getTableName('galAlbumItem')}
            SET rank = rank + 1
        WHERE
            album = ".$scriptProperties['album']."
        AND rank >= {$target->get('rank')}
        AND rank < {$source->get('rank')}
    ");
    $newRank = $target->get('rank');
}
$source->set('rank',$newRank);
$source->save();

return $modx->error->success();
