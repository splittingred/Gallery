<?php
/**
 * @package gallery
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Gallery',
    'description' => '',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.gallery.php'),
),'',true,true);
$properties = include $sources['build'].'properties/properties.gallery.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;