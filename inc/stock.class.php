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

class PluginKarastockStock extends CommonDBTM {  

    public static $rightname         = 'plugin_karastock_stock';

    // --------------------------------------------------------------------
    //  PLUGIN MANAGEMENT - DATABASE INITIALISATION
    // --------------------------------------------------------------------

    /**
     * Install or update PluginKarastockMenu
     *
     * @param Migration $migration Migration instance
     * @param string    $version   Plugin current version
     *
     * @return boolean
     */
    public static function install(Migration $migration, $version)
    {
        // DO NOTHING
    }

     /**
     * Uninstall PluginKarastockMenu
     *
     * @return boolean
     */
    public static function uninstall()
    {
        // DO NOTHING
    }    

    // --------------------------------------------------------------------
    //  GLPI PLUGIN COMMON
    // --------------------------------------------------------------------

    public static function getTypeName($nb = 0) {
        return __("Stock", "karastock");
    }

    //! @copydoc CommonDBTM::getIcon()
    static function getIcon()
    {
        return "fas fa-cubes";
    }

    static function show() {
        global $DB;
        
        $query = "SELECT count(*) as 'count', `type`, `model`, o.is_received 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.is_received = 1 
            GROUP BY `type`,`model`
            
            UNION
            
            SELECT count(*) as 'count', `type`, `model`, o.is_received 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.is_received = 0 
            GROUP BY `type`,`model` 
            ORDER BY `type`,`model`
        ";

        $result = $DB->query($query);
                
        echo "<div class='center'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<tr><th colspan='4' class='center'>" . __("Stock management", "karastock") . "</th></tr>";

        if($result) {

            echo "<tr><th class='center'>" . __('Type') . "</th>";
            echo "<th class='center'>" . __('Model') . "</th>";
            echo "<th class='center'>" . __('Count') . "</th>";
            echo "<th class='center'>" . __('Pending') . "</th></tr>";

            $number = $DB->numrows($result);            
            
            while ($data = $DB->fetch_assoc($result)) {
                              
                echo "<tr><td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "'>" . $data['type'] . "</a></td>";
                echo "<td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "&model=" . $data['model']. "'>" . $data['model'] . "</a></td>";
                echo "<td class='center'>" . $data['count'] . "</td>";
                echo "<td class='center'>" . 
                    ($data['is_received'] == 1 
                    ? "<i class='fas fa-check'></i>" 
                    : "<i class='fas fa-shipping-fast'></i>" ) 
                . "</td></tr>";
            }
        }
        
        echo "</table></div>";
    }

    static function showType($type)
    {
        global $DB;
        
        $query = "SELECT count(*) as 'count', `type`, `model`, `tickets_id`, `plugin_karastock_orders_id`, o.`is_received` 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.`is_received` = 1 AND oi.type = '$type'
            GROUP BY `type`,`model`
            
            UNION
            
            SELECT count(*) as 'count', `type`, `model`, `tickets_id`, `plugin_karastock_orders_id`, o.`is_received` 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.`is_received` = 0 AND oi.type = '$type'
            GROUP BY `type`,`model` 
            ORDER BY `type`,`model`
        ";

        $result = $DB->query($query);
                
        echo "<div class='center'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<tr><th colspan='6' class='center'>" . __("Stock management", "karastock") . " - " . __('Type')  . " : ". $type . "</th></tr>";

        if($result) {

            echo "<tr><th class='center'>" . __('Order ID') . "</th>";
            echo "<th class='center'>" . __('Type') . "</th>";
            echo "<th class='center'>" . __('Model') . "</th>";
            echo "<th class='center'>" . __('Count') . "</th>";
            echo "<th class='center'>" . __('Ticket') . "</th>";
            echo "<th class='center'>" . __('Pending') . "</th></tr>";

            $number = $DB->numrows($result);            
            
            while ($data = $DB->fetch_assoc($result)) {

                echo "<tr><td class='center'><a href='". PluginKarastockOrder::getFormURLWithID($data[PluginKarastockOrder::getForeignKeyField()]) ."'>" . $data[PluginKarastockOrder::getForeignKeyField()] . "</a></td>";
                echo "<td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "'>" . $data['type'] . "</a></td>";
                echo "<td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "&model=" . $data['model']. "'>" . $data['model'] . "</a></td>";
                echo "<td class='center'>" . $data['count'] . "</td>";
                echo "<td class='center'>";
                $ticketId = $data['tickets_id'];
                if($ticketId > 0) {
                    $ticket = new Ticket();
                    $ticket->getFromDB($ticketId);

                    echo "<a href='". $ticket->getLinkURL() ."'>" . $ticketId . "</a>";
                }
                echo"</td>";
                echo "<td class='center'>" . 
                    ($data['is_received'] == 1 
                    ? "<i class='fas fa-check'></i>" 
                    : "<i class='fas fa-shipping-fast'></i>" ) 
                . "</td></tr>";
            }
        }
        
        echo "</table></div>";
    }

