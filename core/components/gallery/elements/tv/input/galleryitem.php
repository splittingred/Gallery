<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx =& $this->xpdo;
$modx->lexicon->load('tv_widget','gallery:default');
$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$modx->setLogTarget('ECHO');
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (!empty($this->value)) {
    $data = $modx->fromJSON($this->value);
    if (is_array($data)) {
        $item = $modx->getObject('galItem',$data['id']);
        if ($item) {
            $item->getSize();
            $itemArray = $item->toArray('',true,true);
            $itemArray['url'] = $item->get('absoluteImage');
            $js = $modx->toJSON($data);
            $modx->smarty->assign('itemjson',$js);
        }
    }
}

return $modx->smarty->fetch($corePath.'elements/tv/galleryitem.input.tpl');