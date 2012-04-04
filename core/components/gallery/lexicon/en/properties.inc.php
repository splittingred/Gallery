<?php
/**
 * Gallery
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
 * @var array $_lang
 * @package gallery
 * @subpackage lexicon
 */
/* Gallery Snippet */
$_lang['gallery.activecls_desc'] = 'The CSS class to add when the active item (the item specified in the GalleryItem snippet) is displayed.';
$_lang['gallery.album_desc'] = 'Will load only items from this album. Can be either the name or ID of the Album.';
$_lang['gallery.albumrequestvar_desc'] = 'If checkForRequestAlbumVar is set to true, will look for a REQUEST var with this name to select the album.';
$_lang['gallery.checkforrequestalbumvar_desc'] = 'If 1, if a REQUEST var of "album" is found, will use that as the album property for the snippet.';
$_lang['gallery.checkforrequesttagvar_desc'] = 'If 1, if a REQUEST var of "tag" is found, will use that as the tag property for the snippet.';
$_lang['gallery.containertpl_desc'] = 'An optional chunk to wrap the output in.';
$_lang['gallery.dir_desc'] = 'The direction to sort images by.';
$_lang['gallery.imagefar_desc'] = 'The "far" value for phpThumb for the image, for aspect ratio zooming.';
$_lang['gallery.imageheight_desc'] = 'If being used by a plugin, the height of the currently on-display image.';
$_lang['gallery.imagegetparam_desc'] = 'The GET param to use when not linking directly to an image. Make sure this matches the getParam property in the GalleryItem snippet call.';
$_lang['gallery.imageproperties_desc'] = 'A JSON object of parameters to pass to phpThumb as properties for the image.';
$_lang['gallery.imagequality_desc'] = 'The "q" value for phpThumb for the image, for quality.';
$_lang['gallery.imagewidth_desc'] = 'If being used by a plugin, the width of the currently on-display image.';
$_lang['gallery.imagezoomcrop_desc'] = 'If being used by a plugin, whether or not the currently on-display image will be zoom-cropped.';
$_lang['gallery.itemcls_desc'] = 'The CSS class for each thumbnail.';
$_lang['gallery.limit_desc'] = 'If set to non-zero, will only show X number of items.';
$_lang['gallery.linktoimage_desc'] = 'If true, will link directly to the image. If false, will append GET parameters to the URL to load the image with the GalleryItem snippet.';
$_lang['gallery.plugin_desc'] = 'The name of a plugin to use for front-end displaying. Please see the official docs for a list of available plugins.';
$_lang['gallery.pluginpath_desc'] = 'Could not load plugin "[[+name]]" from path: [[+path]]';
$_lang['gallery.showinactive_desc'] = 'If 1, will also display inactive images.';
$_lang['gallery.sort_desc'] = 'The field to sort images by.';
$_lang['gallery.start_desc'] = 'The index to start grabbing from when limiting the number of items. Similar to an SQL order by start clause.';
$_lang['gallery.tag_desc'] = 'Will load only items with this tag.';
$_lang['gallery.tagrequestvar_desc'] = 'If checkForRequestTagVar is set to true, will look for a REQUEST var with this name to select the tag.';
$_lang['gallery.toplaceholder_desc'] = 'If set, will set the output to a placeholder of this value, and the snippet call will output nothing.';
$_lang['gallery.thumbfar_desc'] = 'The "far" value for phpThumb for the thumbnail, for aspect ratio zooming.';
$_lang['gallery.thumbheight_desc'] = 'The height of the generated thumbnails, in pixels.';
$_lang['gallery.thumbproperties_desc'] = 'A JSON object of parameters to pass to phpThumb as properties for the thumbnail.';
$_lang['gallery.thumbquality_desc'] = 'The "q" value for phpThumb for the thumbnail, for quality.';
$_lang['gallery.thumbtpl_desc'] = 'The Chunk to use as a tpl for each thumbnail.';
$_lang['gallery.thumbwidth_desc'] = 'The width of the generated thumbnails, in pixels.';
$_lang['gallery.thumbzoomcrop_desc'] = 'Whether or not the thumbnail will be zoom-cropped.';
$_lang['gallery.usecss_desc'] = 'Whether or not to use the pre-provided CSS for the snippet.';

