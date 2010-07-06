<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx =& $this->xpdo;
$modx->lexicon->load('tv_widget');
$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$modx->setLogTarget('ECHO');
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
$modx->addPackage('gallery',$corePath.'model/');

if (!empty($this->value)) {
    $item = $modx->getObject('galItem',$this->value);
    if ($item) {
        $item->url = $item->get('absoluteImage');
        $modx->smarty->assign('item',$item);
        $modx->smarty->assign('connectors_url',$modx->getOption('connectors_url',null,MODX_CONNECTORS_URL));
    }
}

return $modx->smarty->fetch($corePath.'elements/tv/galleryitem.input.tpl');