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
 * Resolve changes to db model
 *
 * @var xPDOObject $object
 * @var array $options
 *
 * @package gallery
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modelPath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/';
            $modx->addPackage('gallery',$modelPath);
            $manager = $modx->getManager();
            $oldLogLevel = $modx->getLogLevel();
            $modx->setLogLevel(0);

            $manager->addField('galAlbum','parent',array('after' => 'id'));
            $manager->addIndex('galAlbum','parent');
            $manager->addField('galItem','url',array('after' => 'mediatype'));

            /* 1.6.0+ */
            $manager->addField('galItem','slug',array('after' => 'mediatype'));
            $manager->addIndex('galItem','slug');
            $manager->addIndex('galItem','name');
            $manager->addIndex('galItem','active');
            $manager->addIndex('galItem','mediatype');

            $manager->addIndex('galAlbum','rank');
            $manager->addIndex('galAlbum','active');
            $manager->addIndex('galAlbum','prominent');

            $manager->addIndex('galAlbumItem','rank');
            $manager->addField('galAlbum', 'year'); 

            /* 1.5.3+ */
            $manager->addField('galAlbum','cover_filename');


            $modx->setLogLevel($oldLogLevel);
            break;
    }
}
return true;