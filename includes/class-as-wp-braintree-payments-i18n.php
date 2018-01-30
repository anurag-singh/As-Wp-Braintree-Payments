<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/includes
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'as-wp-braintree-payments',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
