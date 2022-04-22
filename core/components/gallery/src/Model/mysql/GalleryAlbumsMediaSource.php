<?php
namespace Gallery\Model\mysql;

use xPDO\xPDO;

class GalleryAlbumsMediaSource extends \Gallery\Model\GalleryAlbumsMediaSource
{

    public static $metaMap = array (
        'package' => 'Gallery\\Model',
        'version' => '3.0',
        'table' => '',
        'extends' => 'MODX\\Revolution\\Sources\\modMediaSource',
        'tableMeta' => 
        array (
            'engine' => 'MyISAM',
        ),
        'fields' => 
        array (
        ),
        'fieldMeta' => 
        array (
        ),
    );

}
