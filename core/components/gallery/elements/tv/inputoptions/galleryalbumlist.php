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
 * Input TV render for Gallery's GalleryAlbumList TV
 *
 * @package gallery
 * @subpackage tv
 */
$modx->lexicon->load('tv_widget','gallery:tvprops');
$modx->smarty->assign('base_url',$modx->getOption('base_url'));

$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

/* get TV input properties specific language strings */
$lang = $modx->lexicon->fetch('galtv.',true);
$modx->smarty->assign('gl',$lang);

return $modx->smarty->fetch($corePath.'elements/tv/galleryalbumlist.inputproperties.tpl');