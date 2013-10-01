<?php
/**
 * Gallery
 *
 * This particular proccessor writen by Egor Bolgov <egor.b@fabricasaitov.ru>
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
 *
 * Gallery is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gallery is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Gallery; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gallery
 */
/**
 * @var modX $modx
 *
 * @package gallery
 * @subpackage processors
 */
class GalleryAlbumUploadCoverProcessor extends modProcessor {
	private $albumId;
	/**
	 * @var galAlbum
	 */
	private $obAlbum;
	public $languageTopics = array('gallery:default');

	public function process() {
		$canSave = $this->beforeSave();
		if ($canSave !== true) {
			return $this->failure($canSave);
		}
		if($this->obAlbum->setCoverItem($_FILES['file'])) {
			$arResult=$this->obAlbum->toArray();
			$arResult['cover_filename_url']=$this->obAlbum->getCoverUrl();
			return $this->success('',$arResult);
		}
		return $this->failure(false);
	}

	public function beforeSave() {
		/* type parsing */
		$this->albumId = intval($this->getProperty('id'));
		if (empty($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
			return $this->failure($this->modx->lexicon('gallery.item_err_ns_file'));
		}
		$this->obAlbum=$this->modx->getObject('galAlbum',$this->albumId);
		if(!$this->obAlbum) {
			$this->addFieldError('albumid',$this->modx->lexicon('gallery.album_not_found'));
		}
		return !$this->hasErrors();
	}
}
return 'GalleryAlbumUploadCoverProcessor';