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
 * @package gallery
 * @subpackage lexicon
 */
/* Gallery Snippet */
$_lang['gallery.album_desc'] = 'Zal alleen afbeedingen van dit album laden. Kan de naam of het ID van het album zijn.';
$_lang['gallery.albumrequestvar_desc'] = 'Als checkForRequestAlbumVar waar is, zal dit zoeken naar een REQUEST variabele met deze naam om het album te selecteren.';
$_lang['gallery.checkforrequestalbumvar_desc'] = 'Wanneer 1 en een REQUEST variabele met "album" is gevonden zal dat gebruikt worden voor album instelling.';
$_lang['gallery.checkforrequesttagvar_desc'] = 'Wanneer 1 en een REQUEST variabele met "tag" is gevonden zal dat gebruikt worden voor de tag filter.';
$_lang['gallery.containertpl_desc'] = 'Een optionele chunk als buitenste template.';
$_lang['gallery.dir_desc'] = 'De sorteervolgorde richting.';
$_lang['gallery.imagefar_desc'] = 'De "far" instelling voor phpThumb voor de afbeelding.';
$_lang['gallery.imageheight_desc'] = 'Wanneer gebruikt door een plugin, de hoogte van de huidige afbeelding.';
$_lang['gallery.imagegetparam_desc'] = 'De GET parameter om te gebruiken wanneer niet direct naar een afbeelding wordt gelinkt. Zorg ervoor dat dit gelijk is aan getParam in de GalleryItem snippet.';
$_lang['gallery.imageproperties_desc'] = 'Een JSON object met parameters welke naar phpThumb worden doorgegeven.';
$_lang['gallery.imagequality_desc'] = 'De kwaliteitsinstelling voor phpThumb.';
$_lang['gallery.imagewidth_desc'] = 'Wanneer gebruikt door een plugin, de hoogte van de huidige afbeelding.';
$_lang['gallery.imagezoomcrop_desc'] = 'Wanneer door een plugin gebruikt, of de huidige afbeelding de phpThumb functie zoom-crop moet gebruiken.';
$_lang['gallery.itemcls_desc'] = 'De CSS class voor elke thumbnail.';
$_lang['gallery.limit_desc'] = 'Wanneer niet gelijk aan 0, geef alleen dat aantal items weer.';
$_lang['gallery.linktoimage_desc'] = 'Wanneer waar zal er direct gelinked worden naar de afbeelding. Wanneer onwaar zal er een GET parameter toegevoegd worden om de afbeelding met de GalleryItem snippet weer te geven.';
$_lang['gallery.plugin_desc'] = 'De naam van een plugin om te gebruiken in de front-end. Zie de documentatie voor mogelijkheden.';
$_lang['gallery.pluginpath_desc'] = 'Kon plugin "[[+name]]" niet laden in pad: [[+path]]';
$_lang['gallery.showinactive_desc'] = 'Wanner 1 zullen ook inactieve afbeeldingen worden getoond.';
$_lang['gallery.sort_desc'] = 'Het veld om afbeeldingen op te sorteren.';
$_lang['gallery.start_desc'] = 'Laad alleen items in vanaf dit aantal. Werkt gelijk als een SQL order by start functie.';
$_lang['gallery.tag_desc'] = 'Filter afbeeldingen op deze tag.';
$_lang['gallery.tagrequestvar_desc'] = 'Als checkForRequestTagVar waar is, zal er naar een REQUEST variabele met deze naam worden gezocht om een tag te selecteren.';
$_lang['gallery.toplaceholder_desc'] = 'Wanneer ingesteld zal de output naar een placeholder met deze waarde worden gezet, en output de snippet zelf niks.';
$_lang['gallery.thumbfar_desc'] = 'De "far" waarde voor phpThumb voor de thumbnail.';
$_lang['gallery.thumbheight_desc'] = 'De hoogte van de gegenereerd thumbnails in pixels.';
$_lang['gallery.thumbproperties_desc'] = 'Een JSON object met parameters welke naar phpThumb worden doorgegeven.';
$_lang['gallery.thumbquality_desc'] = 'De kwaliteitsinstelling voor phpThumb.';
$_lang['gallery.thumbtpl_desc'] = 'De Chunk om te gebruiken voor de thumbnails.';
$_lang['gallery.thumbwidth_desc'] = 'De breedte van de gegenereerd thumbnails in pixels.';
$_lang['gallery.thumbzoomcrop_desc'] = 'Of de thumbnail wel of niet gebruik moet maken van de zoom-crop functie.';
$_lang['gallery.usecss_desc'] = 'Gebruik de bijgevoegde CSS?';

