<?php
/**
 * Gallery
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
require_once dirname(__FILE__).'/galleryplugin.class.php';
/**
 * @package gallery
 * @subpackage colorbox
 */
class Colorbox extends GalleryPlugin {
    public function load() {
        $this->modx->lexicon->load('gallery:colorbox');

        $this->config = array_merge(array(
            'theme' => 'theme1',
            'transition' => 'elastic',
            'speed' => 350,
            'width' => false,
            'height' => false,
            'maxWidth' => false,
            'maxHeight' => false,
            'opacity' => 0.85,
            'loop' => true,
            'slideshow' => false,
            'slideshowSpeed' => 2500,
            'slideshowAuto' => true,
            'slideshowStart' => $this->modx->lexicon('gallery.start_slideshow'),
            'slideshowStop' => $this->modx->lexicon('gallery.stop_slideshow'),
            'current' => $this->modx->lexicon('gallery.current'),
            'previous' => $this->modx->lexicon('gallery.previous'),
            'next' => $this->modx->lexicon('gallery.next'),
            'close' => $this->modx->lexicon('gallery.close'),
            
        ),$this->config);

        $this->toInt($this->config,array(
            'speed',
            'slideshowSpeed',
        ));
        $this->toBoolean($this->config,array(
            'slideshow',
            'slideshowAuto',
            'loop',
            'colorboxUseCss',
            'colorboxUseJs',
        ));

        $this->renderCssJs();
    }

    public function adjustSettings(array $scriptProperties = array()) {
        $scriptProperties['thumbTpl'] = $this->modx->getOption('colorboxThumbTpl',$this->config,'ColorboxItemThumb');
        $scriptProperties['containerTpl'] = $this->modx->getOption('colorboxContainerTpl',$this->config,'ColorboxContainer');
        return $scriptProperties;
    }

    protected function renderCssJs() {
        $useCss = $this->modx->getOption('colorboxUseCss',$this->config,true);
        
        if ($useCss) {
            $this->modx->regClientCSS($this->gallery->config['assetsUrl'].'packages/colorbox/'.$this->gallery->config['theme'].'/colorbox.css');
        }
        
        $useJs = $this->modx->getOption('colorboxUseJs',$this->config,true);
        if ($useJs) {
            $this->modx->regClientStartupScript($this->gallery->config['assetsUrl'].'packages/colorbox/colorbox/jquery.colorbox-min.js');
            $jsTpl = $this->modx->getOption('colorboxJsTpl',$this->config,'ColorboxJS');
        }
        
        $properties = array(
            'options' => $this->modx->toJSON($this->config),
        );
        $output = $this->gallery->getChunk($jsTpl,$properties);
        $this->modx->regClientStartupHTMLBlock($output);
    }
}