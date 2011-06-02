<?php
/**
 * @package gallery
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'galimport.class.php';
/**
 * @package gallery
 */
class galZipImport extends galImport {
    const OPT_EXTENSIONS = 'extensions';
    const OPT_IGNORE_DIRECTORIES = 'ignore_directories';
    const OPT_USE_MULTIBYTE = 'use_multibyte';
    const OPT_ENCODING = 'encoding';
    public $target = '';
    public $results = array();

    public function initialize() {
        if (!$this->modx->loadClass('compression.xPDOZip',$this->modx->getOption('core_path').'xpdo/',true,true)) {
            return $this->modx->lexicon('gallery.xpdozip_err_nf');
        }
        $this->config[galZipImport::OPT_EXTENSIONS] = explode(',',$this->modx->getOption('gallery.import_allowed_extensions',null,'jpg,jpeg,png,gif,bmp'));
        $this->config[galZipImport::OPT_USE_MULTIBYTE] = $this->modx->getOption('use_multibyte',null,false);
        $this->config[galZipImport::OPT_ENCODING] = $this->modx->getOption('modx_charset',null,'UTF-8');
        $this->config[galZipImport::OPT_IGNORE_DIRECTORIES] = explode(',',$this->modx->getOption('gallery.import_ignore_directories',null,'.,..,.svn,.git,__MACOSX,.DS_Store'));
        return true;
    }

    public function setSource($source,array $options) {
        if (empty($source) || $source['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        $this->source = $source;
        return true;
    }

    public function run($albumId,array $options) {
        $preparedTarget = $this->prepareTarget($albumId);
        if ($preparedTarget !== true) return $preparedTarget;
        
        $unpacked = $this->unpack();
        if ($unpacked !== true) return $unpacked;

        /* iterate */
        $errors = array();
        /* iterate over zipped files and move them into main dir */
        foreach (new DirectoryIterator($this->target) as $dir) {
            if (in_array($dir->getFilename(),$this->config[galZipImport::OPT_IGNORE_DIRECTORIES])) continue;
            if ($dir->isDir()) {
                foreach (new DirectoryIterator($dir->getPathname()) as $file) {
                    if (in_array($file->getFilename(),$this->config[galZipImport::OPT_IGNORE_DIRECTORIES])) continue;

                    $fileName = $file->getFilename();
                    $filePathName = $file->getPathname();

                    $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
                    $fileExtension = $this->config[galZipImport::OPT_USE_MULTIBYTE] ? mb_strtolower($fileExtension,$this->config[galZipImport::OPT_ENCODING]) : strtolower($fileExtension);
                    if (!in_array($fileExtension,$this->config[galZipImport::OPT_EXTENSIONS])) continue;

                    /* create item */
                    $item = $this->modx->newObject('galItem');
                    $item->set('name',$fileName);
                    $item->set('createdby',$this->modx->user->get('id'));
                    $item->set('mediatype','image');
                    $item->set('active',$options['active']);
                    if (!$item->save()) {
                        $errors[] = $this->modx->lexicon('gallery.item_err_save');
                        continue;
                    }

                    $newFileName = $item->get('id').'.'.$fileExtension;
                    $newRelativePath = $albumId.'/'.$newFileName;
                    $newAbsolutePath = $this->target.'/'.$newFileName;

                    if (@file_exists($newAbsolutePath)) {
                        @unlink($newAbsolutePath);
                    }
                    if (!@copy($filePathName,$newAbsolutePath)) {
                        $errors[] = $this->modx->lexicon('gallery.file_err_move',array(
                            'file' => $newFileName,
                            'target' => $newAbsolutePath,
                        ));
                        $item->remove();
                        continue;
                    } else {
                        $item->set('filename',$newRelativePath);
                        $item->save();
                    }

                    /* get count of items in album */
                    $total = $this->modx->getCount('galAlbumItem',array('album' => $albumId));

                    /* associate with album */
                    $albumItem = $this->modx->newObject('galAlbumItem');
                    $albumItem->set('album',$albumId);
                    $albumItem->set('item',$item->get('id'));
                    $albumItem->set('rank',$total);
                    $albumItem->save();

                    /* save tags */
                    if (isset($options['tags'])) {
                        $tagNames = explode(',',$options['tags']);
                        foreach ($tagNames as $tagName) {
                            $tagName = trim($tagName);
                            if (empty($tagName)) continue;

                            $tag = $this->modx->newObject('galTag');
                            $tag->set('item',$item->get('id'));
                            $tag->set('tag',$tagName);
                            $tag->save();
                        }
                    }

                    $this->results[] = $fileName;
                }
                /* delete subdir */
                $this->modx->cacheManager->deleteTree($dir->getPathname(),array('deleteTop' => true, 'skipDirs' => false, 'extensions' => '*'));
            }
        }
        if (!empty($errors)) {
            return implode(',',$errors);
        }
        return true;
    }

    public function prepareTarget($albumId) {
        $this->target = $this->modx->getOption('gallery.files_path').$albumId.'/';
        
        /* get sanitized base path and current path */
        $cacheManager = $this->modx->getCacheManager();
        /* if directory doesnt exist, create it */
        if (!file_exists($this->target) || !is_dir($this->target)) {
            if (!$cacheManager->writeTree($this->target)) {
               $this->modx->log(modX::LOG_LEVEL_ERROR,'[Gallery] Could not create directory: '.$this->target);
               return $this->modx->lexicon('gallery.directory_err_create',array('directory' => $this->target));
            }
        }
        /* make sure directory is readable/writable */
        if (!is_readable($this->target) || !is_writable($this->target)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,'[Gallery] Could not write to directory: '.$this->target);
            return $this->modx->lexicon('gallery.directory_err_write',array('directory' => $this->target));
        }
        return true;
    }

    public function unpack() {
        /* unpack zip file */
        $archive = new xPDOZip($this->modx,$this->source['tmp_name']);
        if (!$archive) {
            return $this->modx->lexicon('gallery.zip_err_unpack');
        }
        $archive->unpack($this->target);
        $archive->close();
        return true;
    }
}