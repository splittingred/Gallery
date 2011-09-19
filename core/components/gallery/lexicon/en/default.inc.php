<?php
/**
 * Gallery
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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
 * Default English Lexicon Entries for Gallery
 *
 * @package gallery
 * @subpackage lexicon
 */
$_lang['gallery'] = 'Gallery';
$_lang['gallery.active'] = 'Active';
$_lang['gallery.active_desc'] = 'If false, this album will not be viewable.';
$_lang['gallery.album'] = 'Album';
$_lang['gallery.album_create'] = 'Create Album';
$_lang['gallery.album_err_nf'] = 'Album not found.';
$_lang['gallery.album_err_ns'] = 'Album not specified.';
$_lang['gallery.album_err_ns_name'] = 'Please enter a valid name for the album.';
$_lang['gallery.album_err_remove'] = 'An error occurred while trying to remove the album.';
$_lang['gallery.album_err_save'] = 'An error occurred while trying to save the album.';
$_lang['gallery.album_remove'] = 'Remove Album';
$_lang['gallery.album_remove_confirm'] = 'Are you sure you want to remove this album? Any items that are not in any other albums will also be removed.';
$_lang['gallery.album_update'] = 'Update Album';
$_lang['gallery.albums'] = 'Albums';
$_lang['gallery.back'] = 'Back';
$_lang['gallery.batch_upload'] = 'Batch Upload';
$_lang['gallery.batch_upload_intro'] = '<p>Specify a directory on the filesystem to scan for images. You can use {base_path}, {core_path}, or {assets_path} as placeholders.</p>';
$_lang['gallery.batch_upload_tags'] = 'Tags to assign to any items batch uploaded, in comma-separated list format.';
$_lang['gallery.bytes'] = 'bytes';
$_lang['gallery.comma_separated_list'] = 'Comma-separated list';
$_lang['gallery.dropfileshere'] = 'Drop Files here to Upload';
$_lang['gallery.directory'] = 'Directory';
$_lang['gallery.directory_desc'] = 'The directory to scan for images.';
$_lang['gallery.directory_err_create'] = 'Could not create directory: [[+directory]]';
$_lang['gallery.directory_err_nf'] = 'Directory not found.';
$_lang['gallery.directory_err_ns'] = 'Directory not specified.';
$_lang['gallery.directory_err_write'] = 'Could not write to directory: [[+directory]]';
$_lang['gallery.file'] = 'File';
$_lang['gallery.file_err_move'] = 'An error occurred while trying to move the file: [[+file]] to [[+target]]';
$_lang['gallery.file_name'] = 'File Name';
$_lang['gallery.file_size'] = 'File Size';
$_lang['gallery.height'] = 'Height';
$_lang['gallery.images_selected'] = '[[+count]] images selected.';
$_lang['gallery.inactive'] = 'Inactive';
$_lang['gallery.intro_msg'] = 'Here you can manage your albums. Right-click on an album to view more options.';
$_lang['gallery.item_delete'] = 'Delete Item';
$_lang['gallery.item_delete_confirm'] = 'Are you sure you want to delete this item entirely? This is irreversible.';
$_lang['gallery.item_delete_multiple'] = 'Delete Selected Items';
$_lang['gallery.item_delete_multiple_confirm'] = 'Are you sure you want to delete these items entirely? This is irreversible.';
$_lang['gallery.item_err_nf'] = 'Item not found.';
$_lang['gallery.item_err_ns'] = 'Item not specified.';
$_lang['gallery.item_err_ns_file'] = 'Please specify a file to upload.';
$_lang['gallery.item_err_remove'] = 'An error occurred while trying to remove the item.';
$_lang['gallery.item_err_save'] = 'An error occurred while trying to save the item.';
$_lang['gallery.item_err_upload'] = 'An error occurred while trying to upload the item.';
$_lang['gallery.item_remove'] = 'Remove Item';
$_lang['gallery.item_remove_album'] = 'Remove Item from Album';
$_lang['gallery.item_update'] = 'Update Item';
$_lang['gallery.item_upload'] = 'Upload Item';
$_lang['gallery.item_url'] = 'URL';
$_lang['gallery.item_url_desc'] = 'A URL that the user will go to when clicking this Gallery Item.';
$_lang['gallery.items'] = 'Items';
$_lang['gallery.menu_desc'] = 'A dynamic gallery system.';
$_lang['gallery.multi_item_upload'] = 'Multi-Item Upload';
$_lang['gallery.parent'] = 'Parent';
$_lang['gallery.prominent'] = 'Prominent';
$_lang['gallery.prominent_desc'] = 'Making an Album non-prominent can be used to hide Albums from your Album listing, should you want a private album, or to create non-listed albums.';
$_lang['gallery.refresh'] = 'Refresh';
$_lang['gallery.tags'] = 'Tags';
$_lang['gallery.title'] = 'Title';
$_lang['gallery.width'] = 'Width';
$_lang['gallery.xpdozip_err_nf'] = 'Could not load xPDOZip class.';
$_lang['gallery.zip_err_ns'] = 'Please specify a zip file.';
$_lang['gallery.zip_err_unpack'] = 'Could not unpack zip file. Please check and make sure that your zip file is not corrupted, and that the Gallery files path is correct.';
$_lang['gallery.zip_file'] = 'Zip File';
$_lang['gallery.zip_upload'] = 'Zip Upload';
$_lang['gallery.zip_upload_intro'] = '<p>Specify a zip file to upload for images. Gallery will unzip the file and place the images in it in this Album.</p>';


