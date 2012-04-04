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
 * @subpackage controllers
 */
require_once dirname(__FILE__) . '/model/gallery/gallery.class.php';
class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'home'; }
}

abstract class GalleryManagerController extends modManagerController {
    /** @var Gallery $gallery */
    public $gallery;
    public function initialize() {
        $this->gallery = new Gallery($this->modx);

        $this->addCss($this->gallery->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->gallery->config['jsUrl'].'mgr/gallery.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            GAL.config = '.$this->modx->toJSON($this->gallery->config).';
            GAL.config.connector_url = "'.$this->gallery->config['connectorUrl'].'";
            GAL.action = "'.(!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0).'";
        });
        </script>');
    }
    public function process(array $scriptProperties = array()) {

    }
    public function getLanguageTopics() {
        return array('gallery:default');
    }
    public function checkPermissions() { return true;}
}