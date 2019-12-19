<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/oliwright1994
 * @since             1.0.0
 * @package           Eag_functionality
 *
 * @wordpress-plugin
 * Plugin Name:       EAG Functionality Plugin
 * Plugin URI:        https://github.com/oliwright1994/EAG-Functionality-Plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Oliver Wright
 * Author URI:        https://github.com/oliwright1994
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eag_functionality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EAG_FUNCTIONALITY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eag_functionality-activator.php
 */
function activate_eag_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality-activator.php';
	Eag_functionality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eag_functionality-deactivator.php
 */
function deactivate_eag_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality-deactivator.php';
	Eag_functionality_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eag_functionality' );
register_deactivation_hook( __FILE__, 'deactivate_eag_functionality' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_eag_functionality() {

	$plugin = new Eag_functionality();
	$plugin->run();

}
run_eag_functionality();
