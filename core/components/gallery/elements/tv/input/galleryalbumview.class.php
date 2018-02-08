<?php
    // core/components/gallery/elements/tv/input/galleryalbumview.class.php
class GalleryAlbumViewInputRender extends modTemplateVarInputRender {
    public function getTemplate() {
        return $this->modx->getOption('gallery.core_path',null, $this->modx->getOption('core_path').'components/gallery/').'elements/tv/galleryalbumview.input.tpl';
    }
}
return 'GalleryAlbumViewInputRender';
?>