<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/tokkonopapa
 * @since             1.0.0
 * @package           IPGB_Tester
 *
 * @wordpress-plugin
 * Plugin Name:       IPGB Tester
 * Plugin URI:        https://github.com/tokkonopapa/WordPress-IP-Geo-Tester
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            tokkonopapa
 * Author URI:        https://github.com/tokkonopapa
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ipgb-tester
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'WPINC' ) or die;

// Slug, path, base
define( 'IPGB_TESTER_SLUG', 'ipgb-tester' );
define( 'IPGB_TESTER_PATH', plugin_dir_path( __FILE__ ) ); // @since 2.8
define( 'IPGB_TESTER_BASE', plugin_basename( __FILE__ ) ); // @since 1.5

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ipgb-tester-activator.php
 */
function activate_IPGB_Tester() {
	require_once IPGB_TESTER_PATH . 'includes/class-ipgb-tester-activator.php';
	IPGB_Tester_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ipgb-tester-deactivator.php
 */
function deactivate_IPGB_Tester() {
	require_once IPGB_TESTER_PATH . 'includes/class-ipgb-tester-deactivator.php';
	IPGB_Tester_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_IPGB_Tester' );
register_deactivation_hook( __FILE__, 'deactivate_IPGB_Tester' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require IPGB_TESTER_PATH . 'includes/class-ipgb-tester.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_IPGB_Tester() {

	$plugin = new IPGB_Tester();
	$plugin->run();

}
run_IPGB_Tester();
