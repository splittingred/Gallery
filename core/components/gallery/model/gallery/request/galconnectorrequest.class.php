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
require_once MODX_CORE_PATH . 'model/modx/modconnectorresponse.class.php';
/**
 * @package gallery
 */
class GalConnectorRequest extends modConnectorResponse {

    function __construct(Gallery &$gallery,array $config = array()) {
        $this->gallery =& $gallery;
        parent::__construct($gallery->modx,$config);
    }

    public function handle($action = '') {
        if (empty($action) && !empty($_REQUEST['action'])) $action = $_REQUEST['action'];
        if (!isset($this->modx->error)) $this->loadErrorHandler();

        $path = $this->gallery->config['processorsPath'].strtolower($action).'.php';
        $processorOutput = false;
        if (file_exists($path)) {
            $this->modx->lexicon->load('gallery:default');
            $modx =& $this->modx;
            $gallery =& $this->gallery;

            $scriptProperties = $_REQUEST;

            $processorOutput = include $path;
        } else {
            $processorOutput = $this->modx->error->failure('No action specified.');
        }
        if (is_array($processorOutput)) {
            $processorOutput = $this->modx->toJSON(array(
                'success' => isset($processorOutput['success']) ? $processorOutput['success'] : 0,
                'message' => isset($processorOutput['message']) ? $processorOutput['message'] : $this->modx->lexicon('error'),
                'total' => (isset($processorOutput['total']) && $processorOutput['total'] > 0)
                        ? intval($processorOutput['total'])
                        : (isset($processorOutput['errors'])
                                ? count($processorOutput['errors'])
                                : 1),
                'data' => isset($processorOutput['errors']) ? $processorOutput['errors'] : array(),
                'object' => isset($processorOutput['object']) ? $processorOutput['object'] : array(),
            ));
        }

        if (!isset($_FILES) && empty($_FILES)) {
            header("Content-Type: text/json; charset=UTF-8");
        }
        return $processorOutput;
    }
}