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
require_once dirname(__FILE__).'/galleryplugin.class.php';
/**
 * @package gallery
 * @subpackage galleriffic
 */
class Galleriffic extends GalleryPlugin {
    public function load() {
        $this->modx->lexicon->load('gallery:galleriffic');

        $this->config = array_merge(array(
            'delay' => 2500,
            'numThumbs' => 15,
            'preloadAhead' => 10,
            'enableTopPager' => true,
            'enableBottomPager' => true,
            'maxPagesToShow' => 7,
            'thumbsContainerSel' => '#gal-gaff-thumbs',
            'imageContainerSel' => '#gal-gaff-slideshow',
            'captionContainerSel' => '#gal-gaff-caption',
            'controlsContainerSel' => '#gal-gaff-controls',
            'loadingContainerSel' => '#gal-gaff-loading',
            'renderSSControls' => true,
            'renderNavControls' => true,
            'playLinkText' => $this->modx->lexicon('gallery.slideshow_play'),
            'pauseLinkText' => $this->modx->lexicon('gallery.slideshow_pause'),
            'prevLinkText' => '&lsaquo; '.$this->modx->lexicon('gallery.photo_previous'),
            'nextLinkText' => $this->modx->lexicon('gallery.photo_next').' &rsaquo;',
            'nextPageLinkText' => $this->modx->lexicon('gallery.next').' &rsaquo;',
            'prevPageLinkText' => '&lsaquo; '.$this->modx->lexicon('gallery.prev'),
            'enableHistory' => false,
            'autoStart' => false,
            'syncTransitions' => true,
            'defaultTransitionDuration' => 500,

            'navigationWidth' => '300px',
            'onMouseOutOpacity' => '0.67',
        ),$this->config);

        $this->toInt($this->config,array(
            'delay',
            'numThumbs',
            'preloadAhead',
            'maxPagesToShow',
            'defaultTransitionDuration',
        ));
        $this->toBoolean($this->config,array(
            'enableTopPager',
            'enableBottomPager',
            'renderSSControls',
            'renderNavControls',
            'enableHistory',
            'autoStart',
            'syncTransitions',
        ));

        $this->renderCssJs();
    }

    public function adjustSettings(array $scriptProperties = array()) {
        $scriptProperties['thumbTpl'] = $this->modx->getOption('gallerifficThumbTpl',$this->config,'GallerifficItemThumb');
        $scriptProperties['containerTpl'] = $this->modx->getOption('gallerifficContainerTpl',$this->config,'Galleriffic');
        $scriptProperties['thumbWidth'] = $this->modx->getOption('gallerifficThumbWidth',$this->config,75);
        $scriptProperties['thumbHeight'] = $this->modx->getOption('gallerifficThumbHeight',$this->config,75);
        return $scriptProperties;
    }

    protected function renderCssJs() {
        $useCss = $this->modx->getOption('gallerifficUseCss',$this->config,true);
        if ($useCss) {
            $this->modx->regClientCSS($this->modx->getOption('gallerifficCss',$this->config,$this->gallery->config['assetsUrl'].'packages/galleriffic20/css/galleriffic-2.css'));
        }

        $renderJsOnStartup = (boolean) $this->modx->getOption('gallerifficRenderJsOnStartup',$this->config,true);


        if($renderJsOnStartup){
            $regJsOn   = 'regClientStartupScript';
            $regHTMLOn = 'regClientStartupHTMLBlock';
        }
        else{
            $regJsOn   = 'regClientScript';
            $regHTMLOn = 'regClientHTMLBlock';
        }

        $this->modx->$regJsOn($this->gallery->config['assetsUrl'].'packages/galleriffic20/js/jquery.galleriffic.js');
        $this->modx->$regJsOn($this->gallery->config['assetsUrl'].'packages/galleriffic20/js/jquery.opacityrollover.js');


        $jsTpl = $this->modx->getOption('gallerifficJsTpl',$this->config,'GallerifficJS');
        $properties = array(
            'options' => $this->modx->toJSON($this->config),
        );
        $output = $this->gallery->getChunk($jsTpl,$properties);
        $this->modx->$regHTMLOn($output);
    }
}