<?php
/**
 * Loads the home page.
 *
 * @package gallery
 * @subpackage controllers
 */
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/album/albums.grid.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/sections/home.js');
$output = '<div id="gal-panel-home-div"></div>';

return $output;
