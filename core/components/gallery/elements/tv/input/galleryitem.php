<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

$corePath = $this->xpdo->getOption('gallery.core_path',null,$this->xpdo->getOption('core_path').'components/gallery/');
return $this->xpdo->smarty->fetch($corePath.'elements/tv/galleryitem.input.tpl');