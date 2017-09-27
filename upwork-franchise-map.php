<?php
/**
 * Plugin Name: Franchise Map for Upwork Project
 * Plugin URI: https://www.upwork.com/
 * Description: Finds Franchises by County Code
 * Author: Maximo Leza
 * Author URI: https://www.upwork.com/o/profiles/users/_~012d9d1278bdc04412/
 * Version: 1.0
 * Text Domain: upwork-franchise-map
 * Domain Path: /languages
 *
 * Copyright (C) 2017 Maximo Leza.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * See <http://www.gnu.org/licenses/> for the GNU General Public License
 *
 * ███╗   ███╗ █████╗ ██╗   ██╗
 * ████╗ ████║██╔══██╗╚██╗ ██╔╝
 * ██╔████╔██║███████║ ╚████╔╝ 
 * ██║╚██╔╝██║██╔══██║ ██╔╝██╗
 * ██║ ╚═╝ ██║██║  ██║██╔╝ ╚██╗ 
 * ╚═╝     ╚═╝╚═╝  ╚═╝╚═╝   ╚═╝
 */

define( 'UPWORK_FRANCHISE_MAP_PLUGIN_DIR', dirname( __FILE__ ) );

register_activation_hook( __FILE__, 'upwork_franchise_map_activate' );

function upwork_franchise_map_activate() {
    register_uninstall_hook( __FILE__, 'upwork_franchise_map_uninstall' );
}

function upwork_franchise_map_uninstall() {
    delete_option( 'upwork_franchise' );
}


add_action( 'init', 'upwork_franchise_map_init' );

function upwork_franchise_map_init() {
    require_once UPWORK_FRANCHISE_MAP_PLUGIN_DIR . '/functions.php';
}


















