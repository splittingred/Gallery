<?php
/**
 * Sort two items
 *
 * @package gallery
 * @subpackage processors
 */
$source = $modx->getObject('galAlbumItem',array(
    'album' => $_POST['album'],
    'item' => $_POST['source'],
));
if (empty($source)) return $modx->error->failure();

$target = $modx->getObject('galAlbumItem',array(
    'album' => $_POST['album'],
    'item' => $_POST['target'],
));
if (empty($target)) return $modx->error->failure();

if ($source->get('rank') < $target->get('rank')) {
    $modx->exec("
        UPDATE {$modx->getTableName('galAlbumItem')}
            SET rank = rank - 1
        WHERE
            album = ".$_POST['album']."
        AND rank <= {$target->get('rank')}
        AND rank > {$source->get('rank')}
        AND rank > 0
    ");
    $newRank = $target->get('rank');
} else {
    $modx->exec("
        UPDATE {$modx->getTableName('galAlbumItem')}
            SET rank = rank + 1
        WHERE
            album = ".$_POST['album']."
        AND rank >= {$target->get('rank')}
        AND rank < {$source->get('rank')}
    ");
    $newRank = $target->get('rank');
}
$source->set('rank',$newRank);
$source->save();

return $modx->error->success();