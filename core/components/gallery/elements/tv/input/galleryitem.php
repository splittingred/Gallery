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
 * Input TV render for Gallery's GalleryItem TV
 *
 * @package gallery
 * @subpackage tv
 */
$modx->lexicon->load('tv_widget','gallery:default');
$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$modx->setLogTarget('ECHO');
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (!empty($this->value)) {
    $data = $modx->fromJSON($this->value);
    if (is_array($data)) {
        $item = $modx->getObject('galItem',$data['id']);
        if ($item) {
            $item->getSize();
            $itemArray = $item->toArray('',true,true);
            $pt = $item->getPhpThumbUrl();
            $data['src'] = $item->get('absoluteImage');
            $data['rotate'] = !empty($data['rotate']) ? $data['rotate'] : 0;
            $data['watermark-text'] = !empty($data['watermark-text']) ? $data['watermark-text'] : '';
            $data['watermark-text-position'] = !empty($data['watermark-text-position']) ? $data['watermark-text-position'] : 'BL';
            $js = $modx->toJSON($data);
            $modx->smarty->assign('itemjson',$js);
        }
    }
}

return $modx->smarty->fetch($corePath.'elements/tv/galleryitem.input.tpl');