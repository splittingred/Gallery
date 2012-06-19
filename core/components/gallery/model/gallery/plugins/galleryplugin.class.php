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
 * @abstract
 */
abstract class GalleryPlugin {
    public $gallery = null;
    public $modx = null;
    public $config = array();

    function __construct(Gallery &$gallery,array $config = array()) {
        $this->gallery =& $gallery;
        $this->modx =& $gallery->modx;
        $this->config = array_merge(array(

        ),$config);
    }

    public function adjustSettings(array $scriptProperties = array()) {
        return $scriptProperties;
    }

    public function renderItem(array &$scriptProperties) {

    }

    public function toInt(&$array,array $properties = array()) {
        foreach ($properties as $property) {
            if (isset($array[$property])) {
                $array[$property] = (int)$array[$property];
            }
        }
    }
    public function toBoolean(&$array,array $properties = array()) {
        foreach ($properties as $property) {
            if (isset($array[$property])) {
                $array[$property] = (boolean)$array[$property];
            }
        }
    }

    abstract public function load();
}