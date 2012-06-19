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
 * @subpackage slimbox
 */
require_once dirname(__FILE__).'/galleryplugin.class.php';
/**
 * @package gallery
 * @subpackage slimbox
 */
class Slimbox extends GalleryPlugin {
    public function load() {
        $this->modx->lexicon->load('gallery:slimbox');

        $this->config = array_merge(array(
            'loop' => false,
            'overlayOpacity' => 0.8,
            'overlayFadeDuration' => 400,
            'resizeDuration' => 400,
            'resizeEasing' => "swing",
            'initialWidth' => 250,
            'initialHeight' => 250,
            'imageFadeDuration' => 400,
            'captionAnimationDuration' => 400,
            'counterText' => 'Image {x} of {y}',
        ),$this->config);

        $this->toInt($this->config,array(
            'overlayFadeDuration',
            'resizeDuration',
            'initialWidth',
            'initialHeight',
            'imageFadeDuration',
            'captionAnimationDuration',
        ));
        $this->toBoolean($this->config,array(
            'loop',
        ));

        $this->renderCssJs();
    }

    public function renderItem(array &$scriptProperties) {

    }

    public function adjustSettings(array $scriptProperties = array()) {
        $scriptProperties['linkAttributes'] = $this->modx->getOption('linkAttributes',$scriptProperties,'').' rel="lightbox"';
        $scriptProperties['linkToImage'] = 1;
        return $scriptProperties;
    }

    protected function renderCssJs() {
        $useCss = $this->modx->getOption('slimboxUseCss',$this->config,true);
        if ($useCss) {
            $this->modx->regClientCSS($this->modx->getOption('slimboxCss',$this->config,$this->gallery->config['assetsUrl'].'packages/slimbox/css/slimbox2.css'));
        }

        $renderJsOnStartup = (boolean) $this->modx->getOption('slimboxRenderJsOnStartup',$this->config,true);

        if($renderJsOnStartup){
            $regJsOn   = 'regClientStartupScript';
            $regHTMLOn = 'regClientStartupHTMLBlock';
        }
        else{
            $regJsOn   = 'regClientScript';
            $regHTMLOn = 'regClientHTMLBlock';
        }

        $jqueryLoad = (boolean)$this->modx->getOption('slimboxLoadJQuery',$this->config,false);
        if ($jqueryLoad) {
            $jqueryUrl = $this->modx->getOption('slimboxJQueryUrl',$this->config,'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
            $this->modx->$regJsOn($jqueryUrl);
        }
        $this->modx->$regJsOn($this->gallery->config['assetsUrl'].'packages/slimbox/js/slimbox2.js');

        $jsTpl = $this->modx->getOption('slimboxJsTpl',$this->config,'slimbox/js');
        $properties = array(
            'options' => $this->modx->toJSON($this->config),
        );
        $output = $this->gallery->getChunk($jsTpl,$properties);
        $this->modx->$regHTMLOn($output);
    }
}