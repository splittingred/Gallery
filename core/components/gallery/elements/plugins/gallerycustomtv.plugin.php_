<?php
/**
 * Handles plugin events for Gallery's Custom TV
 * 
 * @package gallery
 */
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath.'elements/tv/inputoptions/');
        break;
    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tv/properties/');
        break;
    case 'OnManagerPageBeforeRender':
        $gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
        if (!($gallery instanceof Gallery)) return '';

        $snippetIds = '';
        $gallerySnippet = $modx->getObject('modSnippet',array('name' => 'Gallery'));
        if ($gallerySnippet) {
            $snippetIds .= 'GAL.snippetGallery = "'.$gallerySnippet->get('id').'";'."\n";
        }
        $galleryItemSnippet = $modx->getObject('modSnippet',array('name' => 'GalleryItem'));
        if ($galleryItemSnippet) {
            $snippetIds .= 'GAL.snippetGalleryItem = "'.$galleryItemSnippet->get('id').'";'."\n";
        }

        $jsDir = $modx->getOption('gallery.assets_url',null,$modx->getOption('assets_url').'components/gallery/').'js/mgr/';
        $modx->controller->addLexiconTopic('gallery:default');
        $modx->controller->addJavascript($jsDir.'gallery.js');
        $modx->controller->addJavascript($jsDir.'tree.js');
        $modx->controller->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            GAL.config.connector_url = "'.$gallery->config['connectorUrl'].'";
            '.$snippetIds.'
        });
        </script>');
        break;
    case 'OnDocFormPrerender':
        $gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
        if (!($gallery instanceof Gallery)) return '';

        /* assign gallery lang to JS */
        $modx->controller->addLexiconTopic('gallery:tv');

        /* @var modAction $action */
        $action = $modx->getObject('modAction',array(
            'namespace' => 'gallery',
            'controller' => 'index',
        ));
        $modx->controller->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            GAL.config = {};
            GAL.config.connector_url = "'.$gallery->config['connectorUrl'].'";
            GAL.action = "'.($action ? $action->get('id') : 0).'";
        });
        </script>');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/tv/Spotlight.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/gallery.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/widgets/album/album.items.view.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/widgets/album/album.tree.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/tv/gal.browser.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/tv/galtv.js');
        $modx->controller->addCss($gallery->config['cssUrl'].'mgr.css');
        break;
}
return;