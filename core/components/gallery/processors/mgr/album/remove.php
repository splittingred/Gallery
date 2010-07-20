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
 * @subpackage processors
 */
/* get board */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
$album = $modx->getObject('galAlbum',$_REQUEST['id']);
if (!$album) return $modx->error->failure($modx->lexicon('gallery.album_err_nf'));

if ($album->remove() == false) {
    return $modx->error->failure($modx->lexicon('gallery.album_err_remove'));
}

/* output */
return $modx->error->success('',$album);