$_lang['area_backend'] = 'Backend';

$_lang['setting_gallery.backend_thumb_far'] = 'Backend Thumbnail Aspect Ratio';
$_lang['setting_gallery.backend_thumb_far_desc'] = 'The phpThumb FAR (aspect ratio) setting for thumbnails when managing them in the backend.';

$_lang['setting_gallery.backend_thumb_height'] = 'Backend Thumbnail Height';
$_lang['setting_gallery.backend_thumb_height_desc'] = 'The height in pixels for thumbnails when managing them in the backend.';

$_lang['setting_gallery.backend_thumb_width'] = 'Backend Thumbnail Width';
$_lang['setting_gallery.backend_thumb_width_desc'] = 'The width in pixels for thumbnails when managing them in the backend.';

$_lang['setting_gallery.backend_thumb_zoomcrop'] = 'Backend Thumbnail Zoomcrop';
$_lang['setting_gallery.backend_thumb_zoomcrop_desc'] = 'Whether or not to use zoomcrop for thumbnails when managing them in the backend.';

$_lang['setting_gallery.default_batch_upload_path'] = 'Default Batch Upload Path';
$_lang['setting_gallery.default_batch_upload_path_desc'] = 'The default value to use for the path when using batch upload.';

$_lang['setting_gallery.thumbs_prepend_site_url'] = 'Prepend Site URL to Thumbs';
$_lang['setting_gallery.thumbs_prepend_site_url_desc'] = 'If true, will prepend the site URL to all thumbnails being sent to phpThumb.';

$_lang['setting_gallery.use_richtext'] = 'Use Rich Text?';
$_lang['setting_gallery.use_richtext_desc'] = 'Use TinyMCE as Rich Text Editor for item descriptions. Note: this requires the TinyMCE extra being installed.';
$_lang['setting_gallery.tiny.width'] = 'Editor Width';
$_lang['setting_gallery.tiny.width_desc'] = 'The width of the Rich Text Editor.';
$_lang['setting_gallery.tiny.height'] = 'Editor Height';
$_lang['setting_gallery.tiny.height_desc'] = 'The height of the Rich Text Editor.';
$_lang['setting_gallery.tiny.buttons1'] = 'Custom Buttons 1';
$_lang['setting_gallery.tiny.buttons1_desc'] = 'Custom string of Buttons to use on the first row. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.buttons2'] = 'Custom Buttons 2';
$_lang['setting_gallery.tiny.buttons2_desc'] = 'Custom string of Buttons to use on the second row. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.buttons3'] = 'Custom Buttons 3';
$_lang['setting_gallery.tiny.buttons3_desc'] = 'Custom string of Buttons to use on the third row. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.buttons4'] = 'Custom Buttons 4';
$_lang['setting_gallery.tiny.buttons4_desc'] = 'Custom string of Buttons to use on the fourth row. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.buttons5'] = 'Custom Buttons 5';
$_lang['setting_gallery.tiny.buttons5_desc'] = 'Custom string of Buttons to use on the fifth row. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.custom_plugins'] = 'Custom Plugins';
$_lang['setting_gallery.tiny.custom_plugins_desc'] = 'Custom string of plugins to load. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.theme'] = 'Editor Theme';
$_lang['setting_gallery.tiny.theme_desc'] = 'The theme to use with the Rich Text Editor.';
$_lang['setting_gallery.tiny.theme_advanced_blockformats'] = 'Block Formats';
$_lang['setting_gallery.tiny.theme_advanced_blockformats_desc'] = 'Block Formats to use in the editor. When empty, will inherit from the TinyMCE Extra.';
$_lang['setting_gallery.tiny.theme_advanced_css_selectors'] = 'CSS Selectors';
$_lang['setting_gallery.tiny.theme_advanced_css_selectors_desc'] = 'CSS selectors to use in the editor. When empty, will inherit from the TinyMCE Extra.';


