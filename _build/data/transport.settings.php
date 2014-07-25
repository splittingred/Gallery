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
 * Loads system settings
 *
 * @package gallery
 * @subpackage build
 */
$settings = array();

$settings['gallery.backend_thumb_far']= $modx->newObject('modSystemSetting');
$settings['gallery.backend_thumb_far']->fromArray(array(
    'key' => 'gallery.backend_thumb_far',
    'value' => 'C',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'backend',
),'',true,true);

$settings['gallery.backend_thumb_height']= $modx->newObject('modSystemSetting');
$settings['gallery.backend_thumb_height']->fromArray(array(
    'key' => 'gallery.backend_thumb_height',
    'value' => '80',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'backend',
),'',true,true);

$settings['gallery.backend_thumb_width']= $modx->newObject('modSystemSetting');
$settings['gallery.backend_thumb_width']->fromArray(array(
    'key' => 'gallery.backend_thumb_width',
    'value' => '100',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'backend',
),'',true,true);

$settings['gallery.backend_thumb_zoomcrop']= $modx->newObject('modSystemSetting');
$settings['gallery.backend_thumb_zoomcrop']->fromArray(array(
    'key' => 'gallery.backend_thumb_zoomcrop',
    'value' => 1,
    'xtype' => 'combo-boolean',
    'namespace' => 'gallery',
    'area' => 'backend',
),'',true,true);

$settings['gallery.default_batch_upload_path']= $modx->newObject('modSystemSetting');
$settings['gallery.default_batch_upload_path']->fromArray(array(
    'key' => 'gallery.default_batch_upload_path',
    'value' => '{assets_path}images/',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'backend',
),'',true,true);

$settings['xhtml_urls']= $modx->newObject('modSystemSetting');
$settings['xhtml_urls']->fromArray(array(
    'key' => 'xhtml_urls',
    'value' => 0,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'furls',
),'',true,true);

$settings['gallery.thumbs_prepend_site_url']= $modx->newObject('modSystemSetting');
$settings['gallery.thumbs_prepend_site_url']->fromArray(array(
    'key' => 'gallery.thumbs_prepend_site_url',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'gallery',
    'area' => '',
),'',true,true);

$settings['gallery.mediaSource']= $modx->newObject('modSystemSetting');
$settings['gallery.mediaSource']->fromArray(array(
    'key' => 'gallery.mediaSource',
    'value' => 1,
    'xtype' => 'modx-combo-source',
    'namespace' => 'gallery',
    'area' => '',
),'',true,true);

/*
$settings['gallery.']= $modx->newObject('modSystemSetting');
$settings['gallery.']->fromArray(array(
    'key' => 'gallery.',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => '',
),'',true,true);
*/

/* Settings for the TinyMCE integration */
$settings['gallery.use_richtext']= $modx->newObject('modSystemSetting');
$settings['gallery.use_richtext']->fromArray(array(
    'key' => 'gallery.use_richtext',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.width']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.width']->fromArray(array(
    'key' => 'gallery.tiny.width',
    'value' => '95%',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.height']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.height']->fromArray(array(
    'key' => 'gallery.tiny.height',
    'value' => 200,
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.buttons1']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.buttons1']->fromArray(array(
    'key' => 'gallery.tiny.buttons1',
    'value' => 'undo,redo,selectall,pastetext,pasteword,charmap,separator,image,modxlink,unlink,media,separator,code,help',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.buttons2']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.buttons2']->fromArray(array(
    'key' => 'gallery.tiny.buttons2',
    'value' => 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.buttons3']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.buttons3']->fromArray(array(
    'key' => 'gallery.tiny.buttons3',
    'value' => 'styleselect,formatselect,separator,styleprops',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.buttons4']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.buttons4']->fromArray(array(
    'key' => 'gallery.tiny.buttons4',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.buttons5']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.buttons5']->fromArray(array(
    'key' => 'gallery.tiny.buttons5',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.custom_plugins']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.custom_plugins']->fromArray(array(
    'key' => 'gallery.tiny.custom_plugins',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.theme']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.theme']->fromArray(array(
    'key' => 'gallery.tiny.theme',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.theme_advanced_blockformats']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.theme_advanced_blockformats']->fromArray(array(
    'key' => 'gallery.tiny.theme_advanced_blockformats',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

$settings['gallery.tiny.theme_advanced_css_selectors']= $modx->newObject('modSystemSetting');
$settings['gallery.tiny.theme_advanced_css_selectors']->fromArray(array(
    'key' => 'gallery.tiny.theme_advanced_css_selectors',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'gallery',
    'area' => 'TinyMCE',
),'',true,true);

return $settings;