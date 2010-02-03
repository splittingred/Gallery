<?php
/**
 * Loads the home page.
 *
 * @package gallery
 * @subpackage controllers
 */
$modx->regClientStartupScript($discuss->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($discuss->config['jsUrl'].'mgr/sections/home.js');
$output = '<div id="gal-panel-home-div"></div>';

return $output;
