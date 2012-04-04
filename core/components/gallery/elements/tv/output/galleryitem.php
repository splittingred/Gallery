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
 * Output TV render for Gallery's GalleryItem TV
 *
 * @package gallery
 * @subpackage tv
 */
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (!empty($value) && $value != '{}') {
    $data = $modx->fromJSON($value);
    if (empty($data)) return '';

    $item = $modx->getObject('galItem',$data['gal_id']);
    if ($item) {
        /* get filters */
        $filtersArray = array();
        if (!empty($data['gal_rotate'])) {
            $filtersArray['rot'] = (string)$data['gal_rotate'];
        }
        /* text watermark */
        if (!empty($data['gal_watermark-text'])) {
            $filtersArray['wmt'] = (string)$data['gal_watermark-text'].'|5|'.$data['gal_watermark-text-position'].'|ffffff|||5|||100|0';
        }
        /* crop */
        if (!empty($data['gal_cropCoords'])) {
            $filtersArray['crop'] = $data['gal_cropLeft'].'|'.$data['gal_cropRight'].'|'.$data['gal_cropTop'].'|'.$data['gal_cropBottom'];
        }
        $filters = '';
        foreach ($filtersArray as $filter => $val) {
            $filters .= '&fltr[]='.$filter.'|'.$val;
        }

        /* get any other params */
        $other = !empty($data['gal_other']) ? $data['gal_other'] : '';
        if (!empty($other)) {
            if (substr($other,0,1) != '&') {
                $other = '&'.$other;
            }
        }

        $url = $item->get('image',array(
            'w' => $data['gal_image_width'],
            'h' => $data['gal_image_height'],
            'f' => 'png',
        )).$filters.$other;

        $class = !empty($data['gal_class']) ? 'class="'.$data['gal_class'].'"' : '';
        $value = '<img src="'.$url.'" alt="'.$data['gal_description'].'" title="'.$data['gal_name'].'" '.$class.' />';
    } else {
        $value = '';
    }
} else { /* if empty dont return json, return blank */
    $value = '';
}
return $value;