<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/public
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/as-wp-braintree-payments-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/as-wp-braintree-payments-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display shortcode to display all packages.
	 *
	 * @since    1.0.0
	 */
	public function render_packages() {
		global $post;
		$packageArgs = array(
				'post_type' => 'subscription'
		);

		$packages = new WP_Query($packageArgs);

		echo '<ul class="" id="subscription-packages">';
		if($packages->have_posts()):
			while($packages->have_posts()) :
				$packages->the_post();

					$plan_id = get_post_meta($post -> ID, '_meta_box_plan_id', true);
					$plan_price = get_post_meta($post -> ID, '_meta_box_plan_price', true);
					$plan_duration = get_post_meta($post -> ID, '_meta_box_plan_duration', true);
					//$plan_description = get_post_meta($post -> ID, '_meta_box_plan_description', true);

					echo '<li data-id="'. $plan_id. '" data-price="'. $plan_price .'" data-duration="'. $plan_duration .'">';
						echo '<a href="'. get_the_permalink( ).'">';
							echo the_title( );
						echo '</a>';
					echo '</li>';
			endwhile;
		else :

		endif;
		echo '</ul>';	

		wp_reset_postdata();
	}
}
