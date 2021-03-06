<?php
/**
 * -------------------------------------------------------------------------
 * Karastock plugin for GLPI
 * Copyright (C) 2020 by the Karastock Development Team.
 *
 * https://github.com/pluginsGLPI/Karastock
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Karastock.
 *
 * Karastock is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * Karastock is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Karastock. If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------------
 * 
 * @package   Karastock
 * @author    Karhel Tmarr
 * @copyright Copyright (c) 2021 Karastock plugin team
 * @license   GPLv3+
 *            http://www.gnu.org/licenses/gpl.txt
 * @link      https://github.com/karhel/glpi-karastock
 * @since     2021
 * --------------------------------------------------------------------------
 */

include("../../../inc/includes.php");

(new PluginKarastockStock)->checkGlobal(READ);

if(array_key_exists('export', $_GET)) {

    PluginKarastockStock::exportReport($_GET);
    // Html::back();

} else {

    Html::header(
        __('Karastock', 'karastock'),
        $_SERVER['PHP_SELF'],
        'management',
        'PluginKarastockMenu',
        'stock'    
    );

    if (isset($_GET["model"]) && isset($_GET["type"])) {

        PluginKarastockStock::ShowModel($_GET["type"], $_GET["model"]);

    } else if (isset($_GET["type"])) {
        
        PluginKarastockStock::ShowType($_GET["type"]);

    } else {

        PluginKarastockStock::Show();
    }
    
    Html::footer();
}
