<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blockvis.com
 * @since             1.0.0
 * @package           Civic_Sip
 *
 * @wordpress-plugin
 * Plugin Name:       Civic SIP
 * Plugin URI:        https://github.com/blockvis/civic-sip-wp
 * Description:       Civic Secure Identity Platform (SIP) authorization plugin. Sign in to your blog using Civic Mobile App.
 * Version:           2.0.1
 * Author:            Blockvis
 * Author URI:        https://blockvis.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       civic-sip
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CIVIC_SIP_PLUGIN_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-civic-sip-activator.php
 */
function activate_civic_sip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-civic-sip-activator.php';
	Civic_Sip_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-civic-sip-deactivator.php
 */
function deactivate_civic_sip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-civic-sip-deactivator.php';
	Civic_Sip_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_civic_sip' );
register_deactivation_hook( __FILE__, 'deactivate_civic_sip' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-civic-sip.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_civic_sip() {

	$plugin = new Civic_Sip();
	$plugin->run();

}

run_civic_sip();
