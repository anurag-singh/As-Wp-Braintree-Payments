<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/admin
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Custom Post Types (CPTs) of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $cpt_names    The ID of this plugin.
	 */
	private $cpt_names;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $cpt_names ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->cpt_names = $cpt_names;



	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in As_Wp_Braintree_Payments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The As_Wp_Braintree_Payments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/as-wp-braintree-payments-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in As_Wp_Braintree_Payments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The As_Wp_Braintree_Payments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/as-wp-braintree-payments-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Creates a new Custom Post Type.
	 *
	 * @since    1.0.0
	 */

	public function register_cpt(){
		$cpt_names = $this->cpt_names;

		$cpt_names = unserialize($cpt_names);

		foreach ($cpt_names as $cpt_name) {
			$single = ucwords(strtolower(preg_replace('/\s+/', ' ', $cpt_name) ));

			$last_character = substr($single, -1);

			if ($last_character === 'y') {
				$plural = substr_replace($single, 'ies', -1);
			}
			else {
				$plural = $single.'s'; 		// add 's' to convert singular name to plural
			}
			$cap_type = 'post';

			$opts['can_export'] = TRUE;
			$opts['capability_type'] = $cap_type;
			$opts['description'] = '';
			$opts['exclude_from_search'] = FALSE;
			$opts['has_archive'] = FALSE;
			$opts['hierarchical'] = FALSE;
			$opts['map_meta_cap'] = TRUE;
			$opts['menu_icon'] = 'dashicons-admin-post';
			$opts['menu_position'] = 25;
			$opts['public'] = TRUE;
			$opts['publicly_querable'] = TRUE;
			$opts['query_var'] = TRUE;
			$opts['register_meta_box_cb'] = '';
			$opts['rewrite'] = FALSE;
			$opts['show_in_admin_bar'] = TRUE; // Define For 'Top Menu' bar
			$opts['show_in_menu'] = TRUE;
			$opts['show_in_nav_menu'] = TRUE;
			$opts['show_ui'] = TRUE;
			$opts['supports'] = array( 'title' );
			$opts['taxonomies'] = array();
			$opts['capabilities']['delete_others_posts'] = "delete_others_{$cap_type}s";
			$opts['capabilities']['delete_post'] = "delete_{$cap_type}";
			$opts['capabilities']['delete_posts'] = "delete_{$cap_type}s";
			$opts['capabilities']['delete_private_posts'] = "delete_private_{$cap_type}s";
			$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
			$opts['capabilities']['edit_others_posts'] = "edit_others_{$cap_type}s";
			$opts['capabilities']['edit_post'] = "edit_{$cap_type}";
			$opts['capabilities']['edit_posts'] = "edit_{$cap_type}s";
			$opts['capabilities']['edit_private_posts'] = "edit_private_{$cap_type}s";
			$opts['capabilities']['edit_published_posts'] = "edit_published_{$cap_type}s";
			$opts['capabilities']['publish_posts'] = "publish_{$cap_type}s";
			$opts['capabilities']['read_post'] = "read_{$cap_type}";
			$opts['capabilities']['read_private_posts'] = "read_private_{$cap_type}s";

			$opts['labels']['add_new'] = __( "Add New {$single}", $this->plugin_name );
			$opts['labels']['add_new_item'] = __( "Add New {$single}", $this->plugin_name );
			$opts['labels']['all_items'] = __( $plural, $this->plugin_name );
			$opts['labels']['edit_item'] = __( "Edit {$single}" , $this->plugin_name);
			$opts['labels']['menu_name'] = __( $plural, $this->plugin_name );
			$opts['labels']['name'] = __( $plural, $this->plugin_name );
			$opts['labels']['name_admin_bar'] = __( $single, $this->plugin_name );
			$opts['labels']['new_item'] = __( "New {$single}", $this->plugin_name );
			$opts['labels']['not_found'] = __( "No {$plural} Found", $this->plugin_name );
			$opts['labels']['not_found_in_trash'] = __( "No {$plural} Found in Trash", $this->plugin_name );
			$opts['labels']['parent_item_colon'] = __( "Parent {$plural} :", $this->plugin_name );
			$opts['labels']['search_items'] = __( "Search {$plural}", $this->plugin_name );
			$opts['labels']['singular_name'] = __( $single, $this->plugin_name );
			$opts['labels']['view_item'] = __( "View {$single}", $this->plugin_name );
			$opts['rewrite']['ep_mask'] = EP_PERMALINK;
			$opts['rewrite']['feeds'] = FALSE;
			$opts['rewrite']['pages'] = TRUE;
			$opts['rewrite']['slug'] = __( strtolower( $plural ), $this->plugin_name );
			$opts['rewrite']['with_front'] = FALSE;

			register_post_type( strtolower( $cpt_name ), $opts );
		}
	}

}
