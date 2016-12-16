<?php
/**
 * Handles plugin events for Gallery's Custom TV
 * need to add plugin property tmplvarid
 * 
 * @package gallery
 */
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
switch ($modx->event->name) {
    case 'OnResourceDuplicate':
          $newResource = $modx->event->params['newResource'];
          $id=$newResource->get('id');
          $tv = $modx->getObject('modTemplateVarResource',array ('tmplvarid'=>$tmplvarid,'contentid' => $id));
          if (isset($tv))           {
             $tv->remove();
           }
        break;
    case 'OnDocFormSave':
            $GalleryProcessorPath = $modx->getOption('gallery.core_path',$config,$modx->getOption('core_path').'components/gallery/').'processors/';
            $options = array('processors_path'=>$GalleryProcessorPath);
            $galleryName = $resource->get('pagetitle');

            //Получаем все TV текущего ресурса
            $tvs = $resource->getTemplateVars();
            foreach($tvs as $tv) {
                //нам нужны только TV с типом galleryalbumview
                if ($tv->get('type')=='galleryalbumview') {
                    $tvvalue = $tv->getValue($id);
                    if (empty($tvvalue)) {
                        //Параметры TV
                        $tv_prop = $tv->get('properties');
                        //Создаем альбом
                        $album = array(
                            'name' => $galleryName,
                            'parent' => isset($tv_prop['galParentId']['value'])?$tv_prop['galParentId']['value']:0,
                            'description' => '',
                            'active' => 1,
                            'prominent' => 0
                        );
                        $resp = $modx->runProcessor('mgr/album/create',$album,$options);
                        if (!$resp->isError()) {
                            $album = $resp->getObject();
                            $tv->setValue($id,$album['id']);
                            $tv->save();
                        }
                        
                    } else {
                        // TV уже есть, обновим название альбома
                        $resp = $modx->runProcessor('mgr/album/get',array('id'=>$tvvalue),$options);
                        if (!$resp->isError()) {
                            $album = $resp->getObject();
                            $album['name'] = $galleryName;
                            $modx->runProcessor('mgr/album/update',$album,$options);    
                        }

                    }
                }
            }
            break;
    
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
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/utils/ddview.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/utils/fileuploader.js');
        $modx->controller->addJavascript($gallery->config['assetsUrl'].'js/mgr/widgets/album/album.panel.js');
        $modx->controller->addCss($gallery->config['cssUrl'].'mgr.css');
        $modx->controller->addCss($gallery->config['cssUrl'].'fileuploader.css');
        break;
}
return;