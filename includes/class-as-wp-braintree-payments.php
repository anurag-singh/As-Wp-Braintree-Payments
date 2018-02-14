<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/includes
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      As_Wp_Braintree_Payments_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The Custom Post Types (CPTs) of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $cpt_names    The ID of this plugin.
	 */
	private $cpt_names;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'as-wp-braintree-payments';

		if(defined('CPT_NAMES')){
			$this->cpt_names = CPT_NAMES;
		} else {
			$this->cpt_names = '';
		}
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_meta_box_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - As_Wp_Braintree_Payments_Loader. Orchestrates the hooks of the plugin.
	 * - As_Wp_Braintree_Payments_i18n. Defines internationalization functionality.
	 * - As_Wp_Braintree_Payments_Admin. Defines all hooks for the admin area.
	 * - As_Wp_Braintree_Payments_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-as-wp-braintree-payments-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-as-wp-braintree-payments-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-as-wp-braintree-payments-admin.php';

		/**
		 * The class responsible for defining all actions related to meta box.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-as-wp-braintree-payments-meta-box.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-as-wp-braintree-payments-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/braintree/includes/braintree_init.php';

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		$this->loader = new As_Wp_Braintree_Payments_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the As_Wp_Braintree_Payments_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new As_Wp_Braintree_Payments_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new As_Wp_Braintree_Payments_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_cpt_names() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_cpt' );

	}

	/**
	 * Register all of the hooks related to meta box.
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_meta_box_hooks() {

		$plugin_meta_box = new As_Wp_Braintree_Payments_Meta_Box( $this->get_plugin_name(), $this->get_version(), $this->get_cpt_names() );

		$this->loader->add_action('add_meta_boxes', $plugin_meta_box, 'add_meta_box');
		$this->loader->add_action('save_post', $plugin_meta_box, 'save_meta_box');
		$this->loader->add_action('the_content', $plugin_meta_box, 'display_meta_box_content');


		$this->loader->add_action('add_meta_boxes', $plugin_meta_box, 'add_transaction_details_meta_box_for_cpt_payment');
		$this->loader->add_action('add_meta_boxes', $plugin_meta_box, 'add_payment_details_meta_box_for_cpt_payment');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new As_Wp_Braintree_Payments_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode( 'packages', $plugin_public, 'render_packages' );
		$this->loader->add_shortcode( 'payment', $plugin_public, 'render_payment_form' );
		$this->loader->add_shortcode( 'success', $plugin_public, 'payment_success' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    As_Wp_Braintree_Payments_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the cpt names of the plugin.
	 *
	 * @since     1.0.0
	 * @return    array    The version number of the plugin.
	 */
	public function get_cpt_names() {
		return $this->cpt_names;
	}

}
