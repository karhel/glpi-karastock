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
Plugin::load('karastock', true);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (isset($_POST["add"])) {

    (new PluginKarastockOrderItem)->checkGlobal(CREATE);

    PluginKarastockOrderItem::addItemsFromPOST($_POST);
    Html::back();
}
else if(isset($_POST["update"])) {

    (new PluginKarastockOrderItem)->checkGlobal(UPDATE);

    PluginKarastockOrderItem::updateFromPOST($_POST);
    Html::back();
}