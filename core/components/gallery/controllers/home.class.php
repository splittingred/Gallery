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
 * Loads the home page.
 *
 * @package gallery
 * @subpackage controllers
 */
class GalleryHomeManagerController extends GalleryManagerController {
    public function getPageTitle() { return $this->modx->lexicon('gallery'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->gallery->config['jsUrl'].'mgr/widgets/album/album.tree.js');
        $this->addJavascript($this->gallery->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->gallery->config['jsUrl'].'mgr/sections/home.js');
        $this->addHtml("<script>Ext.onReady(function() { MODx.load({xtype: 'gal-page-home'}) })</script>");
    }
    public function getTemplateFile() { return $this->gallery->config['templatesPath'].'home.tpl'; }
}