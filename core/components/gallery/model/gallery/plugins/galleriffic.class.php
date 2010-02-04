<?php
/**
 * @package gallery
 * @subpackage galleriffic
 */
require_once dirname(__FILE__).'/galleryplugin.class.php';
/**
 * @package gallery
 * @subpackage galleriffic
 */
class Galleriffic extends GalleryPlugin {
    public function load() {
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
            'playLinkText' => 'Play Sideshow',
            'pauseLinkText' => 'Pause Slideshow',
            'prevLinkText' => '&lsaquo; Previous Photo',
            'nextLinkText' => 'Next Photo &rsaquo;',
            'nextPageLinkText' => 'Next &rsaquo;',
            'prevPageLinkText' => '&lsaquo; Prev',
            'enableHistory' => false,
            'autoStart' => false,
            'syncTransitions' => true,
            'defaultTransitionDuration' => 500,

            'navigationWidth' => '300px',
            'onMouseOutOpacity' => '0.67',
        ),$this->config);

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
        $this->modx->regClientCSS($this->gallery->config['assetsUrl'].'packages/galleriffic20/css/galleriffic-2.css');
        $this->modx->regClientStartupScript($this->gallery->config['assetsUrl'].'packages/galleriffic20/js/jquery.galleriffic.js');
        $this->modx->regClientStartupScript($this->gallery->config['assetsUrl'].'packages/galleriffic20/js/jquery.opacityrollover.js');


        $jsTpl = $this->modx->getOption('gallerifficJsTpl',$this->config,'GallerifficJS');
        $properties = array(
            'options' => $this->modx->toJSON($this->config),
        );
        $output = $this->gallery->getChunk($jsTpl,$properties);
        $this->modx->regClientStartupHTMLBlock($output);
    }
}