/* GalleryAlbums Snippet */
$_lang['galleryalbums.albumrequestvar_desc'] = 'If checkForRequestAlbumVar is set to true, will look for a REQUEST var with this name to select the album.';
$_lang['galleryalbums.albumcoversort_desc'] = 'The field which to use when sorting to get the Album Cover. To get the first image, use "rank". To get a random image, use "random".';
$_lang['galleryalbums.albumcoversortdir_desc'] = 'The direction to use when sorting to get the Album Cover. Accepts "ASC" or "DESC".';
$_lang['galleryalbums.dir_desc'] = 'The direction to sort the results by.';
$_lang['galleryalbums.limit_desc'] = 'If set to non-zero, will limit the number of results returned.';
$_lang['galleryalbums.parent_desc'] = 'Grab only the albums with a parent album with this ID.';
$_lang['galleryalbums.prominentonly_desc'] = 'If 1, will only display albums marked with a "prominent" status.';
$_lang['galleryalbums.rowcls_desc'] = 'A CSS class to be added to each album row.';
$_lang['galleryalbums.rowtpl_desc'] = 'The Chunk to use for each album row.';
$_lang['galleryalbums.showall_desc'] = 'If 1, will show all albums regardless of their parent.';
$_lang['galleryalbums.showinactive_desc'] = 'If 1, will show inactive galleries as well.';
$_lang['galleryalbums.showall_desc'] = 'If 0, will hide the album name in the album row tpl.';
$_lang['galleryalbums.start_desc'] = 'The index to start from in the results.';
$_lang['galleryalbums.sort_desc'] = 'The field to sort the results by.';
$_lang['galleryalbums.thumbfar_desc'] = 'The "far" value for phpThumb for the album cover thumbnail, for aspect ratio zooming.';
$_lang['galleryalbums.thumbheight_desc'] = 'The height of the generated album cover thumbnail, in pixels.';
$_lang['galleryalbums.thumbproperties_desc'] = 'A JSON object of parameters to pass to phpThumb as properties for the album thumbnail.';
$_lang['galleryalbums.thumbquality_desc'] = 'The "q" value for phpThumb for the album cover thumbnail, for quality.';
$_lang['galleryalbums.thumbwidth_desc'] = 'The width of the generated album cover thumbnail, in pixels.';
$_lang['galleryalbums.thumbzoomcrop_desc'] = 'Whether or not the album coverthumbnail will be zoom-cropped.';
$_lang['galleryalbums.toplaceholder_desc'] = 'If not empty, will set the output to a placeholder with this value.';

/* GalleryItem Snippet */
$_lang['galleryitem.albumrequestvar_desc'] = 'The REQUEST var to use when linking albums.';
$_lang['galleryitem.albumseparator_desc'] = 'A string separator for each album listed for the Item.';
$_lang['galleryitem.albumtpl_desc'] = 'Name of a chunk to use for each album that is listed for the Item.';
$_lang['galleryitem.id_desc'] = 'The ID of the item to display.';
$_lang['galleryitem.imagefar_desc'] = 'The "far" value for phpThumb for the image, for aspect ratio zooming.';
$_lang['galleryitem.imageheight_desc'] = 'If being used by a plugin, the max height of the generated image.';
$_lang['galleryitem.imageproperties_desc'] = 'A JSON object of parameters to pass to phpThumb as properties for the generated image.';
$_lang['galleryitem.imagequality_desc'] = 'The "q" value for phpThumb for the image, for quality.';
$_lang['galleryitem.imagewidth_desc'] = 'If being used by a plugin, the max width of the generated image.';
$_lang['galleryitem.imagezoomcrop_desc'] = 'Whether or not to use zoom cropping for the image.';
$_lang['galleryitem.tagrequestvar_desc'] = 'The REQUEST var to use when linking tags.';
$_lang['galleryitem.tagseparator_desc'] = 'A string separator for each tag listed for the Item.';
$_lang['galleryitem.tagsortdir_desc'] = 'A the direction to sort the tags listed for the Item.';
$_lang['galleryitem.tagtpl_desc'] = 'Name of a chunk to use for each tag that is listed for the Item.';
$_lang['galleryitem.toplaceholders_desc'] = 'If true, will set the properties of the Item to placeholders. If false, will use the tpl property to output a chunk.';
$_lang['galleryitem.toplaceholdersprefix_desc'] = 'Optional. The prefix to add to placeholders set by this snippet. Only works if toPlaceholders is true.';
$_lang['galleryitem.tpl_desc'] = 'Name of a chunk to use when toPlaceholders is set to false.';
$_lang['galleryitem.thumbfar_desc'] = 'The "far" value for phpThumb for the thumbnail, for aspect ratio zooming.';
$_lang['galleryitem.thumbheight_desc'] = 'The max height of the generated thumbnail, in pixels.';
$_lang['galleryitem.thumbproperties_desc'] = 'A JSON object of parameters to pass to phpThumb as properties for the thumbnail.';
$_lang['galleryitem.thumbquality_desc'] = 'The "q" value for phpThumb for the thumbnail, for quality.';
$_lang['galleryitem.thumbwidth_desc'] = 'The max width of the generated thumbnail, in pixels.';
$_lang['galleryitem.thumbzoomcrop_desc'] = 'Whether or not to use zoom cropping for the thumbnail.';
