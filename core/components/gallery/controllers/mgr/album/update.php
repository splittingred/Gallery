<?php
/**
 * Gallery
 *
 * Copyright 2010-2011 by Shaun McCormick <shaun@modx.com>
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

/* If we want to use Tiny, we'll need some extra files. */
$useTiny = $modx->getOption('gallery.use_richtext',$gallery->config,false);
if ($useTiny) {
    $tinyCorePath = $modx->getOption('tiny.core_path',null,$modx->getOption('core_path').'components/tinymce/');
    if (file_exists($tinyCorePath.'tinymce.class.php')) {

        /* First fetch the gallery+tiny specific settings */
        $cb1 =  $modx->getOption('gallery.tiny.buttons1');
        $cb2 =  $modx->getOption('gallery.tiny.buttons2');
        $cb3 =  $modx->getOption('gallery.tiny.buttons3');
        $cb4 =  $modx->getOption('gallery.tiny.buttons4');
        $cb5 =  $modx->getOption('gallery.tiny.buttons5');
        $plugins =  $modx->getOption('gallery.tiny.custom_plugins');
        $theme =  $modx->getOption('gallery.tiny.theme');
        $bfs =  $modx->getOption('gallery.tiny.theme_advanced_blockformats');
        $css =  $modx->getOption('gallery.tiny.theme_advanced_css_selectors');

        /* If the settings are empty, override them with the generic tinymce settings. */
        $tinyProperties = array(
            'height' => $modx->getOption('gallery.tiny.height',null,200),
            'width' => $modx->getOption('gallery.tiny.width',null,400),
            'tiny.custom_buttons1' => (!empty($cb1)) ? $cb1 : $modx->getOption('tiny.custom_buttons1'),
            'tiny.custom_buttons2' => (!empty($cb2)) ? $cb2 : $modx->getOption('tiny.custom_buttons2'),
            'tiny.custom_buttons3' => (!empty($cb3)) ? $cb3 : $modx->getOption('tiny.custom_buttons3'),
            'tiny.custom_buttons4' => (!empty($cb4)) ? $cb4 : $modx->getOption('tiny.custom_buttons4'),
            'tiny.custom_buttons5' => (!empty($cb5)) ? $cb5 : $modx->getOption('tiny.custom_buttons5'),
            'tiny.custom_plugins' => (!empty($plugins)) ? $plugins : $modx->getOption('tiny.custom_plugins'),
            'tiny.editor_theme' => (!empty($theme)) ? $theme : $modx->getOption('tiny.editor_theme'),
            'tiny.theme_advanced_blockformats' => (!empty($bfs)) ? $bfs : $modx->getOption('tiny.theme_advanced_blockformats'),
            'tiny.css_selectors' => (!empty($css)) ? $css : $modx->getOption('tiny.css_selectors'),
        );
        
        require_once $tinyCorePath.'tinymce.class.php';
        $tiny = new TinyMCE($modx,$tinyProperties);
        $tiny->setProperties($tinyProperties);
        $html = $tiny->initialize();
        $modx->regClientHTMLBlock($html);
    }
}

$output = '<div id="gal-panel-album-div"></div>';

return $output;
