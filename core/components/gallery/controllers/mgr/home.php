<?php
/**
 * Gallery
 *
 * Copyright 2010 by Shaun McCormick <shaun@collabpad.com>
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
