<?php
/* @var modX $modx
 * @var array $scriptProperties
 */
class GalleryItemAjaxUploadProcessor extends modProcessor {

    public function process(){
        $modx = $this->modx;
        $scriptProperties = $this->properties;

        /* validate form */
        $album = $modx->getOption('album',$scriptProperties,false);
        $filenm = $modx->getOption('qqfile',$scriptProperties,false);
        /* If $filenm is an array, it means we've used IE and we need to use a different name. */
        if (is_array($filenm)) $filenm = $filenm['name'];

        if (empty($album)) return $modx->error->failure($modx->lexicon('gallery.album_err_ns'));
        if (empty($filenm)) return $modx->error->failure($modx->lexicon('gallery.item_err_ns'));

        /* create item */
        $scriptProperties['active'] = !empty($scriptProperties['active']) ? 1 : 0;
        /** @var galItem $item */
        $item = $modx->newObject('galItem');
        $item->fromArray($scriptProperties);
        $item->set('createdby',$modx->user->get('id'));
        $item->set('name',$filenm);

        if (!$item->save()) {
            return $modx->error->failure($modx->lexicon('gallery.item_err_save'));
        }

        /* Upload */
        $albumDir = $album.'/';
        $targetDir = $modx->call('galAlbum','getFilesPath',array(&$modx)).$albumDir;

        /* upload the file */
        $array = explode('.', $filenm);
        $extension = @end($array);
        $filename = $item->get('id').'.'.$extension;
        $relativePath = $albumDir.$filename;
        $absolutePath = $targetDir.$filename;


        if (!empty($_FILES['qqfile'])) {
            if (!$item->upload($_FILES['qqfile'],$scriptProperties['album'])) {
                $item->remove();
                return $modx->error->failure($modx->lexicon('gallery.item_err_upload'));
            }
        } else {

            $length = 10;
            $tmpDir = MODX_CORE_PATH."cache/gallery-tmp/";

            if(!file_exists($tmpDir) && !mkdir($tmpDir) && !is_dir($tmpDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $tmpDir));
            }

            $randomFilename = $tmpDir.substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length).".$extension";

            /* Using AJAX upload - to tmp file then use the correct media source to upload */
            $input = fopen("php://input", "r");
            $target = fopen($randomFilename, "w");
            $bytes = stream_copy_to_stream($input, $target);
            fclose($input);
            fclose($target);

            $file = array("name" => $relativePath, "tmp_name" => $randomFilename, "error" => "0"); // emulate a $_FILES object

            //$modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Album Type: '.$scriptProperties['album']);


            if ($bytes == 0 || !$item->upload($file,$scriptProperties['album'])) {
                $modx->log(xPDO::LOG_LEVEL_INFO,'[Gallery] An error occurred while trying to upload the file to '.$absolutePath);
                $item->remove();
                return $modx->toJSON(array('error' => 'gallery.item_err_upload'));
            } else {
                $item->set('filename',str_replace(' ','',$relativePath));
            }

            @unlink($randomFilename);
        }

        $item->save();

        /* get count of items in album */
        $total = $modx->getCount('galAlbumItem',array('album' => $scriptProperties['album']));

        /* associate with album */
        /** @var galAlbumItem $albumItem */
        $albumItem = $modx->newObject('galAlbumItem');
        $albumItem->set('album',$scriptProperties['album']);
        $albumItem->set('item',$item->get('id'));
        $albumItem->set('rank',$total);
        $albumItem->save();

        /* save tags */
        if (isset($scriptProperties['tags'])) {
            $tagNames = explode(',',$scriptProperties['tags']);
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) continue;

                /** @var galTag $tag */
                $tag = $modx->newObject('galTag');
                $tag->set('item',$item->get('id'));
                $tag->set('tag',$tagName);
                $tag->save();
            }
        }

        /* output to browser */
        return $modx->toJSON(array('success' => true));
    }
}
return 'GalleryItemAjaxUploadProcessor';
