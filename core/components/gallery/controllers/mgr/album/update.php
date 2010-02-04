<?php
/**
 * Loads the album editing page.
 *
 * @package gallery
 * @subpackage controllers
 */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/core/modx.view.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/utils/ddview.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/album/album.items.view.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/album/album.panel.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/sections/album/update.js');
$output = '<div id="gal-panel-album-div"></div>';

return $output;