/* GalleryAlbums Snippet */
$_lang['galleryalbums.albumrequestvar_desc'] = 'Als checkForRequestAlbumVar waar is, zal dit zoeken naar een REQUEST variabele met deze naam om het album te selecteren.';
$_lang['galleryalbums.albumcoversort_desc'] = 'Het veld om op te sorteren om een album cover te selecteren. Gebruik "rank" voor de eerste afbeelding, of "random" voor een willekeurig album.';
$_lang['galleryalbums.albumcoversortdir_desc'] = 'De sorteervolgorde richting (ASC of DESC).';
$_lang['galleryalbums.dir_desc'] = 'De sorteervolgorde richting (ASC of DESC).';
$_lang['galleryalbums.limit_desc'] = 'Wanneer niet gelijk aan 0, geef alleen dat aantal items weer.';
$_lang['galleryalbums.parent_desc'] = 'Laat alleen albums zien met als bovenliggend album dit ID.';
$_lang['galleryalbums.prominentonly_desc'] = 'Wanneer gelijk aan 1, laat alleen albums zien welke als "prominent" zijn gemarkeerd.';
$_lang['galleryalbums.rowcls_desc'] = 'Een CSS class om aan elke album rij toe te voegen.';
$_lang['galleryalbums.rowtpl_desc'] = 'De Chunk om te gebruiken voor elke album rij.';
$_lang['galleryalbums.showall_desc'] = 'Wanneer gelijk aan 1, laat alle albums zien onafhankelijk van het bovenliggende album.';
$_lang['galleryalbums.showinactive_desc'] = 'Wanneer gelijk aan 1, laat ook alle inactieve albums zien.';
$_lang['galleryalbums.showall_desc'] = 'Wanneer gelijk aan 0, laat alleen de album naam zien in de album row template.';
$_lang['galleryalbums.start_desc'] = 'Start met het weergeven van items bij deze index.';
$_lang['galleryalbums.sort_desc'] = 'Veld om op te sorteren.';
$_lang['galleryalbums.thumbfar_desc'] = 'De "far" waarde voor phpThumb, voor aspect ratio zooming.';
$_lang['galleryalbums.thumbheight_desc'] = 'De hoogte van de gegenereerd album thumbnails in pixels.';
$_lang['galleryalbums.thumbproperties_desc'] = 'Een JSON object met parameters welke naar phpThumb worden doorgegeven.';
$_lang['galleryalbums.thumbquality_desc'] = 'De kwaliteitsinstelling voor phpThumb.';
$_lang['galleryalbums.thumbwidth_desc'] = 'De breedte van de gegenereerd thumbnails in pixels.';
$_lang['galleryalbums.thumbzoomcrop_desc'] = 'Of de thumbnail wel of niet gebruik moet maken van de zoom-crop functie.';
$_lang['galleryalbums.toplaceholder_desc'] = 'Wanneer ingesteld zal de output naar een placeholder met deze waarde worden gezet, en output de snippet zelf niks.';

/* GalleryItem Snippet */
$_lang['galleryitem.albumrequestvar_desc'] = 'De REQUEST variabele om te gebruiken wanneer albums te linken.';
$_lang['galleryitem.albumseparator_desc'] = 'Een scheidingsteken of string om te gebruiken tussen elk album.';
$_lang['galleryitem.albumtpl_desc'] = 'Naam van een chunk om te gebruiken voor elk album dat zichtbaar is voor het item.';
$_lang['galleryitem.id_desc'] = 'Het ID van het item om weer te geven.';
$_lang['galleryitem.imagefar_desc'] = 'De "far" waarde voor phpThumb voor de afbeelding.';
$_lang['galleryitem.imageheight_desc'] = 'Wanneer gebruikt door een plugin, de hoogte van de huidige afbeelding.';
$_lang['galleryitem.imageproperties_desc'] = 'Een JSON object met parameters welke naar phpThumb worden doorgegeven.';
$_lang['galleryitem.imagequality_desc'] = 'De kwaliteitsinstelling voor phpThumb.';
$_lang['galleryitem.imagewidth_desc'] = 'Wanneer gebruikt door een plugin, de hoogte van de huidige afbeelding.';
$_lang['galleryitem.imagezoomcrop_desc'] = 'Wanneer door een plugin gebruikt, of de huidige afbeelding de phpThumb functie zoom-crop moet gebruiken.';
$_lang['galleryitem.tagrequestvar_desc'] = 'Als checkForRequestTagVar waar is, zal er naar een REQUEST variabele met deze naam worden gezocht om een tag te selecteren.';
$_lang['galleryitem.tagseparator_desc'] = 'Een scheidingsteken of string voor elke tag in het Item.';
$_lang['galleryitem.tagsortdir_desc'] = 'De sorteervolgorde voor de tags.';
$_lang['galleryitem.tagtpl_desc'] = 'Naam van de chunk om te gebruiken als template voor elke tag.';
$_lang['galleryitem.toplaceholders_desc'] = 'Wanneer waar zal de eigenschappen van items als placeholders instellen. Anders gebruikt de snippet een chunk.';
$_lang['galleryitem.toplaceholdersprefix_desc'] = 'Optioneel. Voorvoegsel voor placeholders ingesteld door de snippet, werkt alleen als toPlaceholders is ingesteld.';
$_lang['galleryitem.tpl_desc'] = 'Naam van een chunk als toPlaceholders uit staat.';
$_lang['galleryitem.thumbfar_desc'] = 'De "far" waarde voor phpThumb, voor aspect ratio zooming.';
$_lang['galleryitem.thumbheight_desc'] = 'De hoogte van de gegenereerd thumbnails in pixels.';
$_lang['galleryitem.thumbproperties_desc'] = 'Een JSON object met parameters welke naar phpThumb worden doorgegeven.';
$_lang['galleryitem.thumbquality_desc'] = 'De kwaliteitsinstelling voor phpThumb.';
$_lang['galleryitem.thumbwidth_desc'] = 'De breedte van de gegenereerd thumbnails in pixels.';
$_lang['galleryitem.thumbzoomcrop_desc'] = 'Of de thumbnail wel of niet gebruik moet maken van de zoom-crop functie.';

