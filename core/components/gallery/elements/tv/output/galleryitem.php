<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
$corePath = $this->xpdo->getOption('gallery.core_path',null,$this->xpdo->getOption('core_path').'components/gallery/');
$this->xpdo->addPackage('gallery',$corePath.'model/');

if (!empty($value)) {
    $item = $this->xpdo->getObject('galItem',$value);
    if ($item) {
        $url = $item->get('image');
        $value = '<img src="'.$url.'" alt="'.$item->get('name').'" />';
    }
}
return $value;