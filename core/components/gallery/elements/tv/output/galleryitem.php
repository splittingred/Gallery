<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
$corePath = $this->xpdo->getOption('gallery.core_path',null,$this->xpdo->getOption('core_path').'components/gallery/');
$this->xpdo->addPackage('gallery',$corePath.'model/');

if (!empty($value)) {
    $data = $this->xpdo->fromJSON($value);

    $item = $this->xpdo->getObject('galItem',$data['id']);
    if ($item) {
        $url = $item->get('image').'&w='.$data['image_width'].'&h='.$data['image_height'];
        $value = '<img src="'.$url.'" alt="'.$data['description'].'" title="'.$data['name'].'" />';
    }
}
return $value;