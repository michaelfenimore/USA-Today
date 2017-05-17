<?php
/*
Plugin Name: USA Today Test Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This plugin enables a content box on every page with the latest news story from your site.
Version:     1.0.0
Author:      Michael Fenimore
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

//No direct Access to this plugin
if ( !defined( 'ABSPATH' ) ) {
	 die;
}

function usat_plugin_activation() {
	require_once( plugin_dir_path( __FILE__ ) . 'usat-activate-class.php' );
	Usat_Activator::usat_activate();
}

function usat_plugin_deactivation() {
	require_once( plugin_dir_path( __FILE__ ) . 'usat-deactivate-class.php' );
	Usat_Deactivator::usat_deactivate();
}

//The core plugin class
require plugin_dir_path( __FILE__ ) . 'usat-class.php';

register_activation_hook( __FILE__, usat_plugin_activation() );
register_deactivation_hook( __FILE__, usat_plugin_deactivation() );

add_action( 'init', array( 'Usa_today', 'init' ) );