    static function showModel($type, $model) {

        global $DB;
        
        $query = "SELECT count(*) as 'count', `type`, `model`, `tickets_id`, `plugin_karastock_orders_id`, o.`is_received` 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.`is_received` = 1 AND oi.type = '$type' AND oi.model = '$model'
            GROUP BY `type`,`model`
            
            UNION
            
            SELECT count(*) as 'count', `type`, `model`, `tickets_id`, `plugin_karastock_orders_id`, o.`is_received` 
            FROM glpi_plugin_karastock_orderitems as oi 
            INNER JOIN glpi_plugin_karastock_orders as o on o.`id` = oi.`plugin_karastock_orders_id` 
            WHERE is_out_of_stock = 0 AND o.`is_received` = 0 AND oi.type = '$type' AND oi.model = '$model'
            GROUP BY `type`,`model` 
            ORDER BY `type`,`model`
        ";

        $result = $DB->query($query);
                
        echo "<div class='center'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<tr><th colspan='6' class='center'>" . __("Stock management", "karastock") . " - " . __('Type')  . " : ". $type . " - " . __('Model') . " : " . $model . "</th></tr>";

        if($result) {

            echo "<tr><th class='center'>" . __('Order ID') . "</th>";
            echo "<th class='center'>" . __('Type') . "</th>";
            echo "<th class='center'>" . __('Model') . "</th>";
            echo "<th class='center'>" . __('Count') . "</th>";
            echo "<th class='center'>" . __('Ticket') . "</th>";
            echo "<th class='center'>" . __('Pending') . "</th></tr>";

            $number = $DB->numrows($result);            
            
            while ($data = $DB->fetch_assoc($result)) {

                echo "<tr><td class='center'><a href='". PluginKarastockOrder::getFormURLWithID($data[PluginKarastockOrder::getForeignKeyField()]) ."'>" . $data[PluginKarastockOrder::getForeignKeyField()] . "</a></td>";
                echo "<td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "'>" . $data['type'] . "</a></td>";
                echo "<td class='center'><a href='".Toolbox::getItemTypeSearchURL('PluginKarastockStock')."?type=" . $data['type']. "&model=" . $data['model']. "'>" . $data['model'] . "</a></td>";
                echo "<td class='center'>" . $data['count'] . "</td>";
                echo "<td class='center'>";
                $ticketId = $data['tickets_id'];
                if($ticketId > 0) {
                    $ticket = new Ticket();
                    $ticket->getFromDB($ticketId);

                    echo "<a href='". $ticket->getLinkURL() ."'>" . $ticketId . "</a>";
                }
                echo"</td>";
                echo "<td class='center'>" . 
                    ($data['is_received'] == 1 
                    ? "<i class='fas fa-check'></i>" 
                    : "<i class='fas fa-shipping-fast'></i>" ) 
                . "</td></tr>";
            }
        }
        
        echo "</table></div>";
    }
}