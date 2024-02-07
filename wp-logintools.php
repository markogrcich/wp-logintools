<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://grcicmarko.com
 * @since             1.0.0
 * @package           Wplt
 *
 * @wordpress-plugin
 * Plugin Name:       Login Tools
 * Plugin URI:        https://grcicmarko.com
 * Description:       Provides login tools for WordPress, Front-end login, account settings and much more.
 * Version:           1.0.0
 * Author:            Marko Grcic
 * Author URI:        https://grcicmarko.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wplt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WPLT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wplt-activator.php
 */
function activate_wplt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wplt-activator.php';
	Wplt_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_wplt' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wplt-deactivator.php
 */
function deactivate_wplt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wplt-deactivator.php';
	Wplt_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_wplt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wplt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wplt() {
	$plugin = new Wplt();
	$plugin->run();
}
run_wplt();
