<?php
/**
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
    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tv/properties/');
        break;
}
return;