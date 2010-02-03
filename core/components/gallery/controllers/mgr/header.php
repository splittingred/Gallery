<?php
/**
 * Loads the header for mgr pages.
 *
 * @package gallery
 * @subpackage controllers
 */
$modx->regClientCSS($gallery->config['cssUrl'].'mgr.css');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/gallery.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/combos.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/windows.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    GAL.config = '.$modx->toJSON($gallery->config).';
    GAL.config.connector_url = "'.$gallery->config['connectorUrl'].'";
    GAL.request = '.$modx->toJSON($_GET).';
});
</script>');

return '';