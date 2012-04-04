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
 * Input TV render for Gallery's GalleryItem TV
 *
 * @package gallery
 * @subpackage tv
 */
$modx->lexicon->load('tv_widget','gallery:default');
$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (!empty($this->value)) {
    $data = $modx->fromJSON($this->value);
    if (is_array($data) && !empty($data['gal_id'])) {
        $item = $modx->getObject('galItem',$data['gal_id']);
        if ($item) {
            $item->getSize();
            $data = array_merge($item->toArray('gal_',true,true),$data);
            $pt = $item->getPhpThumbUrl();
            $data['gal_id'] = $item->get('id');
            $data['gal_src'] = $item->get('absoluteImage');
            $data['gal_rotate'] = !empty($data['gal_rotate']) ? $data['gal_rotate'] : 0;
            $data['gal_watermark-text'] = !empty($data['gal_watermark-text']) ? $data['gal_watermark-text'] : '';
            $data['gal_watermark-text-position'] = !empty($data['gal_watermark-text-position']) ? $data['gal_watermark-text-position'] : 'BL';
            $js = $modx->toJSON($data);
            $modx->smarty->assign('itemjson',$js);
        }
    }
}

return $modx->smarty->fetch($corePath.'elements/tv/galleryitem.input.tpl');