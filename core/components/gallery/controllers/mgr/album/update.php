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
 * @var modX $modx
 * @var Gallery $gallery
 */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/core/modx.view.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/utils/ddview.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/utils/fileuploader.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/album/album.items.view.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/widgets/album/album.panel.js');
$modx->regClientStartupScript($gallery->config['jsUrl'].'mgr/sections/album/update.js');
$modx->regClientCSS($gallery->config['cssUrl'].'fileuploader.css');

/* If we want to use Tiny, we'll need some extra files. */
$useTiny = $modx->getOption('gallery.use_richtext',$gallery->config,false);
if ($useTiny) {
    $tinyCorePath = $modx->getOption('tiny.core_path',null,$modx->getOption('core_path').'components/tinymce/');
    if (file_exists($tinyCorePath.'tinymce.class.php')) {

        /* First fetch the gallery+tiny specific settings */
        $cb1 =  $modx->getOption('gallery.tiny.buttons1',null,'undo,redo,selectall,pastetext,pasteword,charmap,separator,image,modxlink,unlink,media,separator,code,help');
        $cb2 =  $modx->getOption('gallery.tiny.buttons2',null,'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull');
        $cb3 =  $modx->getOption('gallery.tiny.buttons3',null,'styleselect,formatselect,separator,styleprops');
        $cb4 =  $modx->getOption('gallery.tiny.buttons4',null,'');
        $cb5 =  $modx->getOption('gallery.tiny.buttons5',null,'');
        $plugins =  $modx->getOption('gallery.tiny.custom_plugins',null,'');
        $theme =  $modx->getOption('gallery.tiny.theme',null,'');
        $bfs =  $modx->getOption('gallery.tiny.theme_advanced_blockformats',null,'');
        $css =  $modx->getOption('gallery.tiny.theme_advanced_css_selectors',null,'');

        /** @var modAction $browserAction */
        $browserAction = $modx->getObject('modAction',array('controller' => 'browser'));

        /* If the settings are empty, override them with the generic tinymce settings. */
        $tinyProperties = array(
            'accessibility_warnings' => false,
            'browserUrl' => $browserAction ? $modx->getOption('manager_url',null,MODX_MANAGER_URL).'index.php?a='.$browserAction->get('id').'&source=1' : null,
            'cleanup' => true,
            'cleanup_on_startup' => false,
            'compressor' => '',
            'execcommand_callback' => 'Tiny.onExecCommand',
            'file_browser_callback' => 'Tiny.loadBrowser',
            'force_p_newlines' => true,
            'force_br_newlines' => false,
            'formats' => array(
                'alignleft' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyleft'),
                'alignright' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyright'),
                'alignfull' => array('selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', 'classes' => 'justifyfull'),
            ),
            'frontend' => false,
            'plugin_insertdate_dateFormat' => '%Y-%m-%d',
            'plugin_insertdate_timeFormat' => '%H:%M:%S',
            'preformatted' => false,
            'resizable' => true,
            'relative_urls' => true,
            'remove_script_host' => true,
            'theme_advanced_disable' => '',
            'theme_advanced_resizing' => true,
            'theme_advanced_resize_horizontal' => true,
            'theme_advanced_statusbar_location' => 'bottom',
            'theme_advanced_toolbar_align' => 'left',
            'theme_advanced_toolbar_location' => 'top',


            'height' => $modx->getOption('gallery.tiny.height',null,200),
            'width' => $modx->getOption('gallery.tiny.width',null,'95%'),
            'tiny.custom_buttons1' => (!empty($cb1)) ? $cb1 : $modx->getOption('tiny.custom_buttons1',null,'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help'),
            'tiny.custom_buttons2' => (!empty($cb2)) ? $cb2 : $modx->getOption('tiny.custom_buttons2',null,'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops'),
            'tiny.custom_buttons3' => (!empty($cb3)) ? $cb3 : $modx->getOption('tiny.custom_buttons3',null,''),
            'tiny.custom_buttons4' => (!empty($cb4)) ? $cb4 : $modx->getOption('tiny.custom_buttons4',null,''),
            'tiny.custom_buttons5' => (!empty($cb5)) ? $cb5 : $modx->getOption('tiny.custom_buttons5',null,''),
            'tiny.custom_plugins' => (!empty($plugins)) ? $plugins : $modx->getOption('tiny.custom_plugins',null,'style,advimage,advlink,modxlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media'),
            'tiny.editor_theme' => (!empty($theme)) ? $theme : $modx->getOption('tiny.editor_theme',null,'cirkuit'),
            'tiny.skin_variant' => $modx->getOption('tiny.skin_variant',null,''),
            'tiny.theme_advanced_blockformats' => (!empty($bfs)) ? $bfs : $modx->getOption('tiny.theme_advanced_blockformats',null,'p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address'),
            'tiny.css_selectors' => (!empty($css)) ? $css : $modx->getOption('tiny.css_selectors',null,''),
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
