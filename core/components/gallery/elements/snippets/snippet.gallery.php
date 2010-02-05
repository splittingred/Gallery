<?php
/**
 * @package gallery
 */
$gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
if (!($gallery instanceof Gallery)) return '';

/* setup default properties */
$album = $modx->getOption('album',$scriptProperties,false);
$plugin = $modx->getOption('plugin',$scriptProperties,'');
$tag = $modx->getOption('tag',$scriptProperties,'');
$limit = $modx->getOption('limit',$scriptProperties,0);
$start = $modx->getOption('start',$scriptProperties,0);
$sort = $modx->getOption('sort',$scriptProperties,'rank');
$sortAlias = $modx->getOption('sortAlias',$scriptProperties,'galItem');
if ($sort == 'rank') $sortAlias = 'AlbumItems';
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* check for REQUEST vars if property set */
if ($modx->getOption('checkForRequestAlbumVar',$scriptProperties,false)) {
    $album = $modx->getOption('album',$_REQUEST,$album);
}
if ($modx->getOption('checkForRequestTagVar',$scriptProperties,false)) {
    $album = $modx->getOption('tag',$_REQUEST,$tag);
}

/* build query */
$c = $modx->newQuery('galItem');
$c->select('
    `galItem`.*,
    (SELECT GROUP_CONCAT(`TagsJoin`.`tag`) FROM '.$modx->getTableName('galTag').' AS `TagsJoin`
     WHERE `TagsJoin`.`item` = `galItem`.`id`) AS `tags`
');
$c->innerJoin('galAlbumItem','AlbumItems');
$c->innerJoin('galAlbum','Album','`AlbumItems`.`album` = `Album`.`id`');

/* pull by album */
if (!empty($album)) {
    $albumField = is_numeric($album) ? 'id' : 'name';

    $albumWhere = $albumField == 'name' ? array('name' => $album) : $album;
    $album = $modx->getObject('galAlbum',$albumWhere);
    if (empty($album)) return '';
    $c->where(array(
        'Album.'.$albumField => $album->get($albumField),
    ));
    $galleryName = $album->get('name');
    $galleryDescription = $album->get('description');
    unset($albumWhere,$albumField);
}
if (!empty($tag)) { /* pull by tag */
    $c->innerJoin('galTag','Tags');
    $c->where(array(
        'Tags.tag' => $tag,
    ));
    if (empty($album)) {
        $galleryName = $tag;
        $galleryDescription = '';
    }
}
$c->where(array(
    'mediatype' => $modx->getOption('mediatype',$scriptProperties,'image'),
));

$c->sortby($sortAlias.'.'.$sort,$dir);
if (!empty($limit)) $c->where($limit,$start);
$items = $modx->getCollection('galItem',$c);

/* load plugins */
if (!empty($plugin)) {
    if (($className = $modx->loadClass('gallery.plugins.'.$plugin,$gallery->config['modelPath'],true,true))) {
        $plugin = new $className($gallery,$scriptProperties);
        $plugin->load();
        $scriptProperties = $plugin->adjustSettings($scriptProperties);
    }
} else {
    if ($modx->getOption('useCss',$scriptProperties,true)) {
        $modx->regClientCSS($gallery->config['cssUrl'].'web.css');
    }
}

/* iterate */
$output = '';
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $itemArray['filename'] = basename($item->get('filename'));
    $itemArray['filesize'] = $item->get('filesize');
    $itemArray['thumbnail'] = $item->get('thumbnail',array(
        'w' => $modx->getOption('thumbWidth',$scriptProperties,100),
        'h' => $modx->getOption('thumbHeight',$scriptProperties,100),
        'zc' => 1,
    ));
    $itemArray['image'] = $item->get('thumbnail',array(
        'w' => $modx->getOption('imageWidth',$scriptProperties,500),
        'h' => $modx->getOption('imageHeight',$scriptProperties,500),
        'zc' => 0,
    ));

    $output .= $gallery->getChunk($modx->getOption('thumbTpl',$scriptProperties,'galItemThumb'),$itemArray);
}

$containerTpl = $modx->getOption('containerTpl',$scriptProperties,false);
if (!empty($containerTpl)) {
    $ct = $gallery->getChunk($containerTpl,array(
        'thumbnails' => $output,
        'album_name' => $galleryName,
        'album_description' => $galleryDescription,
    ));
    if (!empty($ct)) $output = $ct;
}

if ($toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false)) {
    $modx->toPlaceholders(array(
        $toPlaceholder => $output,
        $toPlaceholder.'.name' => $galleryName,
        $toPlaceholder.'.description' => $galleryDescription,
    ));
} else {
    return $output;
}
return '';