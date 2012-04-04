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
 * Build Schema script
 *
 * @package gallery
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* setup paths */
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/';
$sources = array(
    'root' => $root,
    'build' => $root.'_build/',
    'core' => $root.'core/components/gallery/',
    'model' => $root.'core/components/gallery/model/',
    'assets' => $root.'assets/components/gallery/',
);

require_once $sources['build'].'/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$manager= $modx->getManager();
$generator= $manager->getGenerator();

$generator->classTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
class [+class+] extends [+extends+] {}
?>
EOD;
$generator->platformTemplate= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\\\', '/') . '/[+class-lowercase+].class.php');
class [+class+]_[+platform+] extends [+class+] {}
?>
EOD;
$generator->mapHeader= <<<EOD
<?php
/**
 * [+phpdoc-package+]
 */
EOD;

if (!is_dir($sources['model'])) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Model directory not found!');
    die();
}
$generator->parseSchema(dirname(__FILE__) . '/gallery.mysql.schema.xml',$sources['model']);


$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();