<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://anuragsingh.me
 * @since             1.0.0
 * @package           As_Wp_Braintree_Payments
 *
 * @wordpress-plugin
 * Plugin Name:       As Wp Braintree Payments
 * Plugin URI:        http://anuragsingh.me
 * Description:       Add Braintree payments in wordpress site. Page and shortcode to display content (Page Name : packages | Shortcode : packages) (Page Name : payment | Shortcode : payment) (Page Name : success | Shortcode : success)
 * Version:           1.0.0
 * Author:            Anurag Singh
 * Author URI:        http://anuragsingh.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       as-wp-braintree-payments
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * Custom Post Types (CPT) to add.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Add all CPT to register at time of plugin registration.
 */
define( 'CPT_NAMES', serialize(array('subscription', 'payment')) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-as-wp-braintree-payments-activator.php
 */
function activate_as_wp_braintree_payments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-as-wp-braintree-payments-activator.php';
	As_Wp_Braintree_Payments_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-as-wp-braintree-payments-deactivator.php
 */
function deactivate_as_wp_braintree_payments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-as-wp-braintree-payments-deactivator.php';
	As_Wp_Braintree_Payments_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_as_wp_braintree_payments' );
register_deactivation_hook( __FILE__, 'deactivate_as_wp_braintree_payments' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-as-wp-braintree-payments.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_as_wp_braintree_payments() {

	$plugin = new As_Wp_Braintree_Payments();
	$plugin->run();

}
run_as_wp_braintree_payments();
