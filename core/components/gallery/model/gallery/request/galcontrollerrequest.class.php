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
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package gallery
 * @extends modRequest
 */
class GalControllerRequest extends modRequest {
    public $gallery = null;
    public $actionVar = 'action';
    public $defaultAction = 'home';

    function __construct(Gallery &$gallery) {
        parent :: __construct($gallery->modx);
        $this->gallery =& $gallery;
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        $modx =& $this->modx;
        $gallery =& $this->gallery;

        $modx->regClientCSS($gallery->config['cssUrl'].'mgr.css');
        $viewHeader = include $this->gallery->config['corePath'].'controllers/mgr/header.php';

        $f = $this->gallery->config['corePath'].'controllers/mgr/'.strtolower($this->action).'.php';
        if (file_exists($f)) {
            $this->modx->lexicon->load('gallery:default');
